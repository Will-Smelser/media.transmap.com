<?php 
/**
 * This page handles returning requested data
 */
include_once '../class/Utilities.php';

class Result {
	public $result; //boolean
	public $messages; // array of message
	public $data; //the data
	public $header_status = 200; //http status codes
	
	public function Result($result, $message, $data, $status){
		$this->result = $result;
		$this->messages = array($message);
		$this->data = ($data === null) ? array() : $data;
		$this->header_status = $status;
	}
	
	public function addMessage($msg){
		array_push($this->messages,$msg);
	}
	
	public function toJSON(){
		return json_encode(array(
			"result"=>$this->result,
			"messages"=>$this->messages,
			"data"=>$this->data		
		));
	}
	
	public function sendHTTPresponse(){
		http_response_code ($this->header_status);
	}
}

$action = (isset($_GET['action'])) ? $_GET['action'] : 'none';

$result = new Result(false, "Invalid Action", null, HTTP_STATUS::$BAD_REQUEST);
switch($action){
	case SERVICE_ACTIONS::$GET_SURVEY_LIMITS:
		$result->messages = array();
		
		//check we have all the requested variables
		if(!isset($_GET['survey'])){
			$result->addMessage("Missing parameter: survey");
			break;
		}
		$basePath = $_SERVER['DOCUMENT_ROOT'] . SETTINGS::$DS
			. SETTINGS::$SURVEY_IMAGE_PATH;
		if(!is_dir($basePath)){
			$result->addMessage("Failed to locate image path");
			break;
		}
		
		//check the path...allow for invalid case
		$project = $_GET['project'];
		foreach(scandir($basePath) as $dir){
			if($dir[0] !== "." && preg_match("/{$dir}/i",$project))
				$project = $dir; break;
		}
		$basePath .= SETTINGS::$DS . $project;
		
		if(!is_dir($basePath)){
			$result->addMessage("Failed to locate project path ($project).");
			break;
		}
		
		//check the path...allow for invalid case
		$survey = $_GET['survey'];
		foreach(scandir($basePath) as $dir){
			if($dir[0] !== "." && preg_match("/{$dir}/i",$survey))
				$survey = $dir; break;
		}
		
		//check we have the survey
		$path = $basePath . SETTINGS::$DS . $survey;
		if(!is_dir($path)){
			$result->addMessage("Failed to find survey path ($survey).");
			break;
		}
		
		$camera = $_GET['camera'];
		if($camera !== SETTINGS::$CAMERA_BACK &&
				$camera !== SETTINGS::$CAMERA_FRONT &&
				$camera !== SETTINGS::$CAMERA_SIGN){
			$result->addMessage("Invalid camera given($camera).");
			break;
		}
		$camera .= $survey;
		$path .= SETTINGS::$DS . $camera;
		
		$image = array();
		try{
			$images = scandir($path);
		} catch (Exception $e){
			$result->addMessage("Failed to open survey camera directory($camera).");
			break;
		}
		
		//remove all .<filename>
		while($images[0][0] === ".")
			array_shift($images);
		
		//check the image count is atleast 2
		if(count($images) < 2){
			$result->addMessage("Failed to locate range of images.");
			$result->header_status = HTTP_STATUS::$SUCCESS;
			break;
		}
		
		$first = Utils::getImageFromFile(array_shift($images));
		$last = Utils::getImageFromFile(array_pop($images));
		
		//verify the filenames were valid
		if(empty($first) || empty($last)){
			$result->addMessage("Failed to locate range of images.  Bad file name(s).");
			$result->header_status = HTTP_STATUS::$SUCCESS;
			break;
		}
		
		//set result to valid results
		$result->data = array("lower"=>$first,'upper'=>$last);
		$result->header_status = HTTP_STATUS::$SUCCESS;
		$result->addMessage("Success");
		$result->result = true;
		break;
}

$result->sendHTTPresponse();
echo $result->toJSON();
?>