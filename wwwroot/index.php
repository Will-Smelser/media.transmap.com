<?php
//includes
include 'class/Project.php';

//definitions
define('VIEW_DEFAULT','front');

$Survey  = $_GET['Survey'];
$Image   = $_GET['Image'];
$Project = $_GET['Project'];
$Version = (empty($_GET['view'])) ? VIEW_DEFAULT : $_GET['view'];

try{
	$project = new Project($_GET['Project'], $_GET['Survey'], $_GET['image']);
}catch(Exception $e){
	echo $e->getMessage();
	die;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
    <title><?php echo $Project; ?> - Road Image Viewer - Survey: <?php echo $Survey; ?></title>
<!--    <script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAB7txdQ2-jjSmLqHcAC9kbhR-Ss8jUVT2xh8RpQxRiQ3T5sAh5RRqTe5P2QxtYmE8Cy7miLaMG3Ub5Q" type="text/javascript"></script> -->
	<script src="../js/pixastic-lib/pixastic.core.js" type="text/javascript" language="javascript"></script>
	<script src="../js/lighten.js" type="text/javascript" language="javascript"></script>


</head>

<body onunload="GUnload()">
  
  
  
<div id="container" class="container">
   
	<!-- begin header -->
    <div id="header" class="span-24" style="margin-top:24px;">
		 <!-- site logo -->
     	 <a href="http://transmap.com/" title="Home"><img class="logo" src="../blueprint/themes/amadou/logo.png" alt="Home" border="0" /></a>
   
		<p style="align:right;"><a href="http://www.transmap.com/?page_id=2"><span style="color:white">Help</span></a> | <a href="http://www.transmap.com/?page_id=2"><span style="color:white">Contact</span></a></p>
	</div>
	<!-- end header -->



	<div class="container">
		<div class="span-19 last">
			<h1><?php echo $project->getProjectName(); ?> Road Image Viewer</h1>
			<h3>Survey: <?php echo $project->getSurvey(); ?> - Image: <?php echo $project->getImagePadded(); ?></h3>
		</div>
	</div>
	
	<div class="container" style="background:#F8F8F8;padding:6px;border-width:1px;border-style:dotted;border-color:black;">
	
		<div class="span-12">
			<h3 class="room">Front Left</h3>
			<a href="<?php echo $project->getImageLinkFl(); ?>" border="0" target="new">
				<img src="<?php echo $project->getImageFl() ?> />
			</a>
		</div>
	    
		<div class="span-12 last">
	    	<h3 class="room">Right Front</h3>    
			<a href="<?php echo $project->getImageLinkFr(); ?>" border="0" target="new">
				<img src="<?php echo $project->getImageFr() ?> />
			</a>
	     </div>
	
		<div class="span-12">
			<h3 class="room">Back Right</h3> 
			<a href="<?php echo $project->getImageLinkBr(); ?>" border="0" target="new">
				<img src="<?php echo $project->getImageBr() ?> />
			</a>
		</div>
	
	
		<div class="span-6 last">
			<h3>Images x 1</h3>
			<p class="span-2">
				<a href="<?php echo $project->getNextImageUrl(1) ?>"><img src="images/f.gif" alt="forward" width="120" height="44" border="0" align="left"/></a> 
			</p>
		
			<p class="span-2">
				<a href="<?php echo $project->getNextImageUrl(-1) ?>"><img src="images/b.gif" alt="backward" width="120" height="44" border="0" align="left"/></a>
			</p>
		</div>
	
		<div class="span-6 last">
			<h3>Images x 5</h3>
			<p class="span-2">
				<a href="<?php echo $project->getNextImageUrl(5) ?>"><img src="images/f.gif" alt="backward" width="120" height="44" border="0" align="left"/></a>
			</p>
			<p class="span-2">
				<a href="<?php echo $project->getNextImageUrl(-5) ?>"><img src="images/b.gif" alt="backward" width="120" height="44" border="0" align="left"/></a>
			</p>
		</div>
	</div>

</div>

</body>
</html>