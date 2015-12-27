<?php
/**
 * Created by PhpStorm.
 * User: Will2
 * Date: 2/14/15
 * Time: 3:28 PM
 */
error_reporting(0);

include 'Parser.php';

header('Content-Type: application/json');


$date = $_GET['date'];
$sess = $_GET['session'];
$image= $_GET['image'];


if(!date || !sess || !$image){
    echo '[]';
    http_response_code(404);
    exit;
}


$loadFile = "https://storage.googleapis.com/tmap_lcms/$date/$sess/LcmsResult_$image.xml";

$parser = new Parser($loadFile,1040,2500,.32);