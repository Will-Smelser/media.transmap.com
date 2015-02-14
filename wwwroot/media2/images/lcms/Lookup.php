<?php
/**
 * Created by PhpStorm.
 * User: Will2
 * Date: 2/14/15
 * Time: 5:06 PM
 */

class Lookup {
    public static function findImage($projectName,$projectId,$projectSub,$instance){
        //first find the projectName, correct case
        if(!file_exists($projectName)){
            foreach(scandir(__DIR__) as $file){
                if(strtolower($file) === strtolower($projectName)){
                    $projectName = $file;
                    break;
                }
            }
        }

        $imageName = 'LcmsResult_OverlayInt_'.$instance.'.jpg';

        //find the correct filename, correct case
        if(!file_exists("$projectName/$projectId/$projectSub/$imageName")){
            if(file_exists("$projectName/$projectId/$projectSub")){
                foreach(scandir("$projectName/$projectId/$projectSub") as $file){
                    if(strtolower($file) === strtolower($imageName)){
                        $imageName = $file;
                        break;
                    }
                }
            }
        }

        if(!file_exists(__DIR__."/$projectName/$projectId/$projectSub/$imageName")){
            return false;
        }

        return realpath(__DIR__."/$projectName/$projectId/$projectSub/$imageName");
    }
}