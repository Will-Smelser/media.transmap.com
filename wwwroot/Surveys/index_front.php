<?php include '../includes/header_front.php'; ?>

	<div class="container">
		<div class="span-19 last">
			<h1><?php echo $project->getProjectName(); ?> Road Image Viewer</h1>
			<h3>
				Survey: <?php echo $project->getSurvey(); ?> - Image: 
				<input id="image-counter" value="<?php echo $project->getImagePadded(); ?>" />
				<input type="button" value="Go" onclick="Viewer.goToImage($('#image-counter').val(),null)" />
			</h3>
		</div>
	</div>
	
	<div class="container" style="background:#F8F8F8;padding:6px;border-width:1px;border-style:dotted;border-color:black;">
		<div class="image-container" id="image-container">
			<div id="canvas" style="margin:0px auto;">
				<img id="image-main" src="<?php echo $project->getImage($camera, 0, $imageSz); ?>" style="position:absolute;" />
				<img id="image-next" src="<?php echo $project->getImage($camera, 1, $imageSz); ?>" style="position:absolute;display:none;" />
				<div id="loading" >
					<div class="inner"><img src="/images/layout/loading.gif" /><i>Loading ...</i></div>
				</div>
			</div>
		</div>
	</div>
	<div id="image-loading" style="display:none"></div>

<?php include "../includes/footer.php"; ?>