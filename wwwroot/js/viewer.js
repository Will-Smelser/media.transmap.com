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
    imgserver: '/images',
		
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
	
	refreshDims : function(){
		//get the image width/height
		Viewer.width = $('#image-main').width();
		Viewer.height = $('#image-main').height();
		
		$('#loading','#loading2').css('width',Viewer.width+'px').first().css('margin-top',(Viewer.height/2-40)+'px');
		$('#map-wrapper').css('height',Viewer.height+'px');
		$('#image-next').width(Viewer.width+'px').height(Viewer.height+'px');
		
	},
	
	load : function(baseref, imageSize, image, project, imgserver, imagePath, survey, camera, type, first, last, query){
		//set values
		this.baseref = baseref;
		this.image = this.lastClicked = parseInt(image,10);
        this.imgserver = imgserver;
		this.imagePath = imagePath;
		this.imageSize = imageSize;
		this.project = project;
		this.survey = survey;
		this.camera = camera;
		this.type = type;
		this.firstImage = first;
		this.lastImage  = last;
		this.qbase = query;
		
		this.refreshDims();
		
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
    		Viewer._loadMap(mapData);
    	});

    	//map resize click
    	$('#map-full').button({text: false,label:'Max/Min Map',icons:{primary: "ui-icon-arrow-4-diag"}}).click(this._fullMap);
    	
    	//survey change
    	$('#survey-list').change(this._surveyChange);
	},
	_urlRequestImageLimits:function(survey){
		var url = localServiceUrl + "?action=getSurveyLimits";
		url += "&project="+Viewer.project;
		url += "&survey="+survey;
		url += "&camera="+Viewer.camera;
		return url;
	},
	_surveyChange : function(evt){
		
		//set the cookie
		$.cookie("survey", $(this).val());
		Viewer.survey = $(this).val();
		
		//get the new limits

		var url = Viewer._urlRequestImageLimits(Viewer.survey);
		
		$.getJSON(url,function(result){
			if(!result.result){
				Viewer.alertUser("Failed to get survey min/max image number.");
				console.log(result);
				return;
			}
			
			Viewer.firstImage = result.data.lower;
			Viewer.lastImage  = result.data.upper;
		});


		Viewer.canvasClick(Viewer.image);
		Viewer.loadData();
	},
	_surveyListSetVal : function(survey){
		
		var found = false;
		$('#survey-list').children().each(
			function(index){
				if($(this).val() === survey){
					found = true;
					return;
				}
			}
		);
		
		if(!found){
			var $opt = $(document.createElement('option'));
			$opt.attr("value",survey);
			$opt.text(survey);
			$('#survey-list').append($opt);
		}
		$('#survey-list').val(survey).uiselect('refresh');
		$.cookie("survey", survey);
	},
	_currentPointGeometry : null,
	
	_mapLoadingShow : function(){
		$('#loading2').show();
	},
	_mapLoadingHide : function(){
		$('#loading2').hide();
	},
	_fullMapState : false,
	_fullMap : function(){
		Viewer._mapLoadingShow();
		var size = (Viewer._fullMapState) ? ['50%',Viewer.height+'px'] : ['100%','100%'];
		
		var $mapWrapper = $('#map-wrapper');
		
		//map is opening
		if(!Viewer._fullMapState)
			$mapWrapper.toggleClass('map-relative map-absolute');


		$mapWrapper
				.animate({
					width:size[0],
					height:size[1]
				},function(){
					map.resize(true);
					setTimeout(function(){map.centerAt(Viewer._currentPointGeometry)},500);
					
					//map is closing
					if(Viewer._fullMapState)
						$mapWrapper.toggleClass('map-relative map-absolute');
					
					Viewer._fullMapState = (!Viewer._fullMapState);
				})
				
				.find('#map-full span:first').toggleClass('ui-icon-arrow-4-diag').toggleClass('ui-icon-arrow-4');
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
				//mode: esri.layers.FeatureLayer.MODE_SNAPSHOT, will not work
	        	outFields: ['*']
	        });
						
			//the feature layer point that was clicked on
			dojo.connect(featureLayer,"onClick",function(evt){
				Viewer._mapLoadingShow();
				
				//query this point to get all the data
				Viewer._currentPointGeometry = evt.graphic.geometry;
				
				var query = new esri.tasks.Query();
				query.geometry = evt.graphic.geometry;
				query.spatialRelationship = esri.tasks.Query.SPATIAL_REL_CONTAINS;
				
				//select the loaded point
				featureLayer.selectFeatures(query,esri.layers.FeatureLayer.SELECTION_NEW,
					function(features){
						
						if(features.length > 0){
							var image = features[0].attributes.IMAGENUM;
							
							//check if we are in the same survey
							var tempSurvey = (typeof features[0].attributes.SURVEY == "undefined") ? 
									features[0].attributes.Survey : features[0].attributes.SURVEY;
									
							//changeing survey
							if(tempSurvey !== Viewer.survey){
								
								var url = Viewer._urlRequestImageLimits(tempSurvey);
								Viewer.loadingShow();
								Viewer.survey = tempSurvey;
								Viewer.firstImage = 0;
								Viewer.lastImage  = 99999;
								Viewer._surveyListSetVal(tempSurvey);
								Viewer.canvasClick(image);
								

								$.getJSON(url,function(result){
									if(!result.result){
										Viewer.alertUser("Failed to get survey min/max image number for survey.");
										
										Viewer.firstImage = 0;
										Viewer.lastImage  = 99999;
										
									} else {
										
										Viewer.firstImage = result.data.lower;
										Viewer.lastImage  = result.data.upper;
										
									}
									Viewer.canvasClick(image);
								})
								//catch the 400 errors and such
								.error(function(err){
									Viewer.alertUser("Failed to get survey min/max image number for survey.");
									
									Viewer.firstImage = 0;
									Viewer.lastImage  = 99999;
									Viewer.canvasClick(image);
								});

							} else {
								Viewer.canvasClick(image);
							}
							
						}else{
							//did not match the clicked on point
							$('#data-details').html("No data.");
						}
					},
					function(err){
						//query failed
						$('#data-details').html("Failed to query data.");
					}
				);
		    });

			
	        map.addLayer(featureLayer);
	        
	        map.onUpdateEnd = function(){
	        	Viewer._mapLoadingHide()
	        }
	        
	        Viewer.loadData();
		});
	},
	loadData : function(){
        //reload the tabs, will fail on older viewers
        $("#tabs").tabs('select',0);

		//load the data
		$('#data-details').html("Loading...");
		
		Viewer.query.where = "IMAGENUM='"+this.pad(this.image,5)+"' and Survey='"+this.survey+"'";
		
		if(typeof featureLayer == "object" ){
			
	        featureLayer.selectFeatures(Viewer.query,esri.layers.FeatureLayer.SELECTION_NEW,
	        	function(features){
		        	if(features.length > 0){
			        	map.graphics.clear();
			        	
			        	var pt = new esri.geometry.Point(features[0].geometry.x,features[0].geometry.y,map.spatialReference);
			        	var sms =new esri.symbol.SimpleMarkerSymbol();
			        	var infoTemplate = new esri.InfoTemplate();
			        	
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
		        	}else{
		        		$('#data-details').html("No data.");
		        	}
	        	},
	        	function(err){
	        		$('#data-details').html("Failed to query data.");
	        	}
	        );
		}
		
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
		
		setTimeout(function(){
			Viewer.refreshDims();
			map.centerAt(Viewer._currentPointGeometry);
			map.resize(true);
		},500);
		
	},
	
	//preloaderImage function to get image
	_imageToNumber : function(){
		//console.log(this.url.match(/(\d+\.jpe?g)$/g)).pop().split('.');
		return parseInt(this.url.match(/(\d+\.jpe?g)$/g).pop().split('.')[0]*1,10);
	},
	
	exponentialDecay : function exponentialDecay(x, goalMax, scaleMax, exponent){
		return goalMax / Math.pow(scaleMax/(scaleMax-x),exponent);
	},

	expoentialGrow : function(x, goalMax, scaleMax, exponent){
		return goalMax / Math.pow(scaleMax/x,exponent);
	},
		
	addSteps : function(image,steps){
		image = parseInt(image,10);
		steps = parseInt(steps,10);
		if(this.camera.toUpperCase() == 'BR'){
			return image - steps;
		} 
		
		return image + steps;
	},
	
	minusSteps : function(image,steps){
		image = parseInt(image,10);
		steps = parseInt(steps,10);
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
		image = parseInt(image,10);
		
		if(image < 0) return;
		
		var url = this.getImageUrl(image);
		
		//preloader wont preload image that has already loaded
		this.preloader.preload(url,this.imageLoadComplete,this);			
	},
	
	imageLoadComplete : function(imgObj){
		//console.log("image finished loading("+imgObj.url+")");
	},
	
	removeImage : function(image){
		image = parseInt(image,10);
		if(image < 0) return;

		//remove images
		var url = this.getImageUrl(image);
		this.preloader.removeImage(url);
		
	},
	
	getImageUrl : function(image){
		image = this.pad(image,5);
		
		return "/imgsize.php?percent="+this.imageSize+"&img="+this.imgserver+
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
		this.canvasClick(this.lastClicked);
		this.loadingHide();
	},
	
	alertUser : function(message){
		$('#dialog').html(message).dialog({
			resizable:false,
			title: 'Alert',
			modal: true,
			buttons: [
				{
					text: "Ok",
					click: function() {$(this).dialog("close");}
				}]
		});
	},
	
	canvasClick : function(img){
		img = parseInt(img,10);
		
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
		
		//if the image isnt loading
		if(imgObj === null || typeof imgObj === "undefined" || !imgObj.loaded){
			this.loadingShow();
			this.preloader.preload(url, this.waitImageReady, this, 5000);
			this.preloader.waitOnImage(url, this.waitImageReady, this, 50, 200)
			return;
		}
		
		//check image finished loading in the DOM
		if(!imgObj.loaded){
			return;
		}
		
		this.loadingHide();
		
		$imageCounter = $('#image-counter');
		$imageMain = $('#image-main');
		$imageNext = $('#image-next');
		
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
			
			this.removeImage(temp);
			this.removeImage(temp2);
		}
		
		Viewer.loadData();
	}
    
};