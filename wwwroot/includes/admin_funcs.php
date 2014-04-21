<?php

$base = $_SERVER['DOCUMENT_ROOT'].'/images';
$maxDepth = 1;
$fs = '/';
$dil = '|';

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
	global $file,$dil;
	$handle = @fopen($file, "r");

	$count = 0;

	while(($buffer = fgets($handle)) !== false){
		$parts = explode($dil,trim($buffer));
		echo "<option value='{$parts[0]}'>{$parts[0]}</option>\n";
		$count++;
	}
	fclose($handle);

	if($count == 0)
		echo "<option value=''>No Projects</option>\n";
}

function handleActions(){
	global $file,$dil;
	
	if(isset($_POST['action']) && $_POST['action'] == 'addProject'){
		$found = false;
	
		//validate the project name
		if(empty($_POST['project'])){
			Login::setMessage("Invalid project name.");
			return;
		}
		
		//check if this alread exists
		$handle = @fopen($file, "r");
	
		while(($buffer = fgets($handle)) !== false){
			$parts = explode($dil,trim($buffer));
	
			if($parts[0] === $_POST['project']){
				$found = true;
				Login::setMessage("The project already exists.");
				break;
			}
		}
	
		fclose($handle);

		if(!$found){

            $line = $_POST['project'].
                $dil.$_POST['pfolder'].
                $dil.$_POST['service'].
                $dil.$_POST['imageServer'];

            if(isset($_POST['addedServices']) && count($_POST['addedServices'] > 0))
                $line .= $dil.implode('@',$_POST['addedServices']);

            $line .= "\n";

			if(file_put_contents($file,$line,FILE_APPEND))
				Login::setMessage("Successfully added.");
			else
				Login::setMessage("Failed to add project.<br/><span style=\"font-size:10px\">info: $line</span>");
        }
	}elseif(isset($_POST['action']) && $_POST['action'] == 'removeProject'){
		//check if this alread exists
		$handle = @fopen($file, "r");
	
		$output = "";
	
		$found = false;
	
		while(($buffer = fgets($handle)) !== false){
			$parts = explode($dil,trim($buffer));
	
			if($parts[0] !== $_POST['project']){
				$output .= $buffer;
				$found = true;
			}
		}
	
		if($found && file_put_contents($file,$output))
			Login::setMessage("Removed \"{$_POST['project']}\" project.");
		else
			Login::setMessage("Failed to remove \"{$_POST['project']}\" project.");
	}
}
