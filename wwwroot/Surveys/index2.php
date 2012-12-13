<?php include '../includes/header_front.php'; ?>

	<div class="container">
		<div class="span-19 last">
			
		</div>
	</div>
	
	<div id="viewer-navigation">
	
		<h3><?php echo $project->getProjectName(); ?></h3>
		<h4><?php echo $project->getSurvey(); ?></h4>
		<hr/>
		<ul style="display:block;float:left">
			<li>
				<input id="image-counter" value="<?php echo $project->getImagePadded(); ?>" />
				<input type="button" value="Go" onclick="Viewer.goToImage($('#image-counter').val(),null)" />
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
			<li><a href="#" id="forward" >Move Forward</a></li>
			<li><a href="#" id="backward" >Move Backward</a></li>
			<li><a href="<?php echo $project->getNextImageUrl(0); ?>">Default Viewer</a></li>
		</ul>
		<div style="clear:both"></div>
	</div>
	<div class="container" style="background:#F8F8F8;padding:6px;border-width:1px;border-style:dotted;border-color:black;">
		<div id="image-nav" style="width:500px;">
			
			  <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props="design:'headline'" style="width: 500px; height: 370px; margin: 0; overflow:hidden;">
				<div id="map" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region:'center'"></div>
			  </div>
				
			<ul>
				<li><h4>Current Image Data</h4>
					<div id="data-details" style="height:150px;overflow-x:hidden;overflow-y:auto;">No Data</div>
				</li>
			</ul>
		</div>
		
		<div class="image-container" id="image-container">
			<img id="image-main" src="<?php echo $project->getImage($camera, 0, $imageSz); ?>" />
			<img id="image-next" src="<?php echo $project->getImage($camera, 1, $imageSz); ?>" style="display:none;" />
			<div id="loading" >
				<div class="inner"><img src="/images/layout/loading.gif" /><i>Loading ...</i></div>
			</div>
		</div>
		
		</div>
	</div>
			
	<div id="image-loading" style="display:none"></div>

	<script src="/js/map.js" ></script>
	
<?php include "../includes/footer.php"; ?>