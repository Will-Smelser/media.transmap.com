var Viewer = {
	
	//link to the display file
	baseref : "/Surveys/index_front.php",
	
	loading : "/images/layout/loading.gif",
	
	//the service query url
	qbase : null,
	query : null,
	queryTask : null,
	
	//project settings
	camera : 'BR',
	type : 'p',
	image : 0,
	imagePath: '',
	imageSize : 0,
	project: '',
	survey: '',
		
	//max steps within vanish point
	maxSteps : 8,
	
	zoom: 19,
	
	//dims of canvas...overriden onload
	width  : 600,
	height : 300,
	
	//max/min
	firstImage : 0,
	lastImage  : 0,
	
	//left bar for turn around width
	leftArea : 120,
	
	//states
	inPaper : false,
	loading : false,
    
	//last clicked...for if the image wasnt loaded
	lastClicked : 0,
	
	//preloader
	preloader : null,
	
	load : function(baseref, imageSize, image, project, imagePath, survey, camera, type, first, last, query){
		//set values
		this.baseref = baseref;
		this.image = this.lastClicked = parseInt(image);
		this.imagePath = imagePath;
		this.imageSize = imageSize;
		this.project = project;
		this.survey = survey;
		this.camera = camera;
		this.type = type;
		this.firstImage = first;
		this.lastImage  = last;
		this.qbase = query;
		
		//get the image width/height
		this.width = $('#image-main').width(); 
		this.height = $('#image-main').height();
		
		
		//set some attributes for containers and canvas
		$('#image-container').css('height',this.height+'px');
		$('#loading','#loading2').css('width',this.width+'px').first().css('margin-top',(this.height/2-40)+'px');
		
		//create preloader
		this.preloader = new Preload('image-preloader');
		this.preloader.extendImage('getNumber',this._imageToNumber);
		
		//preload forward images
		for(i=0; i<=this.maxSteps+5; i++){
			this.preloadImage(this.addSteps(this.image, i));
			this.preloadImage(this.minusSteps(this.image, i));
		}
		
		//bind click events
		$('#forward').click($.proxy(this._forwardClick,this));
		$('#backward').click($.proxy(this._backwardClick,this));
		$('#selectView').change($.proxy(this._changeView,this))
			.val(this.type+'-'+this.camera+'-'+this.imageSize);
		
		
		//setup the arcgis query
		this.query = new esri.tasks.Query();
	    this.query.returnGeometry = true;
	    this.query.outFields = ["*"];//["IMAGENUM","IMAGE_LINK","Sequence"];
	    this.query.where = "IMAGENUM='"+this.pad(this.image,5)+"' and Survey='"+this.survey+"'";
	    
	    
	    //wait on the map to load
	    var obj = this;
    	this.queryTask = new esri.tasks.QueryTask(this.qbase);
    	this.queryTask.execute(this.query,function(data){
    		Viewer.firstPoint = data.features[0];
    		$.getJSON(Viewer.qbase.replace(/\/0\/query/,"")+'?f=json',Viewer._loadMap);
    	});

    	//map resize click
    	$('#map-full').click(this._fullMap);
    	
    	
	},
	_currentPointGeometry : null,
	
	_fullMapState : false,
	_fullMap : function(){
		$('#loading2').show();
		
		var size = (Viewer._fullMapState) ? ['325px','210px'] : ['100%','100%'];
		Viewer._fullMapState = (!Viewer._fullMapState);
		var $wrapper = $('#map-wrapper');
		$wrapper.find('#map-full').toggleClass('open','close');
		$wrapper.animate({
			width:size[0],
			height:size[1]
		},function(){
			map.resize(true);
			setTimeout(function(){map.centerAt(Viewer._currentPointGeometry)},500);
		});
	},
	_loadMap : function(data){
				
		var popup = new esri.dijit.Popup({}, dojo.create("div"));

		
		var featureQuery = Viewer.qbase.replace(/\/query(\/.*)?/i,'');
		var initExtent = new esri.geometry.Extent(data.fullExtent);

		map = new esri.Map("map", {
		    extent: initExtent,
		    maxRecordCount:100,
		    infoWindow : popup,
		    sliderStyle: "small",
			outFields : ["*"]
		});
		console.log("loaded map",map);
		dojo.addClass(map.infoWindow.domNode, "myTheme");
		
		var basemap = new esri.layers.ArcGISTiledMapServiceLayer("http://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer");
		map.addLayer(basemap);
		
		//after the map loads we want to add the feature layer
		dojo.connect(map, "onLoad", function() {
			//after map is loaded zoom the map to the current point
			map.centerAndZoom(Viewer.firstPoint.geometry, Viewer.zoom);
			
			//now we need to load the featureLayer
			featureLayer = new esri.layers.FeatureLayer(featureQuery, {
	        	mode: esri.layers.FeatureLayer.MODE_ONDEMAND,
	        	outFields: ['*']
	        });
			
			//the feature layer point that was clicked on
			dojo.connect(featureLayer,"onClick",function(evt){
				console.log(evt);
				
				//query this point to get all the data
				Viewer._currentPointGeometry = evt.graphic.geometry;
				
				var query = new esri.tasks.Query();
				query.geometry = evt.graphic.geometry;
				query.spatialRelationship = esri.tasks.Query.SPATIAL_REL_CONTAINS;
				console.log(query);
				featureLayer.selectFeatures(query,esri.layers.FeatureLayer.SELECTION_NEW,
					function(features){
						if(features.length > 0){
							var image = features[0].attributes.IMAGENUM;
							console.log(image);
							Viewer.canvasClick(image);
							Viewer.loadData();
						}else{
							//did not match the clicked on point
						}
					},
					function(){
						//query failed
					}
				);
		    });

			
	        map.addLayer(featureLayer);
	        
	        map.onUpdateEnd = function(){
	        	$('#loading2').hide();
	        }
	        
	        Viewer.loadData();
		});
	},
	loadData : function(){
		//load the data
		$('#data-details').html("Loading...");
		
		this.query.where = "IMAGENUM='"+this.pad(this.image,5)+"' and Survey='"+this.survey+"'";
		
		console.log("loadData",featureLayer);
		if(typeof featureLayer == "object" ){
			
	        featureLayer.selectFeatures(Viewer.query,esri.layers.FeatureLayer.SELECTION_NEW,
	        	function(features){
		        	
		        	map.graphics.clear();
		        	
		        	
		        	var pt = new esri.geometry.Point(features[0].geometry.x,features[0].geometry.y,map.spatialReference);
		        	var sms =new esri.symbol.SimpleMarkerSymbol();
		        	var infoTemplate = new esri.InfoTemplate();
		        	
		        	/*
		        	infoTemplate.setContent(function(data){
		        		console.log(data);
		        		var str = "";
		        		for(var x in data){
		        			str+="<div>"+x+":"+data[x]+"</div>";
		        		}
		        		return str;
		        	}(features[0].attributes));
					*/
		        	
		        	//set the details content
		        	var str = "";
		        	for(var x in features[0].attributes){
		        		var mystr = features[0].attributes[x].toString();
		        		if(mystr.match(/http\:\/\.*/i) != null){
		        			var link = "<a href='"+features[0].attributes[x]+"' target='_blank'>link</a>";
		        			str += '<div><b>' + x + "</b>: " + link + '</div>';
		        		} else {
		        			str += '<div><b>' + x + "</b>: " + features[0].attributes[x] + '</div>';
		        		}
		        	}
		        	$('#data-details').html(str);
		        	
		        	var graphic = new esri.Graphic(pt,sms,features[0].attributes,infoTemplate);
		        	map.graphics.add(graphic);
		        	
		        	var center = new esri.geometry.Point(
		        			features[0].geometry.x+0,
		        			features[0].geometry.y+0,
		        			features[0].geometry.spatialReference);
		        	map.centerAt(center);
		        	Viewer._currentPointGeometry = center;
	        	}
	        );
		}
		
	},
	_showData : function(results){
		console.log(results);
		var s = "";
		var found = null;
		
		var feature = (results.features.length > 0) ?
				results.features[0] : [];
				
		for(var x in feature.attributes)
			s = s + "<b>" + x + ":</b>  " + feature.attributes[x] + "<br />";
		        
        if(s == ""){
        	s = "No Data";
        } else {
        	
        }
        
		$('#data-details').html(s);
	},
	
	_goToImage : function(image){
		
	},
	
	_forwardClick : function(){
		this.canvasClick(this.addSteps(this.lastClicked,1));
		this.loadData();
	},
	
	_backwardClick : function(){
		this.canvasClick(this.minusSteps(this.lastClicked,1));
		this.loadData();
	},
	
	_changeView : function($ev){
		var val = $($ev.currentTarget).val().split('-');
		this.type = val[0];
		this.camera = val[1];
		this.imageSize = val[2];
		
		this.canvasClick(this.lastClicked);
	},
	
	//preloaderImage function to get image
	_imageToNumber : function(){
		//console.log(this.url.match(/(\d+\.jpe?g)$/g)).pop().split('.');
		return parseInt(this.url.match(/(\d+\.jpe?g)$/g).pop().split('.')[0]*1);
	},
	
	exponentialDecay : function exponentialDecay(x, goalMax, scaleMax, exponent){
		return goalMax / Math.pow(scaleMax/(scaleMax-x),exponent);
	},

	expoentialGrow : function(x, goalMax, scaleMax, exponent){
		return goalMax / Math.pow(scaleMax/x,exponent);
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
		
		return "/imgsize.php?percent="+this.imageSize+"&img="+
			this.imagePath+"/"+this.survey+"/"+this.camera+this.survey+"/"+this.camera+"_"+image+".jpg";
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
		
		if(imgObj === null || typeof imgObj === "undefined" || !imgObj.loaded){
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
		$.cookie("type",this.camera);
		
		
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

		//add the next maxSteps
		for(i=0; i<this.maxSteps; i++){
			this.preloadImage(this.addSteps(img,i));
			this.preloadImage(this.minusSteps(img,i));
		}

		//remove previous images
		for(i=this.maxSteps; i<this.maxSteps+5; i++){
			var temp = this.minusSteps(img,i);
			var temp2 = this.addSteps(img,i);
			
			if(temp < 0 || temp2 < 0) break;
			
			console.log("removing :"+temp);
			console.log("removing :"+temp2);
			this.removeImage(temp);
			this.removeImage(temp2);
		}
		
		Viewer.loadData();
	}
    
};