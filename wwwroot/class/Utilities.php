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
	$GET_SURVEY_LIMITS = "getSurveyLimits";
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
}

?>