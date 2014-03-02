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
	$DS = "\\",
	$SURVEY_IMAGE_PATH = "\\images",
	$CAMERA_FRONT = "FL",
	$CAMERA_SIGN  = "RF",
	$CAMERA_BACK  = "BR";
}

class SERVICE_ACTIONS {
	public static
	$GET_SURVEY_LIMITS = "getSurveyLimits",
	$GET_SURVEYS = "getSurveys",
	//$GET_SURVEY_FIRST = "getSurveyFirstOne",
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

		$ch = curl_init($service);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($output,true);
		
		return ($json['result']) ? $json['data'] : array();
		
	}
	
	public static function validateSurveys(array $surveyList){
		$validSurveys = array();
		
		/*
		 * wont work when lots of surveys
		foreach($surveyList as $s){
			
			$url = $service . "?f=json&outputFields=Survey&returnIdsOnly=true&where=Survey='$s'";
		
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$output = curl_exec($ch);
			curl_close($ch);
			$json = json_decode($output,true);
		
			if(isset($json['objectIds']) && count($json['objectIds']) > 0){
				$validSurvey = $s;
			}
		}
		*/
		return $surveyList;
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
			$parts = explode("|",trim($buffer));
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

/**
 * This function (http_response_code) does not exist on the transmap media server, so
 * had to implament our own
 */
if (!function_exists('http_response_code')) {
	function http_response_code($code = 500) {

			switch ($code) {
				case 100: $text = 'Continue'; break;
				case 101: $text = 'Switching Protocols'; break;
				case 200: $text = 'OK'; break;
				case 201: $text = 'Created'; break;
				case 202: $text = 'Accepted'; break;
				case 203: $text = 'Non-Authoritative Information'; break;
				case 204: $text = 'No Content'; break;
				case 205: $text = 'Reset Content'; break;
				case 206: $text = 'Partial Content'; break;
				case 300: $text = 'Multiple Choices'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 303: $text = 'See Other'; break;
				case 304: $text = 'Not Modified'; break;
				case 305: $text = 'Use Proxy'; break;
				case 400: $text = 'Bad Request'; break;
				case 401: $text = 'Unauthorized'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'Not Found'; break;
				case 405: $text = 'Method Not Allowed'; break;
				case 406: $text = 'Not Acceptable'; break;
				case 407: $text = 'Proxy Authentication Required'; break;
				case 408: $text = 'Request Time-out'; break;
				case 409: $text = 'Conflict'; break;
				case 410: $text = 'Gone'; break;
				case 411: $text = 'Length Required'; break;
				case 412: $text = 'Precondition Failed'; break;
				case 413: $text = 'Request Entity Too Large'; break;
				case 414: $text = 'Request-URI Too Large'; break;
				case 415: $text = 'Unsupported Media Type'; break;
				case 500: $text = 'Internal Server Error'; break;
				case 501: $text = 'Not Implemented'; break;
				case 502: $text = 'Bad Gateway'; break;
				case 503: $text = 'Service Unavailable'; break;
				case 504: $text = 'Gateway Time-out'; break;
				case 505: $text = 'HTTP Version not supported'; break;
				default:
					exit('Unknown http status code "' . htmlentities($code) . '"');
					break;
			}

			$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

			header($protocol . ' ' . $code . ' ' . $text);

	}
}


?>