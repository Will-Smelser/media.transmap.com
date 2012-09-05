<?php


$Survey = $_GET["Survey"];
$Image = $_GET["Image"];
$Project = $_GET["Project"];

$multiple = $_GET["multiple"];

		if (!$Project || !$Survey || !$Image)  {
		die;
		}

//http://projects.transmap.com/media2/".$Project."_Images/".$Survey."(17)/FL_00".$Image.".jpg

//http://projects.transmap.com/media2/".$Project."/".$Survey."/".$Image.".jpg


			if ($Project == "Alexandria")  {
			$Projectname = "Media10/Alexandria";
			$Projectname;
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "Greenburgh")  {
			$Projectname = "Media10/Greenburgh";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "ElPaso")  {
			$Projectname = "Media3/ElPaso/Images";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "Allegheny")  {
			$Projectname = "Media10/Allegheny/Images";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "SeaTac")  {
			$Projectname = "Media2/SeaTac/Images";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
		
			if ($Project == "Fresno")  {
			$Projectname = "Media10/Fresno/Images";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}

			if ($Project == "VCDD")  {
			$Projectname = "Media10/VCDD/Images";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "Sarasota")  {
			$Projectname = "Media10/Sarasota";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "I595")  {
			$Projectname = "";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "Schertz")  {
			$Projectname = "Media10/Schertz";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "ICA")  {
			$Projectname == "Media3/ICA";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "Manatee")  {
			$Projectname == "Media3/Manatee";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "redwoodcity")  {
			$Projectname == "Media10/Redwood";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "Irvine")  {
			$Projectname == "Media10/Irvine";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			if ($Project == "HuberHeights")  {
			$Projectname == "Media10/Huber_Heights";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			if ($Project == "PutnamCounty")  {
			$Projectname == "Media10/PutnamCounty";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			if ($Project == "Watertown")  {
			$Projectname == "Media10/Watertown";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			if ($Project == "JIMI")  {
			$Projectname == "Media10/JIMI";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			if ($Project == "ErieCounty")  {
			$Projectname == "Media10/Erie";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			if ($Project == "Buffalo")  {
			$Projectname == "Media10/Buffalo";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			if ($Project == "Casper")  {
			$Projectname == "Media11/Casper";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			if ($Project == "Wilmington")  {
			$Projectname == "Media11/Wilmington";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			if ($Project == "Lakeland")  {
			$Projectname == "Media11/Lakeland";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}

			if ($Project == "SIPOA")  {
			$Projectname == "Media11/SIPOA";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}

			if ($Project == "Cary")  {
			$Projectname == "Media11/Cary";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}


			if ($Project == "Milton")  {
			$Projectname == "Media11/Milton";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			if ($Project == "Escambia")  {
			$Projectname == "Media11/Escambia";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}
			
			
			if ($Project == "Kettering")  {
			$Projectname == "Media11/Kettering";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}



	if ($Project == "Hanford")  {
			$Projectname == "Media11/Hanford";
			$SurveynameFL = $Survey;
			$SurveynameRF = $Survey;
			$SurveynameBR = $Survey;
			}



			
			
			
//http://projects.transmap.com/Media2/Alexandria_Images/08091514(17)/FL_00137.jpg

if ($Project == "Sarasota") {

$filename = "".$Image.".jpg";
$FL_url = "http://projects.transmap.com/".$Projectname."/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
$RF_url = "http://projects.transmap.com/".$Projectname."/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
$BR_url = "http://projects.transmap.com/".$Projectname."/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";

} 

if ($Project == "Allegheny") {

$filename = "".$Image.".jpg";
$FL_url = "http://projects.transmap.com/".$Projectname."/".$Survey."/FL_".$Image.".jpg";
$RF_url = "http://projects.transmap.com/".$Projectname."/".$Survey."/RF_".$Image.".jpg";
$BR_url = "http://projects.transmap.com/".$Projectname."/".$Survey."/BR_".$Image.".jpg";

} 

if ($Project == "Schertz") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/".$Projectname."/".$Survey."/fl".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/".$Projectname."/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/".$Projectname."/".$Survey."/br".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "ICA") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media3/ICA/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media3/ICA/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
}


if ($Project == "Manatee") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media3/Manatee/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media3/Manatee/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media3/Manatee/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "redwoodcity") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media10/Redwood/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media10/Redwood/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media10/Redwood/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "Irvine") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://media.transmap.com/images/Irvine/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://media.transmap.com/images/Irvine/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://media.transmap.com/images/Irvine/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}


if ($Project == "HuberHeights") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media10/Huber_Heights/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media10/Huber_Heights/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media10/Huber_Heights/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}


if ($Project == "PutnamCounty") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media10/PutnamCounty/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media10/PutnamCounty/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media10/PutnamCounty/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "Watertown") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media10/Watertown/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media10/Watertown/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media10/Watertown/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "JIMI") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media10/JIMI/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media10/JIMI/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media10/JIMI/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "ErieCounty") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media10/Erie/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media10/Erie/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media10/Erie/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "Greenburgh") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media10/Greenburgh/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media10/Greenburgh/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media10/Greenburgh/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}
if ($Project == "Buffalo") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media10/Buffalo/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media10/Buffalo/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media10/Buffalo/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "Casper") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media11/Casper/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media11/Casper/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media11/Casper/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "Wilmington") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media11/Wilmington/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media11/Wilmington/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media11/Wilmington/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}



