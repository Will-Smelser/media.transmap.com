<?php
//force all GET vars to lowercase
foreach($_GET as $key=>$val)
	$_GET[strtolower($key)] = $val;

//check that we have a survey and project

if(!isset($_GET['survey']) || !isset($_GET['project'])){
	header('Location: findSurvey.php');
	exit;
}

require_once '../class/Project.php';
require_once '../class/Session.php';
require_once '../class/Utilities.php';

$session = &Session::getInstance();

//definitions
define('VIEW_DEFAULT','front');
define('IMAGE_SIZE','40');
define('IMAGE_SIZE_BR', 25);
define('IMAGE_SIZE_RF', 38);
define('IMAGE_SIZE_FL', 38);

//get everything to lowercase
foreach($_GET as $key=>$val) $_GET[strtolower($key)] = $val;

//get display vars
$version = (empty($_GET['view']))    ? VIEW_DEFAULT     : $_GET['view'];
$project1= $_GET['project'];
$survey  = $_GET['survey'];
$image   = (isset($_GET['image']))   ? $_GET['image']   : null;
$camera  = (isset($_GET['type']))  ? $_GET['type']  : 'p';

switch($camera){
	default:
	case 'p':
		$camera = 'BR';
		$type = 'p';
		$imageSz = IMAGE_SIZE_BR;
		break;
	case 's':
		$camera = 'RF';
		$type = 's';
		$imageSz = IMAGE_SIZE_RF;
		break;
	case 'f':
		$type = 'f';
		$camera = 'FL';
		$imageSz = IMAGE_SIZE_FL;
}

try{
	
	$project = new Project($project1, $survey, $image, null, $session);
	
}catch(Exception $e){
	echo $e->getMessage();
	echo "Go <a href='findSurvey.php'>here</a> to choose a valid survey.";
	exit;
}

function listSurveys($surveys, $currentSurvey){
	foreach($surveys as $entry){
		echo "<option value='${entry}' ";
		echo ($entry === $currentSurvey) ? 'selected' : '';
		echo ">$entry</option>\n";
	}
}

?>
	