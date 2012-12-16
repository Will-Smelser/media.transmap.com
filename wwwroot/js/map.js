    
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
      

      //called after the page loads with the featureService base information
      function init(data) {
    	/*
    	  var featureQuery = Viewer.qbase.replace(/\/query(\/.*)?/i,'');
    	  var initExtent = new esri.geometry.Extent(
    			  data.fullExtent
    	  );
    	console.log(data);    	  
		map = new esri.Map("map", {
		    extent: initExtent,
		    maxRecordCount:100,
		});
		
        var basemap = new esri.layers.ArcGISTiledMapServiceLayer("http://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer");
        map.addLayer(basemap);
        
        
        map.onLoad = function(map){
        	map.setLevel(16);
        	
	        //create a feature layer based on the feature collection
	        featureLayer = new esri.layers.FeatureLayer(featureQuery, {
	        	mode: esri.layers.FeatureLayer.MODE_ONDEMAND,
	        	outFields: ['*']
	        });
	        map.addLayer(featureLayer);
	        
	        featureLa
	        Viewer.loadData();
        }*/
        /*
        dojo.connect(map, 'onLoad', function(theMap) {
          //resize the map when the browser resizes
          dojo.connect(dijit.byId('map'), 'resize', map,map.resize);

        });
        */
      }

      