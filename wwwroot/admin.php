<?php

require_once 'class/Login.php';
require_once 'includes/admin_funcs.php';

Login::protect();

//see includes/admin_funcs.php
handleActions();

$msg = '';

if(Login::hasMessage()){
	$msg = '<div class="message">'.Login::getMessage().'</div>';
	Login::clearMessage();
}

//get a list of services
$ch = curl_init("http://services.arcgis.com/Gyd9F6MUsQ0SKcSf/ArcGIS/rest/services?f=json");
$options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HTTPHEADER => array('Content-type: application/json')
);

// Setting curl options
curl_setopt_array( $ch, $options);
$output = curl_exec($ch); 
curl_close($ch);

$json = array();
try{
	$json = json_decode($output,true);
}catch(Exception $e){
    $msg = '<div class="message">Failed to load arcgis services list.<br/>ERROR: '.$e->getMessage().'</div>';
}
?>
<!DOCTYPE html>
<html>
  <head>
  
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/css.html'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/js.html'; ?> 
  
  <style>
  ul.ui-autocomplete{
  	overflow-x:hidden;
  	overflow-y:auto;
  	max-height:200px;
  }
  .message{
      font-weight:bold;
      font-size:24px;
      margin:3px;
      padding:10px;
      background-color:#EFEFEF;
      border:inset #CCC 2px;
  }
  </style>
  
  <script src="/js/cookie.js" ></script>
  
  <script>
	$(document).ready(function(){

		$('input:button').button();
		$('input:submit').button();
		$('select').uiselect();
	});
  </script>
  
  </head>
  <body>
<div class="container" id="container">

<?php include 'includes/html/header.html'; ?>
  


<a href="login.php?action=logout" >Logout</a>

<hr/>

<?php echo $msg; ?>

<h3>Add Project</h3>
<form action="admin.php" method="POST">
	<label for="project">
		<span style="display:inline-block;width:200px;">Project:</span><br/>
		<input id="project" name="project" type="text"/>
			<i style="font-weight:normal">(No ":" please)</i>
	</label><br/>
	
	<label for="pfolder">
		<span  style="display:inline-block;width:200px;">Image Folder Name:</span><br/>
        <input type="text" name="pfolder" id="pfolder" />
	</label><br/>
	
	<label for="service">
		<span style="display:inline-block;width:200px;">Service:</span>
		<select name="service" id="service">
		<?php  
			foreach($json['services'] as $val){
				$url = preg_replace('/https?\:\/\//i', '', $val['url']);
				echo "<option value='$url'>{$val['name']}</option>";
			}
		?>
		</select>
	</label><br/>

    <label for="imageServer">
        <span style="display:inline-block;width:200px;">Image Server:</span>
        <select name="imageServer" id="imageServer">
            <option value="http://s3.amazonaws.com/tmapmedia/" selected>Amazon S3</option>
            <option value="/images/">CeraNet</option>
        </select>
    </label><br/>
	
	<input type="hidden" value="addProject" name="action" />
	<input type="submit" value="Add Project" />
</form>
<hr/>
<h3>Existing Projects</h3>
<form action="admin.php" method="POST">
	<label for="project-delete">
	 	<span style="display:inline-block;width:200px;">Project:</span> 
		<select name="project" id="currentList"><?php listProjects(); ?></select>
	</label><br/>
	<input type="hidden" value="removeProject" name="action" />
	
	<input type="submit" value="Delete" />&nbsp;&nbsp;
	<input type="button" value="View Project" onclick="openProject()" />
</form>
</div>

<script>
function openProject(){
	//get the value of dropdown
	var project = $('#currentList').val();
	var url = '/surveys/index.php?Project='+project;

	window.open(url);
}
</script>

<?php 
include 'includes/html/footer.html';
?>