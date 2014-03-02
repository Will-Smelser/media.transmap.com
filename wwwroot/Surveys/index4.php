<?php

require_once '../class/Project.php';

//definitions
define('VIEW_DEFAULT','front');

//get everything to lowercase
foreach($_GET as $key=>$val) $_GET[strtolower($key)] = $val;

//get display vars
$version = (empty($_GET['view']))    ? VIEW_DEFAULT     : $_GET['view'];
$project = (isset($_GET['project'])) ? $_GET['project'] : null;
$survey  = (isset($_GET['survey']))  ? $_GET['survey']  : null;
$image   = (isset($_GET['image']))   ? $_GET['image']   : null;
$host = null;
try{
	
	$project = new Project($project, $survey, $image, $host);
	
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
        <script src="/js/preload.js"></script>

        <script>
            function resetContainer(){
                $('#container').width($(window).width());
                while($(document).height() > $(window).height()){
                   var width = $('#container').width();
                   $('#container').width(width-10);
                }
            }
            $(document).ready(function(){
                resetContainer();
                $(window).resize(resetContainer);
                $('#img-br').load(resetContainer);
                $('#img-fl').load(resetContainer);
                $('#img-fr').load(resetContainer);

                //not sure why, but if images fail, then
                //container does not resize correctly
                setTimeout(resetContainer,1500);

                //start loading images so they will already be in the cache
                //we will load 2 in each direction
                var loader = new Preload();

                <?php for($i=1; $i <= 2; $i++) { ?>

                loader.preload("<?php echo $project->getImageFr($i); ?>");
                loader.preload("<?php echo $project->getImageFl($i); ?>");
                loader.preload("<?php echo $project->getImageBr($i); ?>");

                loader.preload("<?php echo $project->getImageFr($i*5); ?>");
                loader.preload("<?php echo $project->getImageFl($i*5); ?>");
                loader.preload("<?php echo $project->getImageBr($i*5); ?>");

                loader.preload("<?php echo $project->getImageFr(-$i); ?>");
                loader.preload("<?php echo $project->getImageFl(-$i); ?>");
                loader.preload("<?php echo $project->getImageBr(-$i); ?>");

                loader.preload("<?php echo $project->getImageFr(-$i*5); ?>");
                loader.preload("<?php echo $project->getImageFl(-$i*5); ?>");
                loader.preload("<?php echo $project->getImageBr(-$i*5); ?>");

                <?php } ?>

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
                width:20%;
                float:left;
            }
            .right-nav{
                width:20%;
                float:right;
            }
            .left-nav img, .right-nav img{
                width:95px;
                padding:10px 0px;
                margin:0px;
            }
            .middle{
                width:60%;
                float:left;
            }
            .middle img{
                width:100%;
            }
            #logo{
                position:fixed;
                bottom:5px;
                right:5px;

                width:130px;
                display:block;
                border:solid #EFEFEF 1px;
            }
            #logo img{width:120px}


            .drop-shadow {
                position:relative;
                float:left;

                padding:3px;

                background:#fff;
            }

            .drop-shadow:before,
            .drop-shadow:after {
                content:"";
                position:absolute;
                z-index:-2;
            }


            .lifted {
                -moz-border-radius:4px;
                border-radius:4px;
            }

            .lifted:before,
            .lifted:after {
                bottom:15px;
                left:10px;
                width:50%;
                height:20%;
                max-width:300px;
                max-height:100px;
                -webkit-box-shadow:0 15px 10px rgba(0, 0, 0, 0.7);
                -moz-box-shadow:0 15px 10px rgba(0, 0, 0, 0.7);
                box-shadow:0 15px 10px rgba(0, 0, 0, 0.7);
                -webkit-transform:rotate(-3deg);
                -moz-transform:rotate(-3deg);
                -ms-transform:rotate(-3deg);
                -o-transform:rotate(-3deg);
                transform:rotate(-3deg);
            }

            .lifted:after {
                right:10px;
                left:auto;
                -webkit-transform:rotate(3deg);
                -moz-transform:rotate(3deg);
                -ms-transform:rotate(3deg);
                -o-transform:rotate(3deg);
                transform:rotate(3deg);
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
				<img id="img-fl"  src="<?php echo $project->getImageFl(); ?>" />
			</a>
		</div>
	    
		<div class="right">
			<a href="<?php echo $project->getImageLinkFr(); ?>" border="0" target="new">
				<img id="img-fr" src="<?php echo $project->getImageFr() ?>" />
			</a>
	     </div>

        <div style="clear:both;"></div>

        <div class="left-nav">
            <div style="margin-left:auto;margin-right: auto;width:100px">
            <h3>Images x 1</h3>
            <p class="span-2 last">
                <?php
                $img = $project->getNextImageUrl(1);
                if($project->hasProjectImages($image+1)){
                    ?>
                    <a href="<?php echo $img ?>"><img src="images/f.gif" alt="forward" border="0" align="left"/></a>
                <?php } ?>
            </p>

            <p class="span-2">
                <?php
                $img = $project->getNextImageUrl(-1);
                if($project->hasProjectImages($image-1)){
                    ?>
                    <a href="<?php echo $img ?>"><img src="images/b.gif" alt="backward" border="0" align="left"/></a>
                <?php } ?>
            </p>

            </div>
        </div>
	
		<div class="middle">
			<a href="<?php echo $project->getImageLinkBr(); ?>" border="0" target="new">
				<img id="img-br" src="<?php echo $project->getImageBr() ?>" />
			</a>
		</div>

	
		<div class="right-nav">
            <div style="margin-left:auto;margin-right: auto;width:100px">
			<h3>Images x 5</h3>
			<p class="span-2">
				<?php
                $img = $project->getNextImageUrl(5);
				if($project->hasProjectImages($image+5)){
				?>
					<a href="<?php echo $img; ?>">
						<img src="images/f.gif" alt="backward" border="0" align="left"/>
					</a>
				<?php } ?>
			</p>
			<p class="span-2">
				<?php
                $img = $project->getNextImageUrl(-5);
				if($project->hasProjectImages($image-5)){
				?>
					<a href="<?php echo $img ?>">
						<img src="images/b.gif" alt="backward" border="0" align="left"/>
					</a>
				<?php } ?>
			</p>
            </div>
		</div>
	</div>
</div>
<div id="logo">
    <div class="drop-shadow lifted">
        <img src="/images/logo.jpg" />
    </div>
</div>
<?php include "../includes/footer.php"; ?>