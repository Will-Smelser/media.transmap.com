<?php

class Project{
	private $HOST_IMG = '';
	private $projectMap =  array();

	private $projectName;
	private $projectPath;
	private $survey;

	//each image is just a number
	private $imagePos;
	private $imageStr;

	private $properties = '/Surveys/projects.properties';
	private $noImage = "/images/default/no-survey-image.jpg";

	private $imageLinkUrlFr;
	private $imageLinkUrlFl;
	private $imageLinkUrlBr;

	private $imageUrlFr;
	private $imageUrlFl;
	private $imageUrlBr;

	function Project($projectName, $survey, $image){
		//build project list
		$handle = @fopen($_SERVER['DOCUMENT_ROOT'].$this->properties, "r");
		while(($buffer = fgets($handle)) !== false){
			$parts = explode(":",trim($buffer));
			$this->projectMap[$parts[0]] = '/images'.$parts[1];
		}
		fclose($handle);
		
		//make sure the key exists
		if(!array_key_exists($projectName,$this->projectMap)){
			throw new Exception("Project name failed to map to project directory.");
		}

		$this->HOST_IMG = 'http://'.$_SERVER['HTTP_HOST'];
		//$this->HOST_IMG = 'http://media.transmap.com';

		$this->projectName = $projectName;
		$this->projectPath = $this->projectMap[$projectName];
		$this->survey = $survey;

		$this->imagePos = intval($image);
		$this->imageStr = str_pad(strval($this->imagePos),5,'0',STR_PAD_LEFT).'.jpg';

		$this->imageLinkUrlFl = $this->getImageLinkUrl('FL');
		$this->imageLinkUrlFr = $this->getImageLinkUrl('RF');
		$this->imageLinkUrlBr = $this->getImageLinkUrl('BR');

		$this->imageUrlFr = $this->getImageResizedUrl('RF',25);
		$this->imageUrlFl = $this->getImageResizedUrl('FL',25);
		$this->imageUrlBr = $this->getImageResizedUrl('BR',18);

	}

	private function getImageLinkUrl($prefix='FL'){
		$temp = "{$this->HOST_IMG}{$this->projectPath}/{$this->survey}/$prefix{$this->survey}/{$prefix}_{$this->imageStr}";

		//if(file_exists($temp)){
			return $temp;
		//}
		//return $this->noImage;
	}

	private function getImageResizedUrl($prefix='FL',$percent=25){
		$temp = $this->HOST_IMG."/imgsize.php?percent={$percent}&img=".$this->getImageLinkUrl($prefix);
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
		return $this->imageLinkUrlFr;
	}

	public function getImageLinkFl(){
		return $this->imageLinkUrlFl;
	}

	public function getImageLinkBr(){
		return $this->imageLinkUrlBr;
	}

	public function getImageFr(){
		return $this->imageUrlFr;
	}

	public function getImageFl(){
		return $this->imageUrlFl;
	}

	public function getImageBr(){
		return $this->imageUrlBr;
	}

	public function getNextImageUrl($offset){
		$pos = $this->imagePos + $offset;
		$str = str_pad(strval($pos),5,'0',STR_PAD_LEFT);

		return "/Surveys/index.php?Image={$str}&Project={$this->projectName}&Survey={$this->survey}";
	}

}

?>