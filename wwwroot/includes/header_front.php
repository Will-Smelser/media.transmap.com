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
	
	$project = new Project($project1, $survey, $image, $session, null,true);
	
}catch(Exception $e){
	echo $e->getMessage();
	echo "Go <a href='findSurvey.php'>here</a> to choose a valid survey.";
	exit;
}

$limits = $project->findImageLimits($project1, $survey);

function listSurveys($surveys, $currentSurvey){
	foreach($surveys as $entry){
		echo "<option value='${entry}' ";
		echo ($entry === $currentSurvey) ? 'selected' : '';
		echo ">$entry</option>\n";
	}
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
  

  <link rel="stylesheet" href="/blueprint/themes/amadou/style.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="/includes/front.css" type="text/css" media="screen" /> 
  <link rel="stylesheet" href="/includes/map.css" type="text/css" media="screen" />	
  
  <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.2/js/dojo/dijit/themes/claro/claro.css"/>
  <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.2/js/esri/css/esri.css" />
  <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.2/js/dojo/dojox/layout/resources/ResizeHandle.css">
  
  <style>
      html, body {} .esriScalebar{
      padding: 20px 20px; } #map{ padding:0;}

  </style>
  
  <script type="text/javascript" src="http://serverapi.arcgisonline.com/jsapi/arcgis/?v=3.2"></script>  
  <script src="http://code.jquery.com/jquery-1.8.1.min.js" ></script>
  <script src="/js/cookie.js" ></script>
  <script src="/js/viewer.js" ></script>
  <script src="/js/preload.js" ></script>
  
  <script>
	  var dojoConfig = {
	          parseOnLoad: true
	  };
    dojo.require("esri.map");
	dojo.require("esri.tasks.query");
    dojo.require("dijit.layout.BorderContainer");
    dojo.require("dijit.layout.ContentPane");
    dojo.require("esri.layers.FeatureLayer");
    dojo.require("esri.dijit.Popup");
    dojo.require("esri.geometry");
	
  	var camera = '<?php echo $camera?>';
	var imageSize = <?php echo $imageSz; ?>;
	var image = <?php echo intval($image); ?>;
	var project = "<?php echo $project1; ?>";
	var survey = "<?php echo $survey; ?>";
	var first = <?php echo intval($limits[0]); ?>;
	var last = <?php echo intval($limits[1]); ?>;

	window.map;
    window.featureLayer;
   
  </script>

  
  
  
  
</head>

<?php include "body_start.php"; ?>
	