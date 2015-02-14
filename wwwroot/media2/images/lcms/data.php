<?php
/**
 * Created by PhpStorm.
 * User: Will2
 * Date: 2/14/15
 * Time: 3:28 PM
 */
error_reporting(E_ALL);

include 'Parser.php';

header('Content-Type: application/json');

if(!isset($_GET['path'])){
    echo '[]';
    http_response_code(404);
    exit;
}

$parts   = explode('/',$_GET['path']);

if(count($parts) < 4){
    echo '[]';
    http_response_code(404);
    exit;
}

$project = array_shift($parts);
$id      = array_shift($parts);
$sub     = array_shift($parts);
$instance= 'LcmsResult_'.array_shift($parts).'.xml';

//first find the projectName, correct case
if(!file_exists($project)){
    foreach(scandir('..') as $file){
        if(strtolower($file) === $project){
            $project = $file;
            break;
        }
    }
}


//find the correct filename, correct case
$base = "$project/$id/$sub";
if(!file_exists($base)){
    if(file_exists($base)){
        foreach(scandir($base) as $file){
            if(strtolower($file) === $instance){
                $instance = $file;
                break;
            }
        }
    }
}

$loadFile = $base.'/'.$instance;

if(!file_exists($loadFile)){
    echo "[]";
    http_response_code(404);
    exit;
}

$parser = new Parser($loadFile);