<?php include '../includes/header_front.php'; ?>

	<div class="container">
		<div class="span-19 last">
			
		</div>
	</div>
	
	<div class="container" style="background:#F8F8F8;padding:6px;border-width:1px;border-style:dotted;border-color:black;">
		<div id="image-nav">
		<h3><?php echo $project->getProjectName(); ?></h1>
		<h4><?php echo $project->getSurvey(); ?></h3>
		<hr/>
		<ul style="display:block">
			<li>
				<input id="image-counter" value="<?php echo $project->getImagePadded(); ?>" />
				<input type="button" value="Go" onclick="Viewer.goToImage($('#image-counter').val(),null)" />
			</li>
		</ul>
		<hr/>
		
		<ul style="display:block">
			<li> View 
			<select id="selectView">
				<option value='p-BR-23'>Pavement</option>
				<option value='s-RF-39'>Sign</option>
				<option value='f-FL-39'>Forward</option>
			</select>
			</li>
		</ul>
		<hr/>
		<ul style="display:block">
			<li><a href="#" id="forward" >Move Forward</a></li>
			<li><a href="#" id="backward" >Move Backward</a></li>
			<li><a href="<?php echo $project->getNextImageUrl(0); ?>">Default Viewer</a></li>
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

<?php include "../includes/footer.php"; ?>