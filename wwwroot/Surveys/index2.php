<?php include '../includes/header_front.php'; ?>
<!DOCTYPE html>
<html>
  <head>
  
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    
  <?php include '../includes/html/css.html'; ?>
  <?php include '../includes/html/js.html'; ?> 

  <link rel="stylesheet" href="/css/map.css" type="text/css" media="screen" />	
  
  <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.2/js/dojo/dijit/themes/claro/claro.css"/>
  <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.2/js/esri/css/esri.css" />
  
  <script type="text/javascript" src="http://serverapi.arcgisonline.com/jsapi/arcgis/?v=3.2"></script> 
   
  <script src="/js/cookie.js" ></script>
  <script src="/js/viewer.js" ></script>
  <script src="/js/preload.js" ></script>
  <script src="/js/image.js" ></script>
  
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

    $(document).ready(function(){
    	$('select').uiselect().each(function(){
        	$(this).uiselect('autocompleteopen',
                function(){
            		$(this).autocomplete('widget').css('z-index',99);
            });
        });
    	$('input:button').button();
    	$('#forward,#backward').button();
    	$('#image-close').button({icons:{primary:'ui-icon-circle-close'}})
    		.click(function(){$('#image-show').dialog('close')});
    	
    	 $( "#backward" ).button({
    	      text: false,
    	      icons: {
    	        primary: "ui-icon-seek-prev"
    	      }
    	 });
    	 $( "#forward" ).button({
    	  text: false,
          icons: {
            primary: "ui-icon-seek-next"
          }
        });

    	 
    });
   
  </script>
  
  
  </head>
  <body class="caro">
	
	<div id="container" style="<?php if(isset($_COOKIE['page-width'])) echo "width:{$_COOKIE['page-width']}" ?>">	
	
	<?php include '../includes/html/header.html'; ?>
	
	<div id="viewer-navigation" style="z-index:20">
	
		<table id="map-top-nav">
			<tr>
			<td><h4><?php echo $project->getProjectName(); ?></h4></td>
			<td>
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
			</td>
			<td id="viewer-controls"> 
			<select id="selectView">
				<option value='p-BR-<?php echo IMAGE_SIZE_BR; ?>'>Pavement</option>
				<option value='s-RF-<?php echo IMAGE_SIZE_RF; ?>'>Sign</option>
				<option value='f-FL-<?php echo IMAGE_SIZE_FL; ?>'>Forward</option>
			</select>
			</td>
			<td> | </td>
			<td>
				<input class="ui-state-default ui-widget" style="background-color:#FFF;background-image:none;" id="image-counter" value="<?php echo $project->getImagePadded(); ?>" />
			</td>
			<td>
				<input type="button" value="Go" onclick="Viewer.canvasClick($('#image-counter').val())" />
			</td>
			<td> | </td>
			<td>
				<div id="toolbar" class="ui-corner-all">
				  <button id="backward">Move Backward</button>
				  <button id="forward">Move Forward</button>
				</div>
			</td>
			<td> | </td>
			<td><input type="button" onclick="document.location.href='<?php echo $project->getNextImageUrl(0); ?>'" value="Default Viewer" /></td>
		</table>
		<div style="clear:both"></div>
	</div>
	<div id="main-container" style="background:#F8F8F8;position:relative;">
	  <div style="margin:6px;position:relative;">
			<div id="map-wrapper" class="map-relative" style="z-index:10;height:210px;float:right;width:50%;">
			  <button id="map-full" ></button>
			  <div id="loading2" class="loading" >
				<div class="inner"><img src="/images/layout/loading.gif" /><i>Loading ...</i></div>
			  </div>
			  <div data-dojo-type="dijit.layout.BorderContainer" data-dojo-props="design:'headline'" style="width:100%;height:100%; margin: 0; overflow:hidden;">
				<div id="map" data-dojo-type="dijit.layout.ContentPane" data-dojo-props="region:'center'" style="width:100%;height:100%"></div>
			  </div>
		</div>
			
		<div class="image-container" id="image-container" style="z-index:1;width:49%;float:left;">
			<img id="image-main" src="<?php echo $project->getImage($camera, 0, $imageSz); ?>" style="width:100%" />
			<img id="image-next" src="<?php echo $project->getImage($camera, 1, $imageSz); ?>" style="position:absolute;display:none;top:0px;" />
			<div id="loading" class="loading">
				<div class="inner"><img src="/images/layout/loading.gif" /><i>Loading ...</i></div>
			</div>
		</div>
		<div style="clear:both;height:10px"></div>
		
		<div id="data-details-wrapper" style="">
			<h4>Current Image Data</h4>
			<div style="">
				<div id="data-details">No Data</div>
			</div>
		</div>
		
	  </div>
	  
	  <div style="position:absolute;top:49%;right:-5px;" class="ui-icon ui-icon-grip-solid-vertical"></div>
	</div>
			
	<div id="image-loading" style="display:none"></div>
	
	<div id="image-show" style="display:none;overflow:hidden;">
		<img id="image-show-img" src="" width="100%" />
		<div class="ui-widget-header ui-corner-all" style="padding:2px;position:absolute;bottom:10px;right:10px;">
			<button id="zoom-in">Zoom In</button>
			<button id="zoom-out">Zoom Out</button>
			<button id="image-close">Close</button>
		</div>
	</div>
	
	<div id="dialog" title="Alert" ></div>
	
	<script>
		var queryBaseUrl = '<?php echo $project->getProjectQueryUrl(); ?>';
		var mapData = <?php echo file_get_contents(preg_replace('/\/0\/query/i','',$project->getProjectQueryUrl()).'?f=json'); ?>;
		var localServiceUrl = "<?php echo Utils::getServiceUrl(); ?>";
		dojo.addOnLoad(function(){
			Viewer.load(<?php echo "'{$_SERVER['PHP_SELF']}',".$imageSz.",".intval($image).", '$project1','{$project->getProjectPath()}','$survey', '$camera','$type', first, last"; ?>,queryBaseUrl);
			$( "#container" ).resizable({ 
				handles: "e",
				stop: function(evt, ui){
					Viewer.refreshDims();
					map.resize(true);
					setTimeout(function(){map.centerAt(Viewer._currentPointGeometry)},500);
					$.cookie("page-width",ui.size.width+'px');
				} 
			});

			//bind up down arrows for map
			$(document).keydown(function(evt){
				if(evt.keyCode == 38){
					$('#forward').trigger('click');
				}else if(evt.keyCode == 40){
					$('#backward').trigger('click');
				}
			});
		});
	
	</script>
	
	
<?php include "../includes/html/footer.html"; ?>