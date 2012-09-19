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
	
?>

<div class="container">

<a href="login.php?action=logout" >Logout</a>

<hr/>

<h3>Add Project</h3>
<form action="admin.php" method="POST">
	<label for="project">
		<span style="display:inline-block;width:200px;">Project:</span>
		<input id="project" name="project" type="text"/>
	</label><br/>
	<label for="pfolder">
		<span  style="display:inline-block;width:200px;">Image Location:</span>
		<select name="pfolder" id="pfolder">
		<?php makeOptions($base); ?>
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