<?php

include '../Lookup.php';

$request = strtolower($_SERVER['REQUEST_URI']);
$base    = strtolower(dirname($_SERVER['SCRIPT_NAME']));
$target  = str_replace($base,'',$request);

if(!isset($_GET['path'])){
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

$loadfile = Lookup::findImage($projectName,$projectId,$projectSub,$instance);

if($loadfile){
    $image = imagecreatefromjpeg ($loadfile);

    if($image){
        $image = imagerotate($image,-90.0,0);

        header('Content-Type: image/jpeg');
        //header('Content-Length: ' . filesize($loadfile));
        header("Cache-Control: private, max-age=31556926, pre-check=10800");
        header("Pragma: private");
        header("Expires: " . date('D, d M Y H:i:s',time()+360*60*60*24)); //a little less than 1 day into the future
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT', true, 200);
        imagejpeg($image);
        imagedestroy($image);
        exit;
    }
}
header('Content-Type: image/jpeg');

$image = imagecreatefromjpeg('missing.jpg');
$size  = getimagesize('missing.jpg');

$ratio = 1.0;
$dimX  = $size[0];
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