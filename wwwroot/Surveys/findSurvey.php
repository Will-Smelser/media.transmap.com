<?php 
include '../class/Utilities.php'; 

?>
<!DOCTYPE html>
<html>
  <head>
  
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/css.html'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/js.html'; ?> 
  
  <script src="/js/cookie.js" ></script>
  
  <script>

  $(document).ready(function(){

  $( "#button" ).button();

var $project = $('#project');
var $survey  = $('#survey');

$("select").uiselect();

var showDialog = function(inner, title){
	title= (typeof title === "undefined") ? "Message" : title;
	$("#dialog").html(inner).dialog({
		resizable:false,
		title: title,
		buttons: [
			{
				text: "Ok",
				click: function() {$(this).dialog("close");}
			}]
	});
}; 

var checkForm = function(){
	$('select').each(function($el){
		if($(this).val() == 0){
			showDialog('One or more entries is invalid.  Please correct and try again.','Check Form');
			return;
		}
	});

	var project = $project.val().split(":")[0];
	var survey  = $survey.val();

	var url = '/Surveys/index2.php?Survey=';
	url += survey + '&' + 'Project=' + project;

	document.location.href = url;
}
	  
//bind to the project list change
$project.change(function(){
	//reset the surveys
	$survey.empty();
	$survey.append(
		$(document.createElement('option')).attr('value',0).html('-- Choose Survey --')
	);
	
	//parse the select list value
	var parts = $project.val().split(':');

	//check that this has a serviceUrl
	if(typeof parts[2] === 'undefined' || parts[2] === ''){
		showDialog("This service does not have a service associated with it.");
		$survey.uiselect("refresh");
		return;
	}
	

	//lookup the surveys
	var url = "<?php echo Utils::getServiceUrl(); ?>?action=getSurveys";
	url += "&project="+parts[1].replace(/\/images?\/?/i,'');

	var service = encodeURIComponent("http://")+parts[2]+encodeURIComponent('/0/query');
	url += "&serviceUrl="+service;

	$("#dialog").html("Loading...").dialog({
		resizable:false,
		title: "Contacting ArcGIS Service",
		modal: true,
		buttons: []
	});
	
	$.getJSON(url,
		function(result){
			$("#dialog").dialog("close");
			
			if(!result.result){
				showDialog("No valid surveys were found.");
				$survey.uiselect("refresh");
				return;
			}
	
			for(var x in result.data){
				var $el = $(document.createElement('option')).attr('value',result.data[x]).html(result.data[x]);
				$survey.append($el);
			}

			$survey.uiselect("refresh");
		},
		function(result){
			$("#dialog").dialog("close");
			showDialog("Request failed.<br/>"+result.messages[0]);
		}
	);
});

//bind to the submit button
$('#button').click(checkForm);

});
  
  </script>
  
</head>
<body>

<div class="container" id="container">
		
	<?php include '../includes/html/header.html'; ?>

	
		
	<!-- Current Projects -->
	<div id="project-wrapper">
		<h4>Current Project(s): </h4>
		<p>
		<select name="project" id="project">
		<option value="0" selected>-- Choose Project --</option>
		<?php 
		foreach(Utils::getPropfileContents() as $info){
			$service = urlencode($info['service']);
			$temp = "{$info['name']}:{$info['path']}:{$service}";
			$temp = str_replace("'","\\'",$temp);
			echo "\t\t<option value='$temp'>{$info['name']}</option>\n";
		}
		?>
		</select>
		</p>
	</div>
	
	<!-- Valid Surveys (dynamically loaded) -->
	<div id="survey-wrapper">
		<h4>Survey(s): </h4>
		<p>
		<select name="survey" id="survey">
			<option value="0">-- Choose Survey --</option>
		</select>
		</p>
	</div>
	<hr/>
	<p>
		<input id="button" value="Load Project" type="button" />
	</p>
			
	</div>	
	
	<div id="dialog" title="Basic dialog"></div>
	
<?php include "../includes/html/footer.html"; ?>