<?php

class Project{
	private $HOST_IMG = '';
	private $projectMap =  array(
			'Alexandria'=>"Media10/Alexandria",
			'Greenburgh'=>'Media10/Greenburgh',
			'ElPaso'=>'Media3/ElPaso/Images',
			'Allegheny'=>'Media10/Allegheny/Images',
			'SeaTac'=>'Media2/SeaTac/Images',
			'Fresno'=>'Media10/Fresno/Images',
			'VCDD'=>'Media10/VCDD/Images',
			'Sarasota'=>'Media10/Sarasota',
			'I595'=>'',
			'Schertz'=>'Media10/Schertz',
			'ICA'=>'Media3/ICA',
			'Manatee','Media3/Manatee',
			'redwoodcity'=>'Media10/Redwood',
			'Irvine'=>'Media10/Irvine',
			'HuberHeights'=>'Media10/Huber_Heights',
			'PutnamCounty'=>'Media10/PutnamCounty',
			'Watertown'=>'Media10/Watertown',
			'JIMI'=>'Media10/JIMI',
			'ErieCounty'=>'Media10/Erie',
			'Buffalo'=>'Media10/Buffalo',
			'Casper'=>'Media11/Casper',
			'Wilmington'=>'Media11/Wilmington',
			'Lakeland'=>'Media11/Lakeland',
			'SIPOA'=>'Media11/SIPOA',
			'Cary'=>'Media11/Cary',
			'Milton'=>'/images/Milton',
			'Escambia'=>'Media11/Escambia',
			'Kettering'=>'Media11/Kettering',
			'Hanford'=>'Media11/Hanford'
	);
	
	
	private $projectName;
	private $projectPath;
	private $survey;
	
	//each image is just a number
	private $imagePos;
	private $imageStr;
	
	private $noImage = "/images/default/no-survey-image.jpg";
	
	private $imageLinkUrlFr;
	private $imageLinkUrlFl;
	private $imageLinkUrlBr;
	
	private $imageUrlFr;
	private $imageUrlFl;
	private $imageUrlBr;
	
	function Project($projectName, $survey, $image){
		
		//make sure the key exists
		if(!array_key_exists($projectName,$this->projectMap)){
			throw new Exception("Project name failed to map to project directory.");
		}
		
		//$this->HOST_IMG = 'http://'.$_SERVER['HTTP_HOST'];
		
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
		$temp = "/images/percent/{$percent}".$this->getImageLinkUrl($prefix);
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