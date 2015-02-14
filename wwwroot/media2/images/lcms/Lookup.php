<?php
/**
 * Created by PhpStorm.
 * User: Will2
 * Date: 2/14/15
 * Time: 5:06 PM
 */

class Lookup {
    public static function findImage($projectName,$projectId,$projectSub,$instance){
        $instance = str_pad($instance,6,'0',STR_PAD_LEFT);
        $projectId = str_pad($projectId,6,'0',STR_PAD_LEFT);

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

    public static function findXml($projectName,$projectId,$projectSub,$instance){
        $instance = str_pad($instance,6,'0',STR_PAD_LEFT);
        $projectId = str_pad($projectId,6,'0',STR_PAD_LEFT);

        //first find the projectName, correct case
        if(!file_exists($projectName)){
            foreach(scandir(__DIR__) as $file){
                if(strtolower($file) === strtolower($projectName)){
                    $projectName = $file;
                    break;
                }
            }
        }

        $xmlName = 'LcmsResult_'.$instance.'.xml';

        //find the correct filename, correct case
        if(!file_exists("$projectName/$projectId/$projectSub/$xmlName")){
            if(file_exists("$projectName/$projectId/$projectSub")){
                foreach(scandir("$projectName/$projectId/$projectSub") as $file){
                    if(strtolower($file) === strtolower($xmlName)){
                        $imageName = $file;
                        break;
                    }
                }
            }
        }

        if(!file_exists(__DIR__."/$projectName/$projectId/$projectSub/$xmlName")){
            echo __DIR__."/$projectName/$projectId/$projectSub/$xmlName";
            return false;
        }

        return realpath(__DIR__."/$projectName/$projectId/$projectSub/$xmlName");
    }
}