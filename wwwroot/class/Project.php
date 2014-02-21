<?php

class Project{
	private $DS = '/';
	private $HOST = '';
	private $LOCAL = true;
	private $projectMap =  array();

	private $projectNameFS;//file system project name
	private $projectName;
	private $projectPath;
	private $projectService;
	private $survey;

	//each image is just a number
	private $imagePos;

	private $properties = '/Surveys/projects.properties';
	private $noImage = '/images/default/no-survey-image.jpg';
	
	public $firstImage= '';
	public $lastImage = '';
	
	private $session;

	function Project($projectName, $survey, $image, Session &$session, $host=null, $local=true, $low=0, $high=99999){
		$this->HOST = (empty($host)) ? '' : $host;
		$this->HOST = rtrim($this->HOST,'/\\');
		$this->session = &$session;
		$this->LOCAL = $local;
		
		$propfile = $_SERVER['DOCUMENT_ROOT'].$this->properties;
		
		$time = filemtime($propfile);
		
		//if no session value, or the file has been altered
		if(!$session->isRegistered($this) || 
				!$time ||$session->getCreatedValue($this) < $time
				){
			$session->register($this);
			
			//build project list
			$handle = fopen($propfile, "r");
			
			if(!$handle){
				throw new Exception("Failed to read property file.");
				return;
			}
			
			while(($buffer = fgets($handle)) !== false){
				$parts = explode(":",trim($buffer));
				$service = (empty($parts[2])) ? null : $parts[2];
				$this->projectMap[$parts[0]] = array($parts[1],$service);
			}
			
			fclose($handle);
			$session->setNameValue($this, 'projectMap', $this->projectMap);
		} else {
			$this->projectMap = $session->getValue($this, 'projectMap');
		}
		
		//make sure the key exists
		if(!array_key_exists($projectName,$this->projectMap)){
			throw new Exception("Project name failed to map to project directory.");
		}
		
		$path = $this->projectMap[$projectName][0];
		
		//if this is hosted, then we dont have access to directory
		//listings and cannot perform these checks
		if($this->LOCAL){
			
			//verify the file paths are valid
			$basePath = $_SERVER['DOCUMENT_ROOT'].$this->DS.$path;
			if(!is_dir($basePath)){
				throw new Exception("Bad project path given ($basePath)");
				return;
			}
			
			//works if we werent given a survey, because it is just same check
			//as above
			if(!is_dir($basePath.$this->DS.$survey)){
				throw new Exception("Bad survey path given ({$basePath}{$this->DS}{$survey})");
				return;
			}
			
			if(empty($survey) || empty($image)){
				if(empty($survey))
					$survey = $this->findFirstSurvey($path);
				
				if(!empty($survey) && empty($image))
					$image = $this->findFirstImage($path, $survey);
				
				if(!empty($survey) && !empty($image) && (empty($_GET['Image']) || empty($_GET['Survey']))){
					header('Location: '.$_SERVER['PHP_SELF'].'?Image='.$image.'&Project='.$projectName.'&Survey='.$survey);
				} else {
					throw new Exception("Failed to locate valid survey and/or starting image.");
				}
			}
			
			$this->firstImage = $low;
			$this->lastImage  = $high;
		}
		$this->projectName = $projectName;
		$this->projectPath = $this->projectMap[$projectName][0];
		$this->projectService = $this->projectMap[$projectName][1];
		$this->survey = $survey;

		$this->imagePos = intval($image);
	}

	private function findFirstSurvey($projectPath){
		if(!$this->LOCAL) 'failed';
		
		$base = $_SERVER['DOCUMENT_ROOT'].$this->DS.$projectPath;
		
		foreach(scandir($base) as $file){
			if($file[0] != '.' && is_dir($base.$this->DS.$file)){
				return $file;
			}
		}
		return 'failed';
	}
	
	private function getImageFile($imagePos){
		return str_pad(strval($imagePos),5,'0',STR_PAD_LEFT).'.jpg';
	}
	
	public function findImageLimits(){
		if(!$this->LOCAL) return array(0,99999);
		$prefix = 'FL';
		$base = $_SERVER['DOCUMENT_ROOT'].$this->projectPath.$this->DS.$this->survey.$this->DS.$prefix.$this->survey;
		
		if(!is_dir($base)){
			return array(0,99999);
		}
		
		$dirs = scandir($base);
		
		$first = $last = null;
		
		if(count($dirs) > 0)
			while(!preg_match('/(jpe?g)$/i',($first=array_shift($dirs))) && count($dirs) > 0);
			
		if(count($dirs) > 0)
			while(!preg_match('/(jpe?g)$/i',($last=array_pop($dirs))) && count($dirs) > 0);
		
		$first = (preg_match('/(jpe?g)$/i',$first)) ? $first : null;
		$last  = (preg_match('/(jpe?g)$/i',$last)) ? $last : $first;
		
		$first = preg_replace("/(({$prefix}_)|(\.jpe?g))/i",'',$first);
		$last  = preg_replace("/(({$prefix}_)|(\.jpe?g))/i",'',$last);
		
		return array($first, $last);
	}
	
