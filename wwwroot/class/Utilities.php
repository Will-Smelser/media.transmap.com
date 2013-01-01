<?php 
class HTTP_STATUS {
	public static
	$SUCCESS		=200,
	$BAD_REQUEST	=400,
	$NOT_IMPLAMENTED=501,
	$UNAUTHORIZED	=401;
}

class SETTINGS {
	public static
	$DS = "/",
	$SURVEY_IMAGE_PATH = "/images",
	$CAMERA_FRONT = "FL",
	$CAMERA_SIGN  = "RF",
	$CAMERA_BACK  = "BR";
}

class SERVICE_ACTIONS {
	public static
	$GET_SURVEY_LIMITS = "getSurveyLimits",
	$GET_SURVEYS = "getSurveys",
	$GET_SURVEY_FIRST = "getSurveyFirstOne",
	$GET_PROJECTS = "getProjects";
}

class Utils {
	public static function getImageFromFile($filename){
		$matches;
		preg_match("/[\w]{2}(?P<number>[\d]+)\.[[jpe?g]|[png]|[gif]]/i",$filename,$matches);
		return (isset($matches['number'])) ? $matches['number'] : null;
	}
	
	public static function getServiceUrl(){
		return 'http://' . $_SERVER['HTTP_HOST'] . '/surveys/service.php';
	}
	
	public static function getValidSurveys($project,$ersiRawService){
		$ersiRawService = urlencode($ersiRawService);
		$service = Utils::getServiceUrl();
		$service.= "?action=getSurveys&project=$project&serviceUrl=$ersiRawService";
		var_dump($service);
		$ch = curl_init($service);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($output,true);
		return ($json['result']) ? $json['data'] : array();
		
	}
	
	public static function getPropfileContents(){
		$file = $_SERVER['DOCUMENT_ROOT'].SETTINGS::$DS.'Surveys'.SETTINGS::$DS.'projects.properties';
		
		$handle = null;
		try{
			$handle = @fopen($file, "r");
		}catch(Exception $e){
			return false;
		}
		
		$data = array();
		while(($buffer = fgets($handle)) !== false){
			$parts = explode(":",trim($buffer));
			$info = array(
					'service'=>(isset($parts[2]) ? $parts[2] : null),
					'name'=>(isset($parts[0]) ? $parts[0] : null),
					'path'=>(isset($parts[1]) ? $parts[1] : null)
			);
			array_push($data,$info);
		}
		fclose($handle);
		return $data;
	}
}

?>