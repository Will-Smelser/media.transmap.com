<?php

class Project{
	private $DS = '/';
	private $HOST = '';
    private $IMGHOST = '/images';

	private $projectNameFS;//file system project name
	private $projectName;
	private $projectPath;
	private $projectService;
	private $survey;

	//each image is just a number
	private $imagePos;

	private $properties = '/Surveys/projects.properties';
	private $noImage = '/images/no-image.jpg';
	
	public $firstImage= '';
	public $lastImage = '';
	
	private $session;

    private $addedServices = array();

	function Project($projectName, $survey, $image, $host=null, Session &$session=null){
		$this->HOST = (empty($host)) ? '' : $host;
        $this->HOST = rtrim($this->HOST,'/\\\/');

        $this->projectName = $projectName;
        $this->survey = $survey;
        $this->imagePos = intval($image);

        $this->session=$session;
		
		$propfile = $_SERVER['DOCUMENT_ROOT'].$this->properties;

        //build project list
        $handle = fopen($propfile, "r");

        if(!$handle){
            throw new Exception("Failed to read property file.");
            return;
        }

        while(($buffer = fgets($handle)) !== false){
            $parts = explode("|",trim($buffer));

            if(!isset($parts[2]))
                throw new Exception("Missing service name in properties file.");

            if($parts[0] === $projectName){
                $imgsvr = (empty($parts[3])) ? $this->IMGHOST : $parts[3];

                $this->projectPath = preg_replace('@^/images@i','',$parts[1]);
                $this->projectService = $parts[2];

                $this->IMGHOST = rtrim($imgsvr,'\/\\');

                $limits = $this->getLimits();

                $this->firstImage = $limits[0];
                $this->lastImage = $limits[1];

                //the new added services
                if(isset($parts[4])){
                    foreach(explode('@',$parts[4]) as $service){
                        $sparts = explode('!',$service);
                        array_push($this->addedServices,array('unique'=>$sparts[0],'name'=>$sparts[1],'service'=>$sparts[2]));
                    }
                }

                fclose($handle);
                return;
            }
        }
        fclose($handle);

        throw new Exception("Project name failed to map to project directory.");
	}

	private function findFirstSurvey($projectPath){

		$base = $_SERVER['DOCUMENT_ROOT'].$this->DS.$projectPath;

        if(!is_dir($base)) return 'failed';

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
	
	private function getImageLinkUrl($prefix='FL', $offset = 0){
		$temp = null;
		
		$imagePos = $this->imagePos + $offset;
		$imageStr = $this->getImageFile($imagePos);

        return "{$this->projectPath}/{$this->survey}/$prefix{$this->survey}/{$prefix}_{$imageStr}";
	}

	private function getImageResizedUrl($prefix='FL',$percent=25, $offset=0){
		$temp = $this->HOST."/imgsize.php?percent={$percent}&img=".$this->IMGHOST.$this->getImageLinkUrl($prefix, $offset);
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
		return $this->IMGHOST.$this->getImageLinkUrl('RF');
	}

	public function getImageLinkFl(){
		return $this->IMGHOST.$this->getImageLinkUrl('FL');
	}

	public function getImageLinkBr(){
		return $this->IMGHOST.$this->getImageLinkUrl('BR');
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

    public function hasProjectImages($imageNumber){

        return ($imageNumber <= $this->lastImage && $imageNumber >= $this->firstImage);
    }

    public function getLimits(){
        $query = 'http://'.$this->projectService . '/0/query?f=json&where=Survey%3D\''.$this->survey.'\'&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=Survey&groupByFieldsForStatistics=Survey&outStatistics='.
            urlencode('[{"statisticType": "min","onStatisticField": "IMAGENUM","outStatisticFieldName": "min"},{"statisticType": "max","onStatisticField": "IMAGENUM","outStatisticFieldName": "max"}]');

        try{
            $result = file_get_contents($query);
            if(!$result){
                return array(0=>0,1=>9999);
            }
            $result = json_decode($result);

            if(isset($result->features[0]->attributes)){
                return array(0=>$result->features[0]->attributes->min,1=>$result->features[0]->attributes->max);
            }else{
                return array(0=>0,1=>9999);
            }
        }catch(Exception $e){
            return array(0=>0,1=>9999);
        }
    }

    public function getSurveys(){
        $query = 'http://'.$this->projectService . "/0/query?f=json&where=1%3D1&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=Survey&groupByFieldsForStatistics=Survey&outStatistics=[{%22statisticType%22:%20%22count%22,%22onStatisticField%22:%20%22Survey%22,%22outStatisticFieldName%22:%20%22Cnt%22}]&orderByFields=Survey%20ASC";

        try{
            $result = file_get_contents($query);
            if(!$result) return array($this->getSurvey());

            $result = json_decode($result);

            $arr = array();
            foreach($result->features as $obj){
                //need to get the number
                preg_match('/(?P<main>\d+)\((?P<id>\d+)\)/',$obj->attributes->Survey,$matches);
                if(!empty($matches['main'])){
                    $key = $matches['main'].'-'.str_pad($matches['id'], 4, "0", STR_PAD_LEFT);
                    $arr[$key]=$obj->attributes->Survey;
                }
            }
            ksort($arr);
            return $arr;
        }catch(Exception $e){
            return array($this->getSurvey(),'Error Loading');
        }
    }

    public function getImgServer(){
        return $this->IMGHOST;
    }

    public function addedServicesJS(){
        echo "[";
        $comma = '';
        foreach($this->addedServices as $service){
            echo $comma . "{unique:\"{$service['unique']}\",name:\"{$service['name']}\",service:\"{$service['service']}\"}";
            $comma = ',';
        }
        echo "]";
    }
}

?>