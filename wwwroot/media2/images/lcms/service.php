<?php

if(!function_exists('http_response_code')){
    function http_response_code($code){
        switch($code){
            case 400:
                header("HTTP/1.1 400 Bad Request");
                break;
            case 404:
                header("HTTP/1.1 404 Not Found");
                break;
            case 500:
                header("HTTP/1.1 500 Internal Server Error");
                break;
        }
    };
}

if(!isset($_GET['action'])){
    http_response_code(400);
    echo "Error.  Missing 'action' parameter.";
    exit;
}

include 'Lookup.php';

header('Content-Type: application/json');

function processPath($path){
    $path = str_replace('.','',$_GET['path']);
    $path = ltrim($path,'/');
    $parts = explode('/',$path);

    if(count($parts) < 3){
        http_response_code(400);
        echo "Invalid 'path' parameter.  Expected project/id/subId";
        exit;
    }

    $path = __DIR__ . '/' . $parts[0] . '/' . $parts[1] . '/' . $parts[2];
    //handle case issues
    if(!is_dir($path)){
        foreach(scandir(__DIR__) as $file){
            if(strtolower($file) === strtolower($parts[0])){
                $parts[0] = $file;
                break;
            }
        }
    }

    $path = __DIR__ . '/' . $parts[0] . '/' . $parts[1] . '/' . $parts[2];

    if(!is_dir($path)){
        http_response_code(404);
        echo "No project data found.";
        exit;
    }

    return $path;
}

switch(strtolower($_GET['action'])){
    case 'projects':
        echo json_encode(Lookup::listProjects());
        break;

    case 'projectdata':
        if(!isset($_GET['project'])){
            http_response_code(400);
            echo "Missing 'project' parameter.";
            exit;
        }

        //sanitize, since we are going to scan a directory
        $project = preg_replace('#\/\.#','',$_GET['project']);
        $ids = Lookup::listDirs($project);

        foreach($ids as $id)
            $ids[$id] = Lookup::listDirs(__DIR__."/$project/$id");

        if(count($ids) <= 0){
            http_response_code(404);
            echo "[]";
            exit;
        }

        echo json_encode($ids);
        break;
    case 'xml':
        if(!isset($_GET['path'])){
            http_response_code(400);
            echo "Missing 'path' parameter.";
            exit;
        }

        $path = processPath($_GET['path']);

        $result = array();
        foreach(scandir($path) as $file){
            if($file[0] === '.') continue;
            if(preg_match('/(\.xml)$/i',$file)){
                array_push($result,$file);
            }
        }
        echo json_encode($result);
        break;
    case 'summary':
        if(!isset($_GET['path'])){
            http_response_code(400);
            echo "Missing 'path' parameter.";
            exit;
        }

        $path = processPath($_GET['path']);

        $result = array('min'=>null,'max'=>null);
        foreach(scandir($path) as $file){
            if($file[0] === '.') continue;
            preg_match("/(?P<digits>\d+)\.xml/",$file,$matches);

            if(isset($matches['digits'])){
                if($result['min'] === null || $result['min'] > $matches['digits'] * 1){
                    $result['min'] = $matches['digits'] * 1;
                    break;
                }
            }
        }

        foreach(scandir($path,1) as $file){
            if($file[0] === '.') continue;
            preg_match("/(?P<digits>\d+)\.xml/",$file,$matches);

            if(isset($matches['digits'])){
                if($result['max'] === null || $result['max'] < $matches['digits'] * 1){
                    $result['max'] = $matches['digits'] * 1;
                    break;
                }
            }
        }

        if($result['min'] === null && $result['max'] === null){
            http_response_code(500);
            echo "Failed to calculate min/max.";
            exit;
        }

        echo json_encode($result);
        break;
    case 'dims':
        if(!isset($_GET['path'])){
            http_response_code(400);
            echo "Missing 'path' parameter.";
            exit;
        }

        $path = processPath($_GET['path']);

        $size = null;
        foreach(scandir($path) as $file){
            if($file[0] === '.') continue;
            if(preg_match('/(\.jpe?g)$/i',$file)){
                $size = getimagesize($path.'/'.$file);
                break;
            }
        }

        if(!$size){
            http_response_code(404);
            echo "Failed to lookup valid image.";
            exit;
        }

        echo json_encode($size);

        break;
    default:
        http_response_code(400);
        echo "No parameter '{$_GET['action']} supported.";
        break;
}
?>