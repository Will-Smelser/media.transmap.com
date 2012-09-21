<?php
require_once '../class/Project.php';
require_once '../class/Session.php';

$session = &Session::getInstance();

//definitions
define('VIEW_DEFAULT','front');
define('IMAGE_SIZE','40');

//get everything to lowercase
foreach($_GET as $key=>$val) $_GET[strtolower($key)] = $val;

//get display vars
$version = (empty($_GET['view']))    ? VIEW_DEFAULT     : $_GET['view'];
$project1= (isset($_GET['project'])) ? $_GET['project'] : null;
$survey  = (isset($_GET['survey']))  ? $_GET['survey']  : null;
$image   = (isset($_GET['image']))   ? $_GET['image']   : null;

try{
	
	$project = new Project($project1, $survey, $image, $session);
	
}catch(Exception $e){
	echo $e->getMessage();
	exit;
}


include '../includes/header_front.php'; 

?>
<script>

window.onload = function () {
	
	var imageSize = <?php echo IMAGE_SIZE; ?>;
	var image = <?php echo $image; ?>;
	var project = "<?php echo $project1; ?>";
	var survey = "<?php echo $survey; ?>";
	
	var elipseHfactor = .4;
	var maxSteps = 5;

	var width = $('#image-main').width();
	var height= $('#image-main').height();

	$('#image-container').css('height',height+'px');
	$('#canvas').css('width',width+'px');
	
	var vanish = height/1.75;
	
	var inPaper = false;

	function mouseOverCanvas(e){
		inPaper = true;
		window.myeventIn = e;
		elipse.show();
	};

	function mouseOutCanvas(e){
		inPaper = false;
		window.myeventOut = e;
		elipse.hide();
	};

	function whileOverCanvas(e){
		elipse.attr({cx:e.offsetX, cy:e.offsetY}).hide().show();
		updateElipse(e);
	};

	function updateElipse(e){
		//relative to top left
		x = e.offsetX;
		y = e.offsetY;

		//convert to bottom left
		y = height - y;
		
		slope = vanish / (width / 2);
		lineX = (1 / slope) * y;
		elipseX = Math.round(Math.abs(2 * ((width/2) - 	lineX)),0) * .5;

		//height of elipse
		percent = y/vanish;
		elipseY = (y>vanish) ? 0 : Math.round((vanish-y)*elipseHfactor,0);
		
		elipse.attr({rx:elipseX, ry:elipseY});
	}
	
	function clickCanvas(e){
		y = height - e.offsetY;
		elipseHeight = elipse.attr('ry');

		//calculate the interval
		f = 1.6;
		temp = 0;
		for(i=0; i<maxSteps; i++)
			temp = temp + 1/(Math.pow(f,i));
		
		interval = vanish / temp;
		
		//determin which interval we are in
		steps = maxSteps;
		temp = 0;
		for(i=0; i<maxSteps; i++){
			
			var test = Math.round(temp+(interval/Math.pow(f,i)),0);
			//console.log("test = "+test);
			window.mytest = test;
			window.myy = y;
			if(y < test){
				//console.log("y="+y+", temp = "+temp+", steps="+i+", interval="+interval);
				steps = i+1;
				break;
			}
			//draw some lines showing the breaks
			//paper.rect(0,height-test, 300,1);
			
			temp+=(interval/Math.pow(f,i));
		}
		
		canvasClick(image+steps);
	};

	function pad (str, max) {
		limit = 10;
		str = str + "";
		while(str.length < max && str.length < limit)
			str = "0" + str;
		
		return str;
	}

	var $imageCounter = $('#image-counter');
	var $imageMain = $('#image-main');
	var $imageNext = $('#image-next');
	var $loaderWrap = $('#image-loading');
	var loadedImages = [];
	
	function preloadImage(image){
		if(typeof loadedImages[image] == "undefined"){
			//mark as loaded
			loadedImages[image] = true;

			//load the image
			var $img = $(document.createElement('img'));
			$img.attr('src',getImageUrl(image)).attr('id','image-'+image);
			$loaderWrap.append($img);
		}
	}

	function removeImage(image){
		if(typeof loadedImages[image] != "undefined"){
			$loaderWrap.find('#image-'+image).remove();
		}
	}
	
	function getImageUrl(image){
		image = pad(image,5);
		
		return "/imgsize.php?percent="+imageSize+"&img=/images/"+project+"/"+survey+"/FL"+survey+"/FL_"+image+".jpg";
	}
	
	function canvasClick(img){
		//document.location.href = getImageUrl(image);
		image = img;
		
		
		$imageCounter.html(pad(img,5));
		
		//transition current image
		var newImgSrc = $loaderWrap.find('#image-'+img).attr('src');
		$imageMain.fadeOut(function(){});
		$imageNext.attr('src',newImgSrc).fadeIn(function(){
			$imageMain.attr('src',newImgSrc).show();
			$imageNext.hide();
		});

		//add the next 5 images
		for(i=0; i<6; i++){
			preloadImage(img+i);
		}

		//remove previous images
		for(i=5; i<10; i++){
			removeImage(img-i);
		}
	}
	
	var paper = Raphael("canvas", width, height);
    paper.clear();
    
    $('#canvas svg').css('position','absolute').css('z-index','100');
    
    var elipse = paper.ellipse(300,100, 50, 20);
    elipse.attr({stroke:"#FFF", "stroke-width":3, fill:"#efefef", "stroke-opacity":0.5, "fill-opacity":0.5}).hide();
    
	$("#canvas").hover(mouseOverCanvas,mouseOutCanvas).mousemove(whileOverCanvas).click(clickCanvas);

	//preload some images
	preloadImage(image);
	preloadImage(image+1);
	preloadImage(image+2);
	preloadImage(image+3);
	preloadImage(image+4);
	preloadImage(image+5);
    
};
//var paper = Raphael("#canvas", 400, 300);

//posx = e.pageX - $(document).scrollLeft() - $('#canvas').offset().left;
//posy = e.pageY - $(document).scrollTop() - $('#canvas').offset().top;

//paper.ellipse(100,100, 50, 20);

</script>

	<div class="container">
		<div class="span-19 last">
			<h1><?php echo $project->getProjectName(); ?> Road Image Viewer</h1>
			<h3>Survey: <?php echo $project->getSurvey(); ?> - Image: <span id="image-counter"><?php echo $project->getImagePadded(); ?></span></h3>
		</div>
	</div>
	
	<div class="container" style="background:#F8F8F8;padding:6px;border-width:1px;border-style:dotted;border-color:black;">
		<div class="image-container" id="image-container">
			<div id="canvas" style="margin:0px auto;">
				<img id="image-main" src="<?php echo $project->getImageFl(0,IMAGE_SIZE); ?>" style="position:absolute;" />
				<img id="image-next" src="<?php echo $project->getImageFl(1,IMAGE_SIZE); ?>" style="position:absolute;display:none;" />
			</div>
		</div>
	</div>
	<div id="image-loading" style="display:none"></div>

<?php include "../includes/footer.php"; ?>