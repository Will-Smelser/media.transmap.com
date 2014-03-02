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
		error_reporting(E_ALL);
		http_response_code($this->header_status);
	}
}

$action = (isset($_GET['action'])) ? $_GET['action'] : 'none';

$result = new Result(false, "Invalid Action", null, HTTP_STATUS::$BAD_REQUEST);
switch($action){
	case SERVICE_ACTIONS::$GET_SURVEY_LIMITS:
		$result->messages = array();

        require_once '../class/Project.php';
        try{
            $project = new Project($_GET['project'], $_GET['survey'], 0);
            $limits = $project->getLimits();
            $result->data = array("lower"=>$limits[0],'upper'=>$limits[1]);
        }catch(Exception $e){
            $result->addMessage($e);
        }
		
		//set result to valid results
		$result->header_status = HTTP_STATUS::$SUCCESS;
		$result->addMessage("Success");
		$result->result = true;
		break;
		
	/**
	 * Get a list of surveys
	 */
	case SERVICE_ACTIONS::$GET_SURVEYS:
		$result->messages = array();

        require_once '../class/Project.php';
        try{
            $project = new Project($_GET['project'], null, 0);
            $result->data = $project->getSurveys();
        }catch(Exception $e){
            $result->addMessage($e);
        }

        //set result to valid results
        $result->header_status = HTTP_STATUS::$SUCCESS;
        $result->addMessage("Success");
        $result->result = true;
        break;

	case $GET_PROJECTS:
		$result->messages = array();
		
		$temp = Utils::getPropfileContents();
		if($temp === false){
			$result->addMessage('Failed to read property file contents.');
			break;
		}
		
		$result->result = true;
		$result->header_status = HTTP_STATUS::$SUCCESS;
		$result->addMessage("Success");
		$result->data = $temp;
		
		break;
}

$result->sendHTTPresponse();
echo $result->toJSON();
?>