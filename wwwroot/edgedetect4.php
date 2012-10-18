<?php
//error_reporting(0);
$graph = false;
$image = $_GET['image'];
$file = $_SERVER['DOCUMENT_ROOT'] . "/images/Milton/12033014(1)/FL12033014(1)/FL_".$image.'.jpg';

if(!$graph) header("Content-type: image/jpeg");

$im = imagecreatefromjpeg($file);

$width = imagesx($im);
$height = imagesy($im);

class States {
	const INCREASE = 0;
	const DECREASE = 1;
}
class PtMin {
	//colors
	public $r=0;
	public $g=0;
	public $b=0;
		
	//the position
	public $x, $y;
	
	//total number of points used
	public $totalPoints = 0;
	
	public function PtMin(){
	}
	public function setPos($x,$y){
		$this->x = $x;
		$this->y = $y;
	}
	public function add($rgb){
		$this->r += $rgb[0];
		$this->g += $rgb[1];
		$this->b += $rgb[2];
		$this->totalPoints++;
	}
	public function getAvg(){
		return array($this->r/$this->totalPoints, $this->g/$this->totalPoints, $this->b/$this->totalPoints);
	}
}

function int2rgb($myint)
{
	return array(0 => 0xFF & ($myint >> 0x10), 1 => 0xFF & ($myint >> 0x8), 2 => 0xFF & $myint);
}

//calculate the average
function avg($rgb){
	return ($rgb[0]+$rgb[1]+$rgb[2])/3;
}

//just set a point
function color($x,$y){
	global $im;
	$ellipseColor = imagecolorallocate($im, 0, 0, 255);
	imagefilledellipse($im, $x, $y, 10, 10, $ellipseColor);
}

/**
 * 
 * @param $xMin
 * @param $xMax
 * @param $height
 * @param $group The number of pixels to use in a horizontal grouping for calculating the average) 
 * @throws Exception
 */
function findMins($xMin, $xMax, $height, $group=1){
	global $im;
	global $graph;
	$t = 15; //threshold
	$pt = null;
	
	//scan right to left, looking for mins
	$mins = array();
	$prev = 0;
	$temp = new PtMin();
	$state = States::DECREASE;
	array_push($mins,$temp);
	for($i=$xMax; $i>$xMin; $i = $i - $group){
		
		$cur = 0;
		$r = $g = $b=0;
		for($j = 0; $j < $group; $j++){
			$mtemp = int2rgb(imagecolorat($im, $i-$j, $height));
			$r += $mtemp[0];
			$g += $mtemp[1];
			$b += $mtemp[2];
		}
		$r = $r / $group;
		$g = $g / $group;
		$b = $b / $group;
		$color = array($r,$g,$b);
		$cur = floor(avg($color));
		
		if($graph) echo abs($prev-$cur) . "-";
		
		$pt = ($mins[count($mins)-1]);
		
		//not moving in any direction
		if($cur <= ($prev + $t) && $cur >= ($prev - $t)){
			if($graph) echo "same";
			$pt = null;
			$pt = ($mins[count($mins)-1]);
			$pt->add($color);
			
		//we are still increasing
		}else if($cur > $prev + $t && $state === States::INCREASE){
			if($graph) echo "incr";
			$pt = null;
			$pt = ($mins[count($mins)-1]);
			$pt->add($color);
			
		//we are at a local min
		}else if($cur > $prev + $t && $state === States::DECREASE){
			if($graph) echo "min";
			$pt = null;
			$pt = ($mins[count($mins)-1]);
			$pt->add($color);
			$pt->setPos($i,$height);
			$state = States::INCREASE;
			
		//we are still decreasing
		}else if ($cur < $prev - $t && $state === States::DECREASE){
			if($graph) echo "decr";
			$pt = null;
			$pt = ($mins[count($mins)-1]);
			$pt->add($color);
			
		//we are at a local max
		}else if ($cur < $prev - $t && $state === States::INCREASE){
			if($graph) echo "max";
			$pt = null;
			$temp = new PtMin();
			$temp->add($color);
			array_push($mins,$temp);
			$state = States::DECREASE;
			
		}else {
			throw new Exception("Unreachable state. $cur, $prev : $state,");
		}
		
		if($graph){ echo str_pad("-",avg(int2rgb(imagecolorat($im, $i, $height)))-50,'#'); echo "<br/>\n";}
		
		$prev = $cur;
		if($i<$xMin) break;
		
	}
	//first point is always trash
	array_shift($mins);
	return $mins;
}


