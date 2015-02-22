<?php
/**
 * Created by PhpStorm.
 * User: Will2
 * Date: 2/14/15
 * Time: 3:28 PM
 */
error_reporting(0);

include 'Parser.php';
include 'Lookup.php';

header('Content-Type: application/json');

if(!isset($_GET['path']) || !isset($_GET['ratio'])){
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
$instance= array_shift($parts);

$loadFile = Lookup::findXml($project,$id,$sub,$instance);
//$loadFile = $base.'/'.$instance;

if(!$loadFile){
    echo "[]";
    http_response_code(404);
    exit;
}

//need to get the image dimensions
$imgFile = Lookup::findImage($project,$id,$sub,$instance);
$size = getimagesize($imgFile);

if(!$size){
    http_response_code(404);
    echo "Failed to lookup valid image.";
    exit;
}

//var_dump($size);

//$parser = new Parser($loadFile,1024,2500,$_GET['ratio']*1.0);

$parser = new Parser($loadFile,$size[0],$size[1],$_GET['ratio']*1.0);