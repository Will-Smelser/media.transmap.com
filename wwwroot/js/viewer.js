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
		
		//the vanishishing line
		this.vanish = (camera == 'BR') ? this.height : this.height/1.75;
		
		//set some attributes for containers and canvas
		$('#image-container').css('height',this.height+'px');
		$('#canvas').css('width',this.width+'px');
		$('#loading').css('width',this.width+'px').first().css('margin-top',(this.height/2-40)+'px');
		
		this._setupCanvas();
		this._createElipse();
		this._createArrow();
		this._createPolygon();
		
		//create preloader
		this.preloader = new Preload('image-preloader');
		this.preloader.extendImage('getNumber',this._imageToNumber);
		
		//preload forward images
		for(i=0; i<=this.maxSteps+5; i++)
			this.preloadImage(this.addSteps(this.image, i));
	},
	//preloaderImage function to get image
	_imageToNumber : function(){
		//console.log(this.url.match(/(\d+\.jpe?g)$/g)).pop().split('.');
		return parseInt(this.url.match(/(\d+\.jpe?g)$/g).pop().split('.')[0]*1);
	},
	_setupCanvas : function(){
		this.paper = Raphael("canvas", this.width, this.height)
		this.paper.clear();
		
		$('#canvas svg')
		.css('position','absolute').css('z-index','100')
		//events
		.hover(
			$.proxy(this.mouseOverCanvas,this),
			$.proxy(this.mouseOutCanvas, this)
		).mousemove(
			$.proxy(this.whileOverCanvas,this)
		).click(
			$.proxy(this.clickCanvas, this)
		);
	},
	_polyAttr : {stroke:"#FFF", "stroke-width":3, fill:"#efefef", "stroke-opacity":0.5, "fill-opacity":0.5},
	_createElipse : function(){
		this.elipse = this.paper.ellipse(300,100, 50, 20);
		this.elipse.attr(this._polyAttr).hide();
	},
	_createArrow : function(){
		//load the reverse arrow
		this.arrow = this.paper.path("M12.981,9.073V6.817l-12.106,6.99l12.106,6.99v-2.422c3.285-0.002,9.052,0.28,9.052,2.269c0,2.78-6.023,4.263-6.023,4.263v2.132c0,0,13.53,0.463,13.53-9.823C29.54,9.134,17.952,8.831,12.981,9.073z").
			attr(this._polyAttr)
			.scale(4,2).hide();
		
		this.arrowTxt = this.paper.text(50, 50, "Turn Around").attr(
				  {"font-family":"Arial", "font-style":"none", "font-size":"16", 
					   stroke:"#FFF", "stroke-width":1, fill:"#efefef", 
					   "stroke-opacity":0.5, "fill-opacity":0.5} ).hide();
	},
	
	
	
	_createPolygon : function(){
		var slope = this.height/this.width;
		var start = 5;
		var end   = 20;
		var stretch = 10;
		var offsetY = 20;
	
		//construe the rectangle
		var top      = 4;
		var bottom   = 0;
		
		
		var p1 = [start,start*slope*bottom+stretch-8];
		var p2 = [start+end, (start+end)*slope+stretch];
		var p3 = [p2[0],-p2[1]-stretch];
		var p4 = [p1[0],start*-slope*top-stretch];
		
		this.polygon = this.paper.path(
				"M"+p1[0]+","+p1[1]+
				"L"+p2[0]+","+p2[1]+
				"L"+p3[0]+","+p3[1]+
				"L"+p4[0]+","+p4[1]+
				"L"+p1[0]+","+p1[1])
			.translate(this.width/2,this.height/2-offsetY).attr(this._polyAttr).hide();
		
		
		var x = 0;
		var y = this._polylineGetY(x);
		var x2= this.width;
		var y2= this._polylineGetY(x2);
		console.log("M"+x+","+y+"L"+x2+","+y2);
		//this.paper.path("M"+x+","+y+"L"+x2+","+y2);
		
	},
	
	_polylineGetY : function(x){
		//poitnts were calculated using 640x380
		var pt1 = [(499/640)*this.width, (250/380)*this.height];
		var pt2 = [((640-15)/640)*this.width, this.height];
		console.log(pt1,pt2);
		var slope = (pt1[1]-pt2[1])/(pt1[0]-pt2[0]);
		console.log(slope);
		//y1 = m * x1 + b
		//y2 = m * x2 + b
		//y1+y2 - m(x1+x2) = 2b
		var xint = (pt1[1]+pt2[1] - slope*(pt1[0]+pt2[0])) / 2;
		
		return x * slope + xint;
	},
	
	mouseOverCanvas : function(e){
		this.inPaper = true;
	},

	mouseOutCanvas : function(e){
		this.inPaper = false;
		this.elipse.hide();
		this.arrow.hide().translate(0,0);
		this.arrowTxt.hide();
		this.polygon.hide();
	},

	whileOverCanvas : function(e){
		console.log(e.offsetX,e.offsetY);
		//turn around area
		if(e.offsetX < this.leftArea){
			this.elipse.hide();
			this.polygon.hide();
			this.arrow.transform("T"+e.offsetX+","+(e.offsetY-20)).scale(4,2).show();
			this.arrowTxt.transform("T"+(e.offsetX-30)+","+(e.offsetY-90)).show();
		//in side wall area
		}else if(
			//only show if we are at front camera
			this.camera.toLocaleUpperCase() === 'FL' &&
			
			//on right side of canvas
			(e.offsetX > (this.width/2)*1.4 ) && (
				//below diagonal
				e.offsetY < (this.height/(this.width*1.0)*e.offsetX) //||
				//above diagonal
				//e.offsetY < this.height - (this.height/(this.width*1.0)*e.offsetX)
			)
		){
			this.arrow.hide();
			this.arrowTxt.hide();
			this.elipse.hide();
			this.polygon.show();
			
			var scale = (e.offsetX-(this.width/2))/(.5*this.width/2)*5+1;
			this.polygon.transform("T"+(e.offsetX-10)+","+((this.height/2)-30)).scale(scale,scale).show();

		//in elipse / road area
		} else {
			this.elipse.attr({cx:e.offsetX, cy:e.offsetY}).show();
			this.arrow.hide();
			this.arrowTxt.hide();
			this.polygon.hide();
			this.updateElipse(e);
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
		var x = e.offsetX;
		var y = this.height - e.offsetY; //convert to bottom left

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
	
	alertUser : function(message){
		alert(message);
	},
	
	clickCanvas : function(e){
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
	
	canvasClick : function(img){
		img = parseInt(img);
		
		//clicked image outside of range
		if(img > this.lastImage || img < this.firstImage){
			this.lastClicked = (img > this.lastImage) ? this.lastImage : this.firstImage;
			this.canvasClick(this.lastClicked);
			
			var obj = this;
			
			//need to wait till the transition happens till alerting
			setTimeout(function(){obj.alertUser("No more images in this direction.")},200);
			return;
		}
		
		this.lastClicked = img;
		
		//check if the image was added to DOM
		var url = this.getImageUrl(img);
		var imgObj = this.preloader.getImage(url);
		
		if(imgObj === null || typeof imgObj === "undefined"){
			//this.preloadImage(img);
			this.loadingShow();
			this.preloader.waitOnImage(url, this.waitImageReady, this, 50, 200)
			return;
		}
		
		//check image finished loading in the DOM
		if(!imgObj.loaded){
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
		$imageMain.fadeOut();
		$imageNext.attr('src',imgObj.url).fadeIn(
			$.proxy(function(){
				$imageMain.attr('src',imgObj.url).show();
				$imageNext.hide();
			}, obj)
		);

		//add the next maxSteps*2 images
		for(i=0; i<this.maxSteps; i++){
			this.preloadImage(this.addSteps(img,i));
		}

		//remove previous images
		for(i=this.maxSteps; i<this.maxSteps+5; i++){
			var temp = this.minusSteps(img,i);
			if(temp < 0) break;
			
			console.log("removing :"+temp);
			//this.removeImage(temp);
		}
	}
    
};