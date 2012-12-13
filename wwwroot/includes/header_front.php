<?php
//check if we have cookie set for last direction and image
if(isset($_COOKIE['camera']) || isset($_COOKIE['last-image'])){
	$cCamera = isset($_COOKIE['camera']) ? $_COOKIE['camera'] : (isset($_GET['camera'])?$_GET['camera']:"");
	$cImage = isset($_COOKIE['last-image']) ? $_COOKIE['last-image'] : $_GET['Image'];
	
	if($_GET['Image'] !== $cImage){
		header("Location: ".basename($_SERVER['PHP_SELF']) .
			 "?Image=$cImage&type=$cCamera&Survey={$_GET['Survey']}&Project={$_GET['Project']}");
	}
}


require_once '../class/Project.php';
require_once '../class/Session.php';

$session = &Session::getInstance();

//definitions
define('VIEW_DEFAULT','front');
define('IMAGE_SIZE','40');
define('IMAGE_SIZE_BR', 18);
define('IMAGE_SIZE_RF', 27);
define('IMAGE_SIZE_FL', 27);

//get everything to lowercase
foreach($_GET as $key=>$val) $_GET[strtolower($key)] = $val;

//get display vars
$version = (empty($_GET['view']))    ? VIEW_DEFAULT     : $_GET['view'];
$project1= (isset($_GET['project'])) ? $_GET['project'] : null;
$survey  = (isset($_GET['survey']))  ? $_GET['survey']  : null;
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
	exit;
}

$limits = $project->findImageLimits($project1, $survey);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  
  	<link rel="stylesheet" href="../blueprint/screen.css" type="text/css" media="screen, projection">
	<link rel="stylesheet" href="../blueprint/print.css" type="text/css" media="print">
  <!--[if IE]><link rel="stylesheet" href="../blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->



  <style type="text/css" media="all">@import "../blueprint/themes/amadou/style.css";</style>
 
 
      <!--[if IE 6]>
        <style type="text/css" media="all">@import "../themes/amadou/ie-fixes/ie6.css";</style>
      <![endif]-->

      <!--[if lt IE 7.]>
        <script defer type="text/javascript" src="../themes/amadou/ie-fixes/pngfix.js"></script>
      <![endif]-->
  
  <style type="text/css" title="text/css">
/* <![CDATA[ */
div.nicebackground { background-color: #CCC; }
h3.room { padding:.9em;}
/* ]]> */
</style>
  
  <meta name="keywords" content="El Paso,Texas,Potholes,Map,Google Map,Infrastructure" />
  <meta name="description" content="Report potholes in the El Paso, Texas area." />
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
  

  <link rel="stylesheet" href="/includes/front.css" type="text/css" media="screen">
  
  <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.2/js/dojo/dijit/themes/claro/claro.css"/>
  <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.2/js/esri/css/esri.css" />
  <script type="text/javascript" src="http://serverapi.arcgisonline.com/jsapi/arcgis/?v=3.2"></script>
   <style>
      html, body {} .esriScalebar{
      padding: 20px 20px; } #map{ padding:0;}
    </style>
  
  <script src="http://code.jquery.com/jquery-1.8.1.min.js" ></script>
  <script src="/js/cookie.js" ></script>
  <script src="/js/viewer.js" ></script>
  <script src="/js/preload.js" ></script>
  
  <script>
	dojo.require("esri.tasks.query");
	dojo.require("esri.map");
	
  	var camera = '<?php echo $camera?>';
	var imageSize = <?php echo $imageSz; ?>;
	var image = <?php echo intval($image); ?>;
	var project = "<?php echo $project1; ?>";
	var survey = "<?php echo $survey; ?>";
	var first = <?php echo intval($limits[0]); ?>;
	var last = <?php echo intval($limits[1]); ?>;

	window.onload = function(){
	//$(document).ready(function(){ //jquery load doesnt work
		var queryBaseUrl = '<?php echo $project->getProjectQueryUrl(); ?>';
		//var queryBaseUrl = "http://services.arcgis.com/Gyd9F6MUsQ0SKcSf/ArcGIS/rest/services/vanimg/FeatureServer/0";
		Viewer.load(<?php echo "'{$_SERVER['PHP_SELF']}',".$imageSz.",".intval($image).", '$project1','{$project->getProjectPath()}','$survey', '$camera','$type', first, last"; ?>,queryBaseUrl);

		//$.getJSON(Viewer.qbase.replace(/\/query/g,'')+'?f=json',init);
		dojo.ready(function(){
			$.getJSON(Viewer.qbase.replace(/\/0\/query/,"")+'?f=json',init);
		});
	};
  </script>
  
  
  
  
  
  
  
</head>

<body class="caro">
  
  
  
<div id="container" class="container">
   
	<!-- begin header -->
    <div id="header" class="span-24" style="margin-top:24px;">
		 <!-- site logo -->
     	 <a href="http://transmap.com/" title="Home"><img class="logo" src="../blueprint/themes/amadou/logo.png" alt="Home" border="0" /></a>
   
		<p style="align:right;"><a href="http://www.transmap.com/?page_id=2"><span style="color:white">Help</span></a> | <a href="http://www.transmap.com/?page_id=2"><span style="color:white">Contact</span></a></p>
	</div>
	<!-- end header -->
	