    
      var dojoConfig = {
        parseOnLoad: true
      };
    
      dojo.require("esri.arcgis.utils");
      dojo.require("dijit.layout.BorderContainer");
      dojo.require("dijit.layout.ContentPane");
      dojo.require("esri.map");
      dojo.require("esri.layers.FeatureLayer");
      dojo.require("esri.dijit.Popup");
      dojo.require("esri.geometry");


      window.map;
      window.featureLayer;

      function init(data) {

    	var initExtent = new esri.geometry.Extent(
    			data.extent
    	);
    	      	  
        map = new esri.Map("map", {
            extent: initExtent
          });
        
        var basemap = new esri.layers.ArcGISTiledMapServiceLayer("http://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer");
        map.addLayer(basemap);

        //console.log(data);
  
        //create a feature layer based on the feature collection
        featureLayer = new esri.layers.FeatureLayer(Viewer.qbase.replace(/\/query(\/.*)?/i,''), {
        //featureLayer = new esri.layers.FeatureLayer("http://services.arcgis.com/Gyd9F6MUsQ0SKcSf/arcgis/rest/services/vanimg/FeatureServer/0",{
        //featureLayer = new esri.layers.FeatureLayer("http://services.arcgis.com/Gyd9F6MUsQ0SKcSf/ArcGIS/rest/services/vanimg/FeatureServer/0",{
        //featureLayer = new esri.layers.FeatureLayer("http://services.arcgis.com/Gyd9F6MUsQ0SKcSf/ArcGIS/rest/services/Wilmington,_NC_Sign_Management_Data_(2011)/FeatureServer/0",{
          //mode: esri.layers.FeatureLayer.MODE_SNAPSHOT,
        	mode: esri.layers.FeatureLayer.MODE_ONDEMAND,
          outFields: ['*']
          
        });
        
        //featureLayer.setDefinitionExpression("address != ''");
        map.addLayer(featureLayer);
        
        map.onUpdateEnd = function(){
        	if(typeof map.extent.spatialReference == "undefined")
        		return;
        	map.graphics.clear();
        	
        	var symbol = new esri.symbol.SimpleMarkerSymbol();
        	
            //select the points within the extent
            var query = new esri.tasks.Query();
            query.returnGeometry = true;
    	    query.outFields = ["*"];
    	    query.where = "1=1";
            query.geometry = map.extent;
            console.log(map.extent);
            featureLayer.queryFeatures(query,function(data){
            	
            	var symbol = new esri.symbol.SimpleMarkerSymbol();
            	dojo.map(data.features,function(feature){
                    //feature.symbol = symbol;
                    //featureLayer.graphics.add(feature);
            		/*
            		var pt = new esri.geometry.Point(feature.,yloc,map.spatialReference)
            		var sms = new esri.symbol.SimpleMarkerSymbol().setStyle(
            		  esri.symbol.SimpleMarkerSymbol.STYLE_SQUARE).setColor(
            		  new dojo.Color([255,0,0,0.5]));
            		var attr = {"Xcoord":evt.mapPoint.x,"Ycoord":evt.mapPoint.y,"Plant":"Mesa Mint"};
            		var infoTemplate = new esri.InfoTemplate("Vernal Pool Locations","Latitude: ${Ycoord} <br/>
            		  Longitude: ${Xcoord} <br/>
            		  Plant Name:${Plant}");
            		var graphic = new esri.Graphic(pt,sms,attr,infoTemplate);
            		*/
                  });
            	
            	console.log("success",data);
            	

            },function(e){console.log("failure",e)});
            
            
            
        }
        
        dojo.connect(map, 'onLoad', function(theMap) {
          //resize the map when the browser resizes
          dojo.connect(dijit.byId('map'), 'resize', map,map.resize);
          
          Viewer.loadData();
        });
      }

      