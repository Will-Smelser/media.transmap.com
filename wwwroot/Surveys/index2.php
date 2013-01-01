<?php include '../includes/header_front.php'; ?>

	<div class="container">
		<div class="span-19 last">
			
		</div>
	</div>
	
	<div id="viewer-navigation">
	
		<ul style="display:inline-block;float:left">
			<li style="display:inline-block"><h3 style="display:inline-block;"><?php echo $project->getProjectName(); ?></h3></li>
			<li style="display:inline-block">
				<select id="survey-list">
				<?php
				//try and get a list of surveys
				$surveys = Utils::getValidSurveys($project->getProjectNameFileSystem(),$project->getProjectQueryUrl());
				if(count($surveys) > 0){
					listSurveys($surveys, $project->getSurvey());
				}else{ 
					listSurveys($project->getSurveys(),$project->getSurvey()); 
				}
				?>
				</select>
			</li>
		</ul>
		<ul id="viewer-controls" style="display:inline-block;float:left">
			<li> View 
			<select id="selectView">
				<option value='p-BR-<?php echo IMAGE_SIZE_BR; ?>'>Pavement</option>
				<option value='s-RF-<?php echo IMAGE_SIZE_RF; ?>'>Sign</option>
				<option value='f-FL-<?php echo IMAGE_SIZE_FL; ?>'>Forward</option>
			</select>
			</li>
			<li>
				<input id="image-counter" value="<?php echo $project->getImagePadded(); ?>" />
				<input type="button" value="Go" onclick="Viewer.canvasClick($('#image-counter').val())" />
			</li>
			<li><a href="#" id="forward" >Move Forward</a></li>
			<li><a href="#" id="backward" >Move Backward</a></li>
			<li><a href="<?php echo $project->getNextImageUrl(0); ?>">Default Viewer</a></li>
		</ul>
		<div style="clear:both"></div>
	</div>
	<div id="main-container" style="background:#F8F8F8;position:relative;">
	  <div style="margin:6px;position:relative;height:515px">
		<div id="map-wrapper" style="position:absolute;right:0px;bottom:0px;z-index:10;width:325px;height:210px;">
		  <div id="map-full" class="open"></div>
		  <div id="loading2" class="loading" >
			<div class="inner"><img src="/images/layout/loading.gif" /><i>Loading ...</i></div>
		  </div>
		  <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props="design:'headline'" style="width:100%;height:100%; margin: 0; overflow:hidden;">
			<div id="map" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region:'center'" style="width:100%;height:100%"></div>
		  </div>
		</div>
		
		<div id="data-details-wrapper" style="position:absolute;right:0px;width:325px;">
			<h4>Current Image Data</h4>
			<div style="width:325px;height:275px;">
				<div id="data-details">No Data</div>
			</div>
		</div>
		<div class="image-container" id="image-container" style="z-index:1;">
			<img id="image-main" src="<?php echo $project->getImage($camera, 0, $imageSz); ?>" />
			<img id="image-next" src="<?php echo $project->getImage($camera, 1, $imageSz); ?>" style="display:none;" />
			<div id="loading" class="loading">
				<div class="inner"><img src="/images/layout/loading.gif" /><i>Loading ...</i></div>
			</div>
		</div>
	  </div>
	</div>
			
	<div id="image-loading" style="display:none"></div>
	
	<script>

		var localServiceUrl = "<?php echo Utils::getServiceUrl(); ?>";
		dojo.addOnLoad(function(){
		//window.onload = function(){
		//$(document).ready(function(){ //jquery load doesnt work
			var queryBaseUrl = '<?php echo $project->getProjectQueryUrl(); ?>';
			Viewer.load(<?php echo "'{$_SERVER['PHP_SELF']}',".$imageSz.",".intval($image).", '$project1','{$project->getProjectPath()}','$survey', '$camera','$type', first, last"; ?>,queryBaseUrl);
		});
	
	</script>
	
	
<?php include "../includes/footer.php"; ?>