/**
 * Find nearest neighbor $x, $y
 * @param int $x
 * @param int $y
 * @param array $rgbAvg
 * @param int $radius
 */
function bestNeighbor($x, $y,array $rgbAvg, $radius){
	global $width, $height, $im;
	
	$neighbors = getCircle($x,$y,15,0,$width,0,$height,$radius);
	
	$minColor = array();
	$minAvg = 999;
	$minX; $minY;
	foreach($neighbors as $pts){
		if($pts[0] < $width && $pts[0] > 0 && $pts[1] > 0 && $pts[1] < $height){
			$tcolor = int2rgb(imagecolorat($im, $pts[0], $pts[1]));
			$tavg = avg($tcolor);
			//color($pts[0],$pts[1]);
			if($tavg < $minAvg){
				list($minX, $minY) = $pts;
				$minColor = $tcolor;
				$minAvg = $tavg;
			}
		}
	}
	
	$pt = new PtMin();
	$pt->setPos($minX,$minY);
	$pt->add($minColor);
	
	color($pt->x,$pt->y);
	
	return $pt;
}

/**
 * 
 * @param int $x
 * @param int $y
 * @param int $radius
 * @param int $minX
 * @param int $maxX
 * @param int $minY
 * @param int $maxY
 * @param int $angle
 * @return array Return an array of points
 */

function getCircle($x, $y, $radius, $minX, $maxX, $minY, $maxY, $angle=20){
	$offset = ($angle * 3.14) / 180;
	
	//all 4 quadrants
	$q1 = $q2 = $q3 = $q4 = array();
	
	//cycle through pi/2 radians
	$i=0;
	for($i=0; $i<1.57; $i=$i+$offset){
		$tempX = floor($radius * sin($i+3.14));
		$tempY = floor($radius * cos($i+3.14));
		
		//need to track, but dont want duplication
		if($tempX + $x < $maxX && $y + $tempY < $maxY) 
			$q4[($x + $tempX).','.($y+$tempY)] = 1;
		
		if($tempX + $x < $maxX && $y - $tempY > $minY)
			$q3[($x + $tempX).','.($y - $tempY)] = 1;
		if($x - $tempX > $minX && $y - $tempY > $minY)
			$q2[($x - $tempX).','.($y - $tempY)] = 1;
		if($x - $tempX > $minX && $y + $tempY < $maxY)
			$q1[($x - $tempX).','.($y + $tempY)] = 1;
		
	}
	
	$result = array();
	foreach(array_merge($q1,$q2,$q3,$q4) as $key=>$val){
		$tx; $ty;
		
		list($tx, $ty) = explode(',',$key);
		array_push($result,array($tx,$ty));
		color($tx,$ty);
	}

	return $result;
}

function findNearest(PtMin $obj, $offset=15, $left=30, $right=5){
	global $im;
	$goal = avg($obj->getAvg());
		
	$diffMin = 9999;
	$cMin;
	$xMin;
	
	for($i=($obj->x-$left); $i<($obj->x+$right); $i++){
		$temp = int2rgb(imagecolorat($im, $i, ($obj->y-$offset)));
		$color = avg($temp);
		$diff = abs($goal - $color);
		
		if($diff < $diffMin){
			$diffMin = $diff;
			$xMin = $i;
			$cMin = $temp;
		}
	}
	
	$pt = new PtMin();
	$pt->setPos($xMin, $obj->y-$offset);
	$pt->add($cMin);
	return $pt;
	
}

$mins = findMins($width*.5,$width-1,($height - 30),10);
foreach($mins as $obj)
	color($obj->x,$obj->y);
$obj = $mins[0];

$obj = findNearest($obj,10);
color($obj->x,$obj->y);

$obj = findNearest($obj,10);
color($obj->x,$obj->y);


$obj = findNearest($obj,10);
color($obj->x,$obj->y);


$obj = findNearest($obj,10);
color($obj->x,$obj->y);


@ImageJPEG($im);
imagedestroy($im);