	private function findFirstImage($projectPath, $survey){
		$base = $_SERVER['DOCUMENT_ROOT'].$projectPath.'/'.$survey;
		foreach(scandir($base) as $file){
			if($file[0] != '.' && is_dir($base.'/'.$file)){
				foreach(scandir($base.'/'.$file) as $img){
					if(preg_match('/(\.jpe?g)$/i',$img)){
						$basefile = basename($img);
						$parts = explode('_',$basefile);
						if(isset($parts[1]))
							return preg_replace('/\.jpe?g/i','',$parts[1]);
						
						return 'failed';
					}
				}
			}
		}
		
		return 'failed';
	}
	
	private function getImageLinkUrl($prefix='FL', $offset = 0){
		$temp = null;
		
		$imagePos = $this->imagePos + $offset;
		$imageStr = $this->getImageFile($imagePos);
		
		if(!$this->LOCAL){
			return "{$this->projectPath}/{$this->survey}/$prefix{$this->survey}/{$prefix}_{$imageStr}";
		} else {
			$temp = rtrim($_SERVER['DOCUMENT_ROOT'],'/\\').$this->projectPath.
				$this->DS . $this->survey . $this->DS . $prefix . 
				$this->survey . $this->DS . "{$prefix}_{$imageStr}";
			if(file_exists($temp)){
				return str_replace($_SERVER['DOCUMENT_ROOT'],'',$temp);
			} else {
				return $this->noImage;
			}
		}
		
	}

	private function getImageResizedUrl($prefix='FL',$percent=25, $offset=0){
		$temp = $this->HOST."/imgsize.php?percent={$percent}&img=".$this->getImageLinkUrl($prefix, $offset);
		return $temp;
	}

	public function getProjectName(){
		return $this->projectName;
	}

	public function getSurvey(){
		return $this->survey;
	}
	
/**
	 * Get an array list of strings of surveys in this
	 * project directory
	 * @TODO Fix this because it doesnt work on server
	 * @return multitype:
	 */
	public function getSurveys(){
		if($this->LOCAL){
			$result = array();
			foreach(scandir($_SERVER['DOCUMENT_ROOT'].$this->DS.$this->getProjectPath()) as $val){
				if(preg_match("/[\d]+\([\d]+[\)]$/",$val)){
					array_push($result, $val);
				}
			}
			if(count($result) == 0)
				array_push($result,$this->getSurvey());
			
			return $result;
		}
		
		return array($this->getSurvey());
	}

	public function getProjectPath(){
		return $this->projectPath;
	}
	
	public function getProjectNameFileSystem(){
		return preg_replace('/\/?images\/?/i','',$this->getProjectPath());
	}
	
	public function getProjectServiceBaseUrl(){
		return (empty($this->projectService)) ? null : "http://{$this->projectService}";
	}
	
	public function getProjectQueryUrl(){
		$base = $this->getProjectServiceBaseUrl();
		if(empty($base)) return null;
		
		//check if we have this already in session
		$key = 'projectQueryUrl-'.$this->projectName;
		$url = $this->session->getValue($this, $key);
		if(!empty($url)) return $url;
		
		//get a list of services
		$ch = curl_init($base.'/0?f=json');
		$options = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_HTTPHEADER => array('Content-type: application/json')
		);
		
		// Setting curl options
		curl_setopt_array( $ch, $options);
		$output = curl_exec($ch);
		curl_close($ch);
		
		$json = array();
		try{
			$json = json_decode($output,true);
		}catch(Exception $e){
			//do nothing
		}
		
		$url = $base . "/0/query";//?f=json&returnGeometry=false&";
		//$url.= 'GeometryType=esriGeometryEnvelope&';
		//$url.= 'outFields=*&';
		//$url.= 'Geometry='.urlencode(json_encode($json['extent']));

		$this->session->setNameValue($this, $key, $url);
		
		return $url;
	}

	public function getImagePadded(){
		return str_pad(strval($this->imagePos),5,'0',STR_PAD_LEFT);
	}

	public function getImageLinkFr(){
		return $this->HOST.$this->getImageLinkUrl('RF');
	}

	public function getImageLinkFl(){
		return $this->HOST.$this->getImageLinkUrl('FL');
	}

	public function getImageLinkBr(){
		return $this->HOST.$this->getImageLinkUrl('BR');
	}

	public function getImage($camera='FL', $offset=0, $size){
		return $this->getImageResizedUrl($camera, $size, $offset);
	}
	
	public function getImageFr($offset=0, $size=25){
		return $this->getImageResizedUrl('RF',$size,$offset);
	}

	public function getImageFl($offset=0, $size=25){
		return $this->getImageResizedUrl('FL',$size,$offset);
	}

	public function getImageBr($offset=0, $size=18){
		return $this->getImageResizedUrl('BR',$size,$offset);
	}

	public function getNextImageUrl($offset){
		$pos = $this->imagePos + intval($offset);
		$str = str_pad(strval($pos),5,'0',STR_PAD_LEFT);

        $path = str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SERVER['PHP_SELF']);//__DIR__;

		return "$path?Image={$str}&Project={$this->projectName}&Survey={$this->survey}";
	}
	
	public function hasProjectImages($projectLink){
		$parts = parse_url($projectLink);
		
		parse_str($parts['query']);
		
		$path = $this->projectPath.$this->DS.$this->survey.$this->DS.'FL'.
			$this->survey.$this->DS."FL_{$Image}.jpg";
		$fullpath = null;
		
		if($this->LOCAL){
			return file_exists($_SERVER['DOCUMENT_ROOT'].$path);
		} else {
			return file_exists($this->HOST.$path);
		}
		
	}
}

?>