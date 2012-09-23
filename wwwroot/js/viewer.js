var Viewer = {
	
	baseref : "/Surveys/index_front.php",
	camera : 'FL',
	image : 0,
	imageSize : 0,
	project: '',
	survey: '',
		
	elipseHfactor : .25,
	maxSteps : 8,
	vanish : 180,
	
	width  : 600,
	height : 300,

	paper : null,
	elipse : null,
	arrow : null,
	arrowTxt : null,
	
	leftArea : 120,
	
	inPaper : false,
    
	load : function(baseref, imageSize, image, project, survey, camera){
		this.baseref = baseref;
		this.image = image;
		this.imageSize = imageSize;
		this.project = project;
		this.survey = survey;
		this.camera = camera;
		
		this.width = $('#image-main').width(); 
		this.height = $('#image-main').height();
		
		$('#image-container').css('height',this.height+'px');
		$('#canvas').css('width',this.width+'px');
		
		this.paper = Raphael("canvas", this.width, this.height)
		this.paper.clear();
		this.elipse = this.paper.ellipse(300,100, 50, 20);
		
		this.vanish = (camera == 'BR') ? this.height : this.height/1.75;
		
		
		$('#canvas svg').css('position','absolute').css('z-index','100');
		this.elipse.attr({stroke:"#FFF", "stroke-width":3, fill:"#efefef", "stroke-opacity":0.5, "fill-opacity":0.5}).hide();
		
		$("#canvas").hover(
			$.proxy(this.mouseOverCanvas,this),
			$.proxy(this.mouseOutCanvas, this)
		).mousemove(
			$.proxy(this.whileOverCanvas,this)
		).click(
			$.proxy(this.clickCanvas, this)
		);
		
		//preload some images
		for(i=0; i<=this.maxSteps; i++){
			this.preloadImage(image+i);
		}
		
		//load the reverse arrow
		this.arrow = this.paper.path("M12.981,9.073V6.817l-12.106,6.99l12.106,6.99v-2.422c3.285-0.002,9.052,0.28,9.052,2.269c0,2.78-6.023,4.263-6.023,4.263v2.132c0,0,13.53,0.463,13.53-9.823C29.54,9.134,17.952,8.831,12.981,9.073z").
			attr({stroke:"#FFF", "stroke-width":3, fill:"#efefef", "stroke-opacity":0.5, "fill-opacity":0.5})
			.scale(4,2).hide();
		
		this.arrowTxt = this.paper.text(50, 50, "Turn Around").attr(
				  {"font-family":"Arial",
					   "font-style":"none",
					   "font-size":"16", 
					   stroke:"#FFF", "stroke-width":1, fill:"#efefef", "stroke-opacity":0.5, "fill-opacity":0.5}).hide();
		
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
	
	clickCanvas : function(e){
		
		if(e.offsetX > this.leftArea){
			var y = this.height - e.offsetY;
			var elipseHeight = this.elipse.attr('ry');
		
			steps = Math.ceil(this.expoentialGrow(y, this.maxSteps, this.vanish, 2));
	
			this.canvasClick.call(this, this.addSteps(this.image,steps));
		} else {
			var camera = (this.camera.toUpperCase() == "FL") ? "BR" : "FL";
			this.goToImage(this.image,camera);
		}
	},

	pad : function(str, max) {
		limit = 10;
		str = str + "";
		while(str.length < max && str.length < limit)
			str = "0" + str;
		
		return str;
	},

	$imageCounter : $('#image-counter'),
	$imageMain : $('#image-main'),
	$imageNext : $('#image-next'),
	$loaderWrap : $('#image-loading'),
	loadedImages : [],
	
	preloadImage : function(image){
		image = image * 1;
		if(typeof this.loadedImages[image] == "undefined"){
			
			//mark as loaded
			this.loadedImages[image] = true;

			//load the image
			var obj = this;
			var $img = $(document.createElement('img'));
			var url = obj.getImageUrl(image);
			
			
			$img.attr('src', url).attr('id','image-'+image);
			
			$('#image-loading').append($img);
		}
	},

	removeImage : function(image){
		if(typeof this.loadedImages[image] != "undefined"){
			$('#image-loading').find('#image-'+image).remove();
		}
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
		document.location.href = this.baseref + "?Image="+
			image+"&survey="+this.survey+"&Project="+this.project+
			"&camera="+camera;
	},
	
	canvasClick : function(img){
		img = parseInt(img);
		
		$imageCounter = $('#image-counter');
		$imageMain = $('#image-main');
		$imageNext = $('#image-next');
		$loaderWrap = $('#image-loading');
		
		//document.location.href = getImageUrl(image);
		this.image = img;

		$.cookie("last-image",this.pad(img,5));
		$.cookie("camera",this.camera);
		
		
		$imageCounter.val(this.pad(img,5));
		
		//transition current image
		var obj = this;
		var newImgSrc = $loaderWrap.find('#image-'+img).attr('src');
		
		$imageMain.fadeOut(function(){});
		$imageNext.attr('src',newImgSrc).fadeIn(
			$.proxy(function(){
				$imageMain.attr('src',newImgSrc).show();
				$imageNext.hide();
			}, obj)
		);

		//add the next 5 images
		for(i=0; i<this.maxSteps*2; i++){
			this.preloadImage(this.addSteps(img,i));
		}

		//remove previous images
		for(i=this.maxSteps; i<this.maxSteps*2+5; i++){
			this.removeImage(this.addSteps(img,i));
			this.loadedImages.splice(this.addSteps(img,i),1);
		}
	}
    
};