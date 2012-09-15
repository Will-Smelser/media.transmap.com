<?php
include 'includes/header.php';
include 'class/Login.php';

session_start();

Login::protect();

$base = $_SERVER['DOCUMENT_ROOT'].'/images';
$maxDepth = 1;
$fs = '/';

$file = $_SERVER['DOCUMENT_ROOT'].$fs.'Surveys'.$fs.'projects.properties';


function makeOptions($dir){
	global $base, $fs;
	
	foreach(scandir($dir) as $file){
		
		$mybase = str_replace($base, '',$dir);
		
		if($file != '.' && $file != '..'){
			if(is_dir($dir.$fs.$file)){
				echo "<option value='/images$fs$file'>/images$mybase$fs$file</option>\n";
			}
		}
	}
}

function listProjects(){
	global $file;
	$handle = @fopen($file, "r");
	
	$count = 0;
	
	while(($buffer = fgets($handle)) !== false){	
		$parts = explode(":",trim($buffer));
		echo "<option value='{$parts[0]}'>{$parts[0]}</option>\n";
		$count++;
	}
	fclose($handle);
	
	if($count == 0)
		echo "<option value=''>No Projects</option>\n"; 
}

if(isset($_POST['action']) && $_POST['action'] == 'addProject'){
	$found = false;
	
	//check if this alread exists
	$handle = @fopen($file, "r");
	
	while(($buffer = fgets($handle)) !== false){
		$parts = explode(":",trim($buffer));
		
		if($parts[0] === $_POST['project']){
			$found = true;
			Login::setMessage("The project already exists.");
			break;
		}
	}
	
	fclose($handle);
	
	if(!$found)
		if(file_put_contents($file,$_POST['project'].':'.$_POST['pfolder']."\n",FILE_APPEND))
			Login::setMessage("Successfully added.");
	
}elseif(isset($_POST['action']) && $_POST['action'] == 'removeProject'){
	//check if this alread exists
	$handle = @fopen($file, "r");
	
	$output = "";
	
	$found = false;
	
	while(($buffer = fgets($handle)) !== false){
		$parts = explode(":",trim($buffer));
	
		if($parts[0] !== $_POST['project']){
			$output .= $buffer;
			$found = true;
		}
	}
	
	if($found)
		Login::setMessage("Removed {$_POST['project']} project.");
	
	file_put_contents($file,$output);
}



if(Login::hasMessage()){
	echo '<div class="message">'.Login::getMessage().'</div>';
	Login::clearMessage();
}
	

//var_dump($_SESSION);
?>

<div class="container">
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
	<input type="button" value="View Project" />
</form>
</div>

<script>
function openProject(){
	//get the value of dropdown
	var project = $('#currentList').val();
	var url = '/surveys/index.php?Project='+project;
}
</script>

<?php 
include 'includes/footer.php';
?>