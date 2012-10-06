var Viewer = {
	
	//link to the display file
	baseref : "/Surveys/index_front.php",
	
	loading : "/images/layout/loading.gif",
	
	//project settings
	camera : 'FL',
	image : 0,
	imageSize : 0,
	project: '',
	survey: '',
		
	//max steps within vanish point
	maxSteps : 8,
	
	//max height of vanish, changes for different cameras
	vanish : 180,
	
	//dims of canvas...overriden onload
	width  : 600,
	height : 300,
	
	//max/min
	firstImage : 0,
	lastImage  : 0,

	//svg objects
	paper : null,
	elipse : null,
	arrow : null,
	arrowTxt : null,
	
	//left bar for turn around width
	leftArea : 120,
	
	//states
	inPaper : false,
	loading : false,
    
	//last clicked...for if the image wasnt loaded
	lastClicked : 0,
	
	//preloader
	preloader : null,
	
	//preloaderImage function to get image
	imageToNumber : function(){
		//console.log(this.url.match(/(\d+\.jpe?g)$/g)).pop().split('.');
		return parseInt(this.url.match(/(\d+\.jpe?g)$/g).pop().split('.')[0]*1);
	},
	
	load : function(baseref, imageSize, image, project, survey, camera, first, last){
		//set values
		this.baseref = baseref;
		this.image = parseInt(image);
		this.imageSize = imageSize;
		this.project = project;
		this.survey = survey;
		this.camera = camera;
		this.firstImage = first;
		this.lastImage  = last;
		
		//get the image width/height
		this.width = $('#image-main').width(); 
		this.height = $('#image-main').height();
		
		//set some attributes for containers and canvas
		$('#image-container').css('height',this.height+'px');
		$('#canvas').css('width',this.width+'px');
		$('#loading').css('width',this.width+'px').first().css('margin-top',(this.height/2-40)+'px');
		
		//setup canvas
		this.paper = Raphael("canvas", this.width, this.height)
		this.paper.clear();
		this.elipse = this.paper.ellipse(300,100, 50, 20);
		
		this.vanish = (camera == 'BR') ? this.height : this.height/1.75;
		
		$('#canvas svg').css('position','absolute').css('z-index','100');
		this.elipse.attr({stroke:"#FFF", "stroke-width":3, fill:"#efefef", "stroke-opacity":0.5, "fill-opacity":0.5}).hide();
		
		//watch the events
		var obj = this;
		$("#canvas").hover(
			$.proxy(this.mouseOverCanvas,this),
			$.proxy(this.mouseOutCanvas, this)
		).mousemove(
			$.proxy(this.whileOverCanvas,this)
		).click(
			$.proxy(this.clickCanvas, this)
		);
		
		//load the reverse arrow
		this.arrow = this.paper.path("M12.981,9.073V6.817l-12.106,6.99l12.106,6.99v-2.422c3.285-0.002,9.052,0.28,9.052,2.269c0,2.78-6.023,4.263-6.023,4.263v2.132c0,0,13.53,0.463,13.53-9.823C29.54,9.134,17.952,8.831,12.981,9.073z").
			attr({stroke:"#FFF", "stroke-width":3, fill:"#efefef", "stroke-opacity":0.5, "fill-opacity":0.5})
			.scale(4,2).hide();
		
		this.arrowTxt = this.paper.text(50, 50, "Turn Around").attr(
				  {"font-family":"Arial",
					   "font-style":"none",
					   "font-size":"16", 
					   stroke:"#FFF", "stroke-width":1, fill:"#efefef", "stroke-opacity":0.5, "fill-opacity":0.5}).hide();
		
		//preload some images
		this.preloader = new Preload('image-preloader');
		this.preloader.extendImage('getNumber',this.imageToNumber);
		for(i=0; i<=this.maxSteps; i++)
			this.preloadImage(this.addSteps(this.image, i));
	},
	
	mouseOverCanvas : function(e){
		this.inPaper = true;
	},

	mouseOutCanvas : function(e){
		this.inPaper = false;
		this.elipse.hide();
		this.arrow.hide().translate(0,0);
		this.arrowTxt.hide();
	},

	whileOverCanvas : function(e){
		if(e.offsetX > this.leftArea){
			this.elipse.attr({cx:e.offsetX, cy:e.offsetY}).show();
			this.arrow.hide();
			this.arrowTxt.hide();
			this.updateElipse(e);
		} else {
			//this.arrow.translate(e.offsetX, e.offsetY).show();
			this.elipse.hide();
			this.arrow.transform("T"+e.offsetX+","+(e.offsetY-20)).scale(4,2).show();
			this.arrowTxt.transform("T"+(e.offsetX-30)+","+(e.offsetY-90)).show();
		}
	},

	exponentialDecay : function exponentialDecay(x, goalMax, scaleMax, exponent){
		return goalMax / Math.pow(scaleMax/(scaleMax-x),exponent);
	},

	expoentialGrow : function(x, goalMax, scaleMax, exponent){
		return goalMax / Math.pow(scaleMax/x,exponent);
	},
	
	updateElipse : function(e){
		//relative to top left
		x = e.offsetX;
		y = e.offsetY;

		//convert to bottom left
		y = this.height - y;
		
		slope = this.vanish / (this.width / 2);
		lineX = (1 / slope) * y;
		elipseX = Math.round(Math.abs(2 * ((this.width/2) - lineX)),0) * .5;

		elipseMaxH = 100;
		elipseY = (y>this.vanish) ? 0 : this.exponentialDecay(y, elipseMaxH, this.vanish, 1.2);
		
		this.elipse.attr({rx:elipseX, ry:elipseY});
	},
	
	addSteps : function(image,steps){
		image = parseInt(image);
		steps = parseInt(steps);
		if(this.camera.toUpperCase() == 'BR'){
			return image - steps;
		} 
		
		return image + steps;
	},
	
	minusSteps : function(image,steps){
		image = parseInt(image);
		steps = parseInt(steps);
		if(this.camera.toUpperCase() == 'BR'){
			return image + steps;
		} 
		
		return image - steps;
	},
	
	clickCanvas : function(e){
		console.log("clicked");
		
		//make sure we arent waiting on an action
		if(this.waiting){
			console.log("We are waiting on image");
			return;
		}
		
		//if NOT turn around
		if(e.offsetX > this.leftArea){
			var y = this.height - e.offsetY;
			var elipseHeight = this.elipse.attr('ry');
		
			steps = Math.ceil(this.expoentialGrow(y, this.maxSteps, this.vanish, 2));
			this.canvasClick.call(this, this.addSteps(this.image,steps));
			
		//toggle view
		} else {
			var camera = (this.camera.toUpperCase() == "FL") ? "BR" : "FL";
			this.goToImage(this.image,camera);
		}
	},

	pad : function(str, max) {
		str = str + "";
		while(str.length < max && str.length < 10)
			str = "0" + str;
		
		return str;
	},

	$imageCounter : $('#image-counter'),
	$imageMain : $('#image-main'),
	$imageNext : $('#image-next'),
	$loaderWrap : $('#image-loading'),
	
	
	preloadImage : function(image){
		image = parseInt(image);
		
		if(image < 0) return;
		
		var url = this.getImageUrl(image);
		
		//preloader wont preload image that has already loaded
		this.preloader.preload(url,this.imageLoadComplete,this);			
	},
	
	imageLoadComplete : function(imgObj){
		//console.log("image finished loading("+imgObj.url+")");
	},
	
	removeImage : function(image){
		image = parseInt(image);
		if(image < 0) return;

		//remove images
		var url = this.getImageUrl(image);
		this.preloader.removeImage(url);
		
	},
	
	getImageUrl : function(image){
		image = this.pad(image,5);
		
		return "/imgsize.php?percent="+this.imageSize+"&img=/images/"+
			this.project+"/"+this.survey+"/"+this.camera+this.survey+"/"+this.camera+"_"+image+".jpg";
	},
	
	goToImage : function(image, camera){
		if(typeof camera == "undefined" || camera == null){
			camera = this.camera;
		}
		
		$.cookie('last-image',null);
		var loc = this.baseref + "?Image="+
			this.pad(image,5)+"&survey="+this.survey+"&Project="+this.project+
			"&camera="+camera;
		
		document.location.href = loc;
	},
	
	loadingShow : function(){
		$('#loading').show();
	},
	
	loadingHide : function(){
		$('#loading').hide();
	},
	
	waitCount : 0,
	waitMax : 100,
	waiting : false,
	waitImageReady : function(imgObj){
		console.log("done waiting");
		this.canvasClick(this.lastClicked);
		this.loadingHide();
	},
	
	
	
	canvasClick : function(img){
		img = parseInt(img);
		
		//clicked image outside of range
		if(img > this.lastImage || img < this.firstImage){
			this.lastClicked = (img > this.lastImage) ? this.lastImage : this.firstImage;
			this.canvasClick(this.lastClicked);
			
			//need to wait till the transition happens till alerting
			setTimeout(function(){alert("No more images in this direction.")},200);
			return;
		}
		
		this.lastClicked = img;
		
		//check if the image was added to DOM
		var url = this.getImageUrl(img);
		var imgObj = this.preloader.getImage(url);
		
		if(imgObj === null || typeof imgObj === "undefined"){
		//if(typeof this.loadedImages[img] == "undefined"){
			console.log("image must load: "+url);
			this.preloadImage(img);
			this.loadingShow();
			//this.waitImageReady();
			this.preloader.waitOnImage(url, this.waitImageReady, this, 50, 200)
			
			return;
		}
		
		//check image finished loading in the DOM
		if(!imgObj.loaded && this.waiting){
		//if(typeof this.completedImages[img] == "undefined"  && this.waiting){
			console.log("Should be waiting on image to load.");
			return;
		}
		
		
		
		$imageCounter = $('#image-counter');
		$imageMain = $('#image-main');
		$imageNext = $('#image-next');
		
		//document.location.href = getImageUrl(image);
		this.image = img;

		$.cookie("last-image",this.pad(img,5));
		$.cookie("camera",this.camera);
		
		
		$imageCounter.val(this.pad(img,5));
		
		//transition current image
		var obj = this;
		var newImgSrc = imgObj.url;
		
		//the animation
		$imageMain.fadeOut(function(){});
		$imageNext.attr('src',imgObj.url).fadeIn(
			$.proxy(function(){
				$imageMain.attr('src',imgObj.url).show();
				$imageNext.hide();
			}, obj)
		);

		//add the next maxSteps*2 images
		for(i=0; i<this.maxSteps*2; i++){
			this.preloadImage(this.addSteps(img,i));
		}

		//remove previous images
		for(i=this.maxSteps; i<this.maxSteps*2+5; i++){
			var temp = this.minusSteps(img,i);
			if(temp < 0) break;
			
			console.log("removing :"+temp);
			//this.removeImage(temp);
		}
	}
    
};