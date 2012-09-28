<?php

class Project{
	private $HOST_IMG = '';
	private $projectMap =  array();

	private $projectName;
	private $projectPath;
	private $survey;

	//each image is just a number
	private $imagePos;

	private $properties = '/Surveys/projects.properties';
	private $noImage = '/images/default/no-survey-image.jpg';
	private $surveyImages = '/images';
	
	public $firstImage= '';
	public $lastImage = '';
	
	private $session;

	function Project($projectName, $survey, $image, Session &$session){
		$this->session = &$session;
		
		$propfile = $_SERVER['DOCUMENT_ROOT'].$this->properties;
		
		$time = filemtime($propfile);
		
		//if no session value, or the file has been altered
		if(!$session->isRegistered($this) || 
				!$time ||$session->getCreatedValue($this) < $time
				){
			$session->register($this);
			//build project list
			$handle = @fopen($propfile, "r");
			while(($buffer = fgets($handle)) !== false){
				$parts = explode(":",trim($buffer));
				$this->projectMap[$parts[0]] = '/images'.$parts[1];
				
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

		//$this->HOST_IMG = 'http://'.$_SERVER['HTTP_HOST'];
		
		if(empty($survey) || empty($image)){
			if(empty($survey))
				$survey = $this->findFirstSurvey($projectName);
			
			if(!empty($survey) && empty($image))
				$image = $this->findFirstImage($projectName, $survey);
			
			header('Location: '.$_SERVER['PHP_SELF'].'?Image='.$image.'&Project='.$projectName.'&Survey='.$survey);
		}
		
		$limits = $this->findImageLimits($projectName, $survey);
		$this->firstImage = $limits[0];
		$this->lastImage  = $limits[1];

		$this->projectName = $projectName;
		$this->projectPath = $this->projectMap[$projectName];
		$this->survey = $survey;

		$this->imagePos = intval($image);

	}

	private function findFirstSurvey($project){
		$base = $_SERVER['DOCUMENT_ROOT'].$this->surveyImages.'/'.$project;
		
		foreach(scandir($base) as $file){
			if($file[0] != '.' && is_dir($base.'/'.$file)){
				return $file;
			}
		}
		return 'failed';
	}
	
	private function getImageFile($imagePos){
		return str_pad(strval($imagePos),5,'0',STR_PAD_LEFT).'.jpg';
	}
	
	private function findImageLimits($project, $survey){
		$prefix = 'FL';
		$base = $_SERVER['DOCUMENT_ROOT'].$this->surveyImages.'/'.$project.'/'.$survey.'/'.$prefix.$survey;
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
	
	private function findFirstImage($project, $survey){
		$base = $_SERVER['DOCUMENT_ROOT'].$this->surveyImages.'/'.$project.'/'.$survey;
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
		
		if(!empty($this->HOST_IMG)){
			return "{$this->HOST_IMG}{$this->projectPath}/{$this->survey}/$prefix{$this->survey}/{$prefix}_{$imageStr}";
		} else {
			$temp = rtrim($_SERVER['DOCUMENT_ROOT'],'/\\')."/{$this->projectPath}/{$this->survey}/$prefix{$this->survey}/{$prefix}_{$imageStr}";
			if(file_exists($temp)){
				return str_replace($_SERVER['DOCUMENT_ROOT'].'/','',$temp);
			} else {
				return $this->noImage;
			}
		}
		
	}

	private function getImageResizedUrl($prefix='FL',$percent=25, $offset=0){
		$temp = $this->HOST_IMG."/imgsize.php?percent={$percent}&img=".$this->getImageLinkUrl($prefix, $offset);
		return $temp;
	}

	public function getProjectName(){
		return $this->projectName;
	}

	public function getSurvey(){
		return $this->survey;
	}

	public function getProjectPath(){
		return $this->projectPath;
	}

	public function getImagePadded(){
		return str_pad(strval($this->imagePos),5,'0',STR_PAD_LEFT);
	}

	public function getImageLinkFr(){
		return $this->getImageLinkUrl('RF');
	}

	public function getImageLinkFl(){
		return $this->getImageLinkUrl('FL');
	}

	public function getImageLinkBr(){
		return $this->getImageLinkUrl('BR');
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

		return "/Surveys/index.php?Image={$str}&Project={$this->projectName}&Survey={$this->survey}";
	}
	
	public function hasProjectImages($projectLink){
		$parts = parse_url($projectLink);
		
		parse_str($parts['query']);
		
		$path = "{$this->projectPath}/{$this->survey}/FL{$this->survey}/FL_{$Image}.jpg";
		$fullpath = null;
		
		if(empty($this->HOST_IMG)){
			return file_exists($_SERVER['DOCUMENT_ROOT'].$path);
		} else {
			return file_exists($this->HOST_IMG.$path);
		}
		
	}
}

?>