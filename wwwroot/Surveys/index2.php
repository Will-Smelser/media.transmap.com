<?php include '../includes/header_front.php'; ?>
<!DOCTYPE html>
<html>
  <head>
  
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    
  <?php include '../includes/html/css.html'; ?>
  <?php include '../includes/html/js.html'; ?> 
  
  <script src="/js/cookie.js" ></script>

  <link rel="stylesheet" href="/includes/map.css" type="text/css" media="screen" />	
  
  <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.2/js/dojo/dijit/themes/claro/claro.css"/>
  <link rel="stylesheet" type="text/css" href="http://serverapi.arcgisonline.com/jsapi/arcgis/3.2/js/esri/css/esri.css" />

  <style>
      html, body {} 
      .esriScalebar{
      	padding: 20px 20px; 
      } 
      #map{ padding:0;}
      #map-top-nav{
      	display:inline-block;
      	float:left;
      	margin:0px;
      	padding:0px;
      	border-collapse:collapse;
      }
      #map-top-nav td{
      	padding:0px 10px 5px 0px;
      	margin:0px;
      }
      #toolbar {
      	padding: 0px;
  	  }
  	  #map-top-nav h4{
  	  	font-size:16px;
  	  	font-weight:bolder;
  	  }
  	  
  </style>
  
  <script type="text/javascript" src="http://serverapi.arcgisonline.com/jsapi/arcgis/?v=3.2"></script>  
  <script src="/js/cookie.js" ></script>
  <script src="/js/viewer.js" ></script>
  <script src="/js/preload.js" ></script>
  
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
        	$(this).('autocompleteopen',
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

    	 var $img = $('#image-show-img');
    	 
         //image previewer
         $("#image-show").dialog({
			modal:true,
			buttons:[],
			resizable:false,
			show:"fade",
			draggable:false,
			width:($(window).width()-40),
			height:($(window).height()-40),
			autoOpen:false,
			close:function(){$('body').css('overflow','auto');}
         });

         $('#image-container').click(function(){
        	 $('body').css('overflow','hidden');
        	 $("#image-show").dialog('open');
        	 $img.attr('width','100%').css({'top':'0px','left':'0px'}).attr('src',$(this).find('img').attr('src').replace(/percent=[\d]+/i,'percent=100')).draggable();
         });


         $("#zoom-in").button({icons:{primary:'ui-icon-circle-plus'}})
         	.click(function(){
             	var inc = 10;
				var width = parseInt($img.attr('width').replace('%',''))+inc;
				
				if(width === inc){
					width = 100 + inc;
				}
				$img.attr('width',width+'%');
          	});
     	 $('#zoom-out').button({icons:{primary:'ui-icon-circle-minus'}})
     	 	.click(function(){
     	 		var inc = -10;
				var width = parseInt($img.attr('width').replace('%',''))+inc;
				
				if(width === inc){
					width = 100 + inc;
				}
				$img.attr('width',width+'%');
     	 	});
    });
   
  </script>
  
  
  </head>
  <body class="caro">
	
	<div id="container">	
	
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
				<input id="image-counter" value="<?php echo $project->getImagePadded(); ?>" />
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
	  <div style="margin:6px;position:relative;height:515px">
		<div id="map-wrapper" style="position:absolute;right:0px;bottom:0px;z-index:10;width:325px;height:210px;">
		  <button id="map-full" ></button>
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
	
	<div id="image-show" style="display:none;overflow:hidden;">
		<img id="image-show-img" src="" width="100%" />
		<div class="ui-widget-header ui-corner-all" style="padding:2px;position:absolute;bottom:10px;right:10px;">
			<button id="zoom-in">Zoom In</button>
			<button id="zoom-out">Zoom Out</button>
			<button id="image-close">Close</button>
		</div>
	</div>
	
	<script>

		var localServiceUrl = "<?php echo Utils::getServiceUrl(); ?>";
		dojo.addOnLoad(function(){
		//window.onload = function(){
		//$(document).ready(function(){ //jquery load doesnt work
			var queryBaseUrl = '<?php echo $project->getProjectQueryUrl(); ?>';
			Viewer.load(<?php echo "'{$_SERVER['PHP_SELF']}',".$imageSz.",".intval($image).", '$project1','{$project->getProjectPath()}','$survey', '$camera','$type', first, last"; ?>,queryBaseUrl);
		});
	
	</script>
	
	
<?php include "../includes/html/footer.html"; ?>