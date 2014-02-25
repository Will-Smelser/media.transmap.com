<?php

require_once '../class/Project.php';
require_once '../class/Session.php';

$session = &Session::getInstance();

//definitions
define('VIEW_DEFAULT','front');

//get everything to lowercase
foreach($_GET as $key=>$val) $_GET[strtolower($key)] = $val;

//get display vars
$version = (empty($_GET['view']))    ? VIEW_DEFAULT     : $_GET['view'];
$project = (isset($_GET['project'])) ? $_GET['project'] : null;
$survey  = (isset($_GET['survey']))  ? $_GET['survey']  : null;
$image   = (isset($_GET['image']))   ? $_GET['image']   : null;

try{
	
	$project = new Project($project, $survey, $image, $session, null, true);
	
}catch(Exception $e){
	echo $e->getMessage();
	exit;
}

//include '../includes/header.php';

?><!DOCTYPE html>
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


        <script src="http://code.jquery.com/jquery-1.8.1.min.js" ></script>

        <script>
            function resetCont(){
                $('#container').width($(window).width());
               while($(document).height() > $(window).height()){
                   var width = $('#container').width();
                   $('#container').width(width-10);
               }
            }
            $(document).ready(function(){
                resetCont();
                $(window).resize(function(){
                    resetCont();
                });
            });

        </script>


        <style>

            #container{
                width:100%;
            }
            .container{
                width:100%;
            }
            .left{
                width:50%;
                float:left;
            }
            .right{
                width:50%;
                float:right;
            }
            .left img, .right img{
                width:100%;
                margin:0px;
                padding:0px;
            }
            .left-nav{
                width:25%;
                float:left;
            }
            .right-nav{
                width:25%;
                float:right;
            }
            .middle{
                width:50%;
                float:left;
            }
            .middle img{
                width:100%;
            }
        </style>
    </head>

<body style="background: none;margin:0px;">
<div id="container">

	<div class="container" style="color:#FFFFFF;background-color:#333;">
			<h3 style="color:#FFFFFF;">Survey: <?php echo $project->getSurvey(); ?>
            , Image: <?php echo $project->getImagePadded(); ?>
            , <?php echo $project->getProjectName(); ?>
            </h3>
	</div>
	
	<div class="container" style="background:#F8F8F8;padding:0px;border-width:0px;">
	
		<div class="left">
			<a href="<?php echo $project->getImageLinkFl(); ?>" border="0" target="new">
				<img  src="<?php echo $project->getImageFl(); ?>" />
			</a>
		</div>
	    
		<div class="right">
			<a href="<?php echo $project->getImageLinkFr(); ?>" border="0" target="new">
				<img src="<?php echo $project->getImageFr() ?>" />
			</a>
	     </div>

        <div style="clear:both;"></div>

        <div class="left-nav">
            <div style="margin-left:auto;margin-right: auto;width:100px">
            <h3>Images x 1</h3>
            <p class="span-2 last">
                <?php
                $img = $project->getNextImageUrl(1);
                if($project->hasProjectImages($img)){
                    ?>
                    <a href="<?php echo $img ?>"><img src="images/f.gif" alt="forward" width="120" height="44" border="0" align="left"/></a>
                <?php } ?>
            </p>

            <p class="span-2">
                <?php
                $img = $project->getNextImageUrl(-1);

                if($project->hasProjectImages($img)){
                    ?>
                    <a href="<?php echo $project->getNextImageUrl(-1) ?>"><img src="images/b.gif" alt="backward" width="120" height="44" border="0" align="left"/></a>
                <?php } ?>
            </p>

            </div>
        </div>
	
		<div class="middle">
			<a href="<?php echo $project->getImageLinkBr(); ?>" border="0" target="new">
				<img src="<?php echo $project->getImageBr() ?>" />
			</a>
		</div>

	
		<div class="right-nav">
            <div style="margin-left:auto;margin-right: auto;width:100px">
			<h3>Images x 5</h3>
			<p class="span-2">
				<?php 
				$img = $project->getNextImageUrl(5); 
				
				if($project->hasProjectImages($img)){
				?>
					<a href="<?php echo $img; ?>">
						<img src="images/f.gif" alt="backward" width="120" height="44" border="0" align="left"/>
					</a>
				<?php } ?>
			</p>
			<p class="span-2">
				<?php 
				$img = $project->getNextImageUrl(-5);
				
				if($project->hasProjectImages($img)){
				?>
					<a href="<?php echo $img ?>">
						<img src="images/b.gif" alt="backward" width="120" height="44" border="0" align="left"/>
					</a>
				<?php } ?>
			</p>
            </div>
		</div>
	</div>
</div>
<?php include "../includes/footer.php"; ?>