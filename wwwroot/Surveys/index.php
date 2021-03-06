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

if(empty($survey)){
    header('Location: findSurvey.php');
    exit;
}

try{
	
	$project = new Project($project, $survey, $image, 'http://default-environment-6keidqm34v.elasticbeanstalk.com/');
	
}catch(Exception $e){
	echo $e->getMessage();
	exit;
}

include '../includes/header.php'; 

?>
    <script src="/js/preload.js"></script>
    <script>
        $(document).ready(function(){

            //start loading images so they will already be in the cache
            //we will load 2 in each direction
            var loader = new Preload();

            <?php for($i=1; $i <= 1; $i++) { ?>

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
				<img src="<?php echo $project->getImageFl(); ?>" />
			</a>
		</div>
	    
		<div class="span-12 last">
	    	<h3 class="room">Right Front</h3>    
			<a href="<?php echo $project->getImageLinkFr(); ?>" border="0" target="new">
				<img src="<?php echo $project->getImageFr() ?>" />
			</a>
	     </div>
	
		<div class="span-12">
			<h3 class="room">Back Right</h3> 
			<a href="<?php echo $project->getImageLinkBr(); ?>" border="0" target="new">
				<img src="<?php echo $project->getImageBr() ?>" />
			</a>
		</div>
	
	
		<div class="span-6">
			<br/>
			<h3>Images x 1</h3>
			<p class="span-2 last">
				<?php 
				$img = $project->getNextImageUrl(1);
				if($project->hasProjectImages($image+1)){
				?>
				<a href="<?php echo $img ?>"><img src="images/f.gif" alt="forward" width="120" height="44" border="0" align="left"/></a>
				<?php } ?> 
			</p>
		
			<p class="span-2">
				<?php 
				$img = $project->getNextImageUrl(-1);
				
				if($project->hasProjectImages($image-1)){
				?>
				<a href="<?php echo $img ?>"><img src="images/b.gif" alt="backward" width="120" height="44" border="0" align="left"/></a>
				<?php } ?>
			</p>
		</div>
	
		<div class="span-6 last">
			<br/>
			<h3>Images x 5</h3>
			<p class="span-2">
				<?php 
				$img = $project->getNextImageUrl(5); 
				
				if($project->hasProjectImages($image+5)){
				?>
					<a href="<?php echo $img; ?>">
						<img src="images/f.gif" alt="backward" width="120" height="44" border="0" align="left"/>
					</a>
				<?php } ?>
			</p>
			<p class="span-2">
				<?php 
				$img = $project->getNextImageUrl(-5);
				
				if($project->hasProjectImages($image-5)){
				?>
					<a href="<?php echo $img ?>">
						<img src="images/b.gif" alt="backward" width="120" height="44" border="0" align="left"/>
					</a>
				<?php } ?>
			</p>
		</div>
	</div>

<?php include "../includes/footer.php"; ?>