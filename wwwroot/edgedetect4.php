<?php

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
	private $r=0;
	private $g=0;
	private $b=0;
	
	public $min=99999;
	public $max=0;
		
	//the position
	public $x, $y;
	
	//total number of points used
	public $totalPoints = 1;
	
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

//start at lower right and keep going left as long
//as things are getting darker
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
			
		//we at at a local max
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
		
		//if($pt->min > $cur) $pt->min = $cur;
		//if($pt->max < $cur) $pt->max = $cur;
		
		$prev = $cur;
		if($i<$xMin) break;
		
	}
	return $mins;
}

for($n=1; $n<10; $n++){
	$temp = $height - 50 * $n;
	$mins = findMins($width*.5,$width-1,$temp,25);
	
	foreach($mins as $key=>$obj){
		color($obj->x,$obj->y);
	}
}

@ImageJPEG($im);
imagedestroy($im);