if ($Project == "Lakeland") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media11/Lakeland/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media11/Lakeland/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media11/Lakeland/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}



if ($Project == "SIPOA") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://media.transmap.com/images/SIPOA/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://media.transmap.com/images/SIPOA/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://media.transmap.com/images/SIPOA/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "Cary") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media11/Cary/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media11/Cary/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media11/Cary/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}


if ($Project == "Milton") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://media.transmap.com/images/Milton/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://media.transmap.com/images/Milton/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://media.transmap.com/images/Milton/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "Escambia") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media11/Escambia/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media11/Escambia/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media11/Escambia/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}

if ($Project == "Kettering") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media11/Kettering/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media11/Kettering/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media11/Kettering/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}


if ($Project == "Hanford") {
	$filename = "".$Image.".jpg";
	$FL_url = "http://projects.transmap.com/Media11/Hanford/".$Survey."/FL".$Survey."/FL_".$Image.".jpg";
	$RF_url = "http://projects.transmap.com/Media11/Hanford/".$Survey."/RF".$Survey."/RF_".$Image.".jpg";
	$BR_url = "http://projects.transmap.com/Media11/Hanford/".$Survey."/BR".$Survey."/BR_".$Image.".jpg";
}


if ($Project != "Sarasota" or $Project != "Schertz" or $Project != "ICA") {

$filename = "".$Image.".jpg";
$url = "http://projects.transmap.com/".$Projectname."/".$SurveynameFL."/FL_".$Image.".jpg";
$url2 = "http://transmap_cdn.s3.amazonaws.com/CAP_Eng/".$SurveynameFL."/RF_".$Image.".jpg";
}


$Image2 = $Image + 1;
$Image3 = $Image - 1;
$Image4 = $Image + 5;
$Image5 = $Image - 5;








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
   

      <!-- primary links -->
    <!--   <div id="menu" class="span-32">
<p style="color:white; font-size:x-small;align:right;">Transmap | Blog | Calendar | Projects </p>

                              </div>
                              
                              -->
                              <!-- end primary links -->

      <!-- begin header -->

      <div id="header" class="span-24" style="margin-top:24px;">
		 <!-- site logo -->
     	 <a href="http://transmap.com/" title="Home"><img class="logo" src="../blueprint/themes/amadou/logo.png" alt="Home" border="0" /></a>
        <!-- end site logo -->
        <p style="align:right;"><a href="http://www.transmap.com/?page_id=2"><span style="color:white">Help</span></a> | <a href="http://www.transmap.com/?page_id=2"><span style="color:white">Contact</span></a></p>
		</div>
		
		<!-- end header -->



<div class="container">


<div class="span-5 last">
</div>
<div class="span-19 last">
<h1><?php echo $Project; ?> Road Image Viewer</h1>
<h3>Survey: <?php echo $Survey; ?> - Image: <?php echo $Image; ?></h3>
<br /> 
<!--<P><a href="index_lighten.php?<?php print $_SERVER["QUERY_STRING"]; ?>">Lighten images</a></p> -->
</div>

</div>



<br />
<div class="container" style="background:#F8F8F8;padding:6px;border-width:1px;border-style:dotted;border-color:black;">



<?php   
if ($Project == "ElPaso" OR $Project == "Alexandria" OR $Project == "Allegheny" OR $Project == "SeaTac" OR $Project == "Fresno" OR $Project == "VCDD")  {

?>
 <div class="span-12">
 <h3 class="room">Front Left</h3>
<?php 
 echo "<a href=\"http://projects.transmap.com/".$Projectname."/".$SurveynameFL."/FL_".$Image.".jpg\" border=\"0\" target=\"new\">
 <img src=http://projects.transmap.com/surveys/imgsize.php?percent=25&img=http://projects.transmap.com/".$Projectname."/".$SurveynameFL."/FL_".$Image.".jpg></a>";
?>
</div>

    
  
    
      <div class="span-12 last">
      
  <h3 class="room">Right Front</h3>    
     <?php
echo "<a href=\"http://projects.transmap.com/".$Projectname."/".$SurveynameRF."/RF_".$Image.".jpg\" border=\"0\" target=\"new\">
<img src=http://projects.transmap.com/surveys/imgsize.php?percent=25&img=http://projects.transmap.com/".$Projectname."/".$SurveynameRF."/RF_".$Image.".jpg></a>";

?>
      
      
      
      
      </div>



<div class="span-12">

<h3 class="room">Back Right</h3> 

 <?php
 echo "<a href=\"http://projects.transmap.com/".$Projectname."/".$SurveynameBR."/BR_".$Image.".jpg\" border=\"0\" target=\"new\">
<img src=http://projects.transmap.com/surveys/imgsize.php?percent=25&img=http://projects.transmap.com/".$Projectname."/".$SurveynameBR."/BR_".$Image.".jpg></a>";

  ?>
</div>




<?php

} if ($Project == "I595")  {


?>

 <div class="span-12">
 <h3 class="room">Front Left</h3>
<?php 
 echo "<a href=\"http://transmap_cdn.s3.amazonaws.com/CAP_Eng/Images/".$SurveynameFL."/FL_".$Image.".jpg\" border=\"0\" target=\"new\">
 <img src=http://projects.transmap.com/surveys/imgsize.php?percent=25&img=http://transmap_cdn.s3.amazonaws.com/CAP_Eng/Images/".$Survey."/FL_".$Image.".jpg></a>";
?>
</div>

    
  
    
      <div class="span-12 last">
      
  <h3 class="room">Right Front</h3>    
<?php 
 echo "<a href=\"http://transmap_cdn.s3.amazonaws.com/CAP_Eng/Images/".$SurveynameFL."/RF_".$Image.".jpg\" border=\"0\" target=\"new\">
 <img src=http://projects.transmap.com/surveys/imgsize.php?percent=25&img=http://transmap_cdn.s3.amazonaws.com/CAP_Eng/Images/".$Survey."/RF_".$Image.".jpg></a>";
?>    
      
      
      
      </div>



<div class="span-12">

<h3 class="room">Back Right</h3> 

 <?php 
 echo "<a href=\"http://transmap_cdn.s3.amazonaws.com/CAP_Eng/Images/".$SurveynameFL."/BR_".$Image.".jpg\" border=\"0\" target=\"new\">
 <img src=http://projects.transmap.com/surveys/imgsize.php?percent=25&img=http://transmap_cdn.s3.amazonaws.com/CAP_Eng/Images/".$Survey."/BR_".$Image.".jpg></a>";
?>
</div>



<?php

}

if ($Project == "Sarasota" OR $Project == "Schertz" OR $Project == "Manatee" OR $Project == "redwoodcity" OR $Project == "Irvine" OR $Project == "HuberHeights" OR $Project == "PutnamCounty" OR $Project == "Watertown" OR $Project == "JIMI" OR $Project == "ErieCounty" OR $Project == "Greenburgh" OR $Project == "Buffalo" OR $Project == "Casper" OR $Project == "Wilmington" OR $Project == "Lakeland" OR $Project == "SIPOA" OR $Project == "Cary" OR $Project == "Milton" OR $Project == "Escambia" OR $Project == "Kettering" OR $Project == "Hanford")  {

?>





 <div class="span-12">
 <h3 class="room">Front Left</h3>
<a href="<?php print $FL_url; ?>" border="0" target="new">
 <img src="http://projects.transmap.com/surveys/imgsize.php?percent=25&img=<?php print $FL_url; ?>"></a>
</div>
    
 <div class="span-12 last">
  <h3 class="room">Right Front</h3>    
<a href="<?php print $RF_url; ?>" border="0" target="new">
 <img src="http://projects.transmap.com/surveys/imgsize.php?percent=25&img=<?php print $RF_url; ?>"></a>
 </div>



<?php


if ($Project == "Casper" OR $Project == "Wilmington" OR $Project == "Lakeland" OR $Project == "SIPOA" OR $Project == "Cary" OR $Project == "Milton" OR $Project == "Escambia" OR $Project == "Kettering" OR $Project == "Hanford") {

?>
<div class="span-12">
<h3 class="room">Back Right</h3> 
<a href="<?php print $BR_url; ?>" border="0" target="new">
 <img src="http://projects.transmap.com/surveys/imgsize.php?percent=18&img=<?php print $BR_url; ?>"></a>
</div>



<?php

} else {
?>


<div class="span-12">
<h3 class="room">Back Right</h3> 
<a href="<?php print $BR_url; ?>" border="0" target="new">
 <img src="http://projects.transmap.com/surveys/imgsize.php?percent=25&img=<?php print $BR_url; ?>"></a>
</div>



<?php 
}
?>






<?php

}

if ($Project == "ICA")  {

?>





 <div class="span-12">
 <h3 class="room">Front Left</h3>
<a href="<?php print $FL_url; ?>" border="0" target="new">
 <img src="http://projects.transmap.com/surveys/imgsize.php?percent=25&img=<?php print $FL_url; ?>"></a>
</div>
    
 <div class="span-12 last">
  <h3 class="room">Right Front</h3>    
<a href="<?php print $RF_url; ?>" border="0" target="new">
 <img src="http://projects.transmap.com/surveys/imgsize.php?percent=25&img=<?php print $RF_url; ?>"></a>
 </div>

<div class="span-12">&nbsp;</div>
<?php
}
?>

<div class="span-6 last">
<br />
<h3>Images x 1</h3>
<?php


if ($Project == "ElPaso" OR $Project == "Alexandria" OR $Project == "Allegheny" OR $Project == "SeaTac" OR $Project == "Fresno" OR $Project == "VCDD" OR $Project == "I595" OR $Project == "Sarasota"  OR $Project == "Schertz" OR $Project == "ICA" OR $Project == "Manatee" OR $Project == "redwoodcity" OR $Project == "Irvine" OR $Project == "HuberHeights" OR $Project == "PutnamCounty" OR $Project == "Watertown" OR $Project == "JIMI" OR $Project == "ErieCounty" OR $Project == "Greenburgh" OR $Project == "Buffalo" OR $Project == "Casper" OR $Project == "Wilmington" OR $Project == "Lakeland" OR $Project == "SIPOA" OR $Project == "Cary" OR $Project == "Milton" OR $Project == "Escambia" OR $Project == "Kettering" OR $Project == "Hanford"){
	
	if ($Image >= "00000" and $Image <= "00008")   {
	
echo "
<p class=\"span-2\">

<a href=\"?Image=0000$Image2&Project=$Project&Survey=$Survey\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=0000$Image3&Project=$Project&Survey=$Survey\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";


} 




elseif ($Image == "00009" and $Image < "00010") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=000$Image2&Project=$Project&Survey=$Survey\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=0000$Image3&Project=$Project&Survey=$Survey\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}









elseif ($Image == "00010") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=000$Image2&Project=$Project&Survey=$Survey\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=0000$Image3&Project=$Project&Survey=$Survey\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}




elseif ($Image > "00010" and $Image <= "00098") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=000$Image2&Project=$Project&Survey=$Survey\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=000$Image3&Project=$Project&Survey=$Survey\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}



elseif ($Image == "00100" OR $Image == "00099") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=00$Image2&Project=$Project&Survey=$Survey\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=000$Image3&Project=$Project&Survey=$Survey\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}





elseif ($Image >= "00099" and $Image <= "00998") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=00$Image2&Project=$Project&Survey=$Survey\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=00$Image3&Project=$Project&Survey=$Survey\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}


elseif ($Image == "01000") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=0$Image2&Project=$Project&Survey=$Survey\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=00$Image3&Project=$Project&Survey=$Survey\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}



elseif ($Image >= "00999" and $Image <= "09998") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=0$Image2&Project=$Project&Survey=$Survey\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=0$Image3&Project=$Project&Survey=$Survey\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}
?>
</div>



<div class="span-6 last">
<br />
<h3>Images x 5</h3>
<?php


if ($Project == "ElPaso" OR $Project == "Alexandria" OR $Project == "Allegheny" OR $Project == "SeaTac" OR $Project == "Fresno" OR $Project == "VCDD" OR $Project == "I595" OR $Project == "Sarasota" OR $Project == "Schertz" OR $Project == "ICA" OR $Project == "Manatee" OR $Project == "redwoodcity" OR $Project == "Irvine" OR $Project == "HuberHeights" OR $Project == "PutnamCounty" OR $Project == "Watertown" OR $Project == "JIMI" OR $Project == "ErieCounty" OR $Project == "Greenburgh" OR $Project == "Buffalo" OR $Project == "Casper" OR $Project == "Wilmington" OR $Project == "Lakeland" OR $Project == "SIPOA" OR $Project == "Cary" OR $Project == "Milton" OR $Project == "Escambia" OR $Project == "Kettering" OR $Project == "Hanford"){
	
	if ($Image >= "00000" and $Image < "00006")   {
	
echo "
<p class=\"span-2\">

<a href=\"?Image=0000$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=0000$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";


} 




elseif ($Image >= "00006" and $Image < "00010") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=000$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=0000$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}









elseif ($Image >= "00010" AND $Image <= "00014" ) {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=000$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=0000$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}




elseif ($Image >= "00015" and $Image <= "00094") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=000$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=000$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}


elseif ($Image == "00095" or $Image < "00100") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=00$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=000$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}



elseif ($Image >= "00100" AND $Image <= "00104") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=00$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=000$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}





elseif ($Image >= "00099" and $Image <= "00994") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=00$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=00$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}

elseif ($Image == "00995" or $Image < "01000") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=0$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=00$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}


elseif ($Image == "01000") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=0$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=00$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}



elseif ($Image >= "00999" and $Image <= "09998") {
	

echo "
<p class=\"span-2\">

<a href=\"?Image=0$Image4&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/f.gif\" alt=\"forward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\"/></a> 

</p>

<p class=\"span-2\">
<a href=\"?Image=0$Image5&Project=$Project&Survey=$Survey&multiple=10\"><img src=\"images/b.gif\" alt=\"backward\" width=\"120\" height=\"44\" border=\"0\" align=\"left\" /></a> 
</p>


";

}



}




?>
</div>


<?php

}




?>









  
    



</div>

    </div>
  </body>
</html>