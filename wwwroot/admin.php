<?php
include 'includes/header.php';
require_once 'class/Login.php';
require_once 'includes/admin_funcs.php';

Login::protect();

//see includes/admin_funcs.php
handleActions();

if(Login::hasMessage()){
	echo '<div class="message">'.Login::getMessage().'</div>';
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
	//do nothing
}
?>

<div class="container">

<a href="login.php?action=logout" >Logout</a>

<hr/>

<h3>Add Project</h3>
<form action="admin.php" method="POST">
	<label for="project">
		<span style="display:inline-block;width:200px;">Project:</span>
		<input id="project" name="project" type="text"/>
			<i style="font-weight:normal">(No ":" please)</i>
	</label><br/>
	<label for="pfolder">
		<span  style="display:inline-block;width:200px;">Image Location:</span>
		<select name="pfolder" id="pfolder">
		<?php makeOptions($base); ?>
		</select>
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
include 'includes/footer.php';
?>