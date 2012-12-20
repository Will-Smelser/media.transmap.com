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
define('IMAGE_SIZE_BR', 25);
define('IMAGE_SIZE_RF', 38);
define('IMAGE_SIZE_FL', 38);

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
  
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
  

  <link rel="stylesheet" href="/blueprint/themes/amadou/style.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="/includes/front.css" type="text/css" media="screen" /> 	
  
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
  
  
  
     <style>
      html, body { height: 100%; width: 100%; margin: 0; padding: 0; }
      .esriScalebar{
        padding: 20px 20px;
      }
      #map{
        padding:0;
      }
      .esriPopup.myTheme .titlePane,
      .dj_ie7 .esriPopup.myTheme .titlePane .title {
        background-color: #899752;
        color: #333333;
        font-weight: bold;
      }
      .esriPopup.myTheme .titlePane {
        border-bottom: 1px solid #121310;
      }
      .esriPopup.myTheme a {
        color: #d6e68a;
      }
      .esriPopup.myTheme .titleButton,
      .esriPopup.myTheme .pointer,
      .esriPopup.myTheme .outerPointer,
      .esriPopup.myTheme .esriViewPopup .gallery .mediaHandle,
      .esriPopup.myTheme .esriViewPopup .gallery .mediaIcon {
          background-image: url(./images/popup.png);
      }
      .esriPopup.myTheme .contentPane,
      .esriPopup.myTheme .actionsPane {
        border-color: 1px solid #121310;
        background-color: #424242;
        color:#ffffff;
        max-height:150px;
     }
     .esriPopup.myTheme .contentPane div{
     	display:block;
     }
 

     #map_zoom_slider{
     	right:5px;
     	left:auto;
     }
     
     #data-details {
     	overflow:auto;
     	max-height:100%;
     }
     #data-details div{
     	
     }
     
     #map-full{
     	position:absolute;
     	top:5px;
     	left:5px;
     	width:20px;
     	height:20px;
     	background-color:#333;
     	z-index:20;
     }
     #map-full .open{
     
     }
     #map-full .close{
     
     }
    </style>
  
  
  
  
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
	