<?php

include '../Lookup.php';

$request = strtolower($_SERVER['REQUEST_URI']);
$base    = strtolower(dirname($_SERVER['SCRIPT_NAME']));
$target  = str_replace($base,'',$request);

if(!isset($_GET['path']) || !isset($_GET['maxWidth'])){
    echo "File not found!";
    http_response_code(404);
    exit;
}

$parts   = explode('/',$_GET['path']);

if(count($parts) < 4){
    echo "File not found!";
    http_response_code(404);
    exit;
}

$projectName = array_shift($parts);
$projectId   = array_shift($parts);
$projectSub  = array_shift($parts);
$instance    = array_shift($parts);
$dimX    = $_GET['maxWidth'];

$loadfile = Lookup::findImage($projectName,$projectId,$projectSub,$instance);
$targetSave = 'store/'.md5($target).'.jpg';


//we have loaded this file in the past, just load it again
if(file_exists($targetSave)){
    header('Content-Type: image/jpeg');
    header('Content-Length: ' . filesize($targetSave));

    header("Cache-Control: private, max-age=31556926, pre-check=10800");
    header("Pragma: private");
    header("Expires: " . date('D, d M Y H:i:s',time()+360*60*60*24)); //a little less than 1 day into the future

    $modtime = @filemtime($targetSave);

    if ($modtime && isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
        &&
        (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $modtime)
    ) {
        // send the last mod time of the file back
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $modtime).' GMT', true, 304);
        exit;
    }

    header('Last-Modified: '.gmdate('D, d M Y H:i:s', $modtime).' GMT', true, 200);

    //send the file
    readfile($targetSave);
    exit;
}
if($loadfile){
    $image = imagecreatefromjpeg ($loadfile);
    $size  = getimagesize($loadfile);

    //the image start portrait, we will rotate to landscape
    $ratio = ($dimX * 1.0) / ($size[1] * 1.0);
    $dimY  = $ratio * $size[0];

    //echo "$dimX<br/>$dimY<br/>$ratio";exit;

    if($image){
        $dst = imagecreatetruecolor($dimX,$dimY);
        $image = imagerotate($image,90.0,0);

        //again, we will be rotating things, so x and y are flipped now
        imagecopyresampled($dst,$image,0,0,0,0,$dimX,$dimY,$size[1],$size[0]);
        imagedestroy($image);

        if(imagejpeg($dst,$targetSave)){
            header('Content-Type: image/jpeg');
            header('Content-Length: ' . filesize($targetSave));
            header("Cache-Control: private, max-age=31556926, pre-check=10800");
            header("Pragma: private");
            header("Expires: " . date('D, d M Y H:i:s',time()+360*60*60*24)); //a little less than 1 day into the future
            header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT', true, 200);
            imagejpeg($dst);
            imagedestroy($dst);
            exit;
        }
    }
}
header('Content-Type: image/jpeg');

$image = imagecreatefromjpeg('missing.jpg');
$size  = getimagesize('missing.jpg');

$ratio = ($dimX * 1.0) / ($size[0] * 1.0);
$dimY  = $ratio * $size[1];

$dst = imagecreatetruecolor($dimX,$dimY);
imagecopyresampled($dst,$image,0,0,0,0,$dimX,$dimY,$size[0],$size[1]);

imagedestroy($image);

$black = imagecolorallocate($dst, 0, 0, 0);
$font = 'arial.ttf';
imagettftext($dst, 50*$ratio*.9, 0, 400*$ratio, 500*$ratio, $black, $font, "Failed to load image: \n$target");

// Output the image
imagejpeg($dst);

// Free up memory
imagedestroy($image);
?>