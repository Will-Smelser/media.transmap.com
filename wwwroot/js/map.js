    
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

        console.log(map);
  
        //create a feature layer based on the feature collection
        featureLayer = new esri.layers.FeatureLayer(Viewer.qbase, {
          mode: esri.layers.FeatureLayer.MODE_SNAPSHOT,
          outFields: ['*']
        });
        
        //featureLayer.setDefinitionExpression("address != ''");
        map.addLayer(featureLayer);
        
        
        dojo.connect(map, 'onLoad', function(theMap) {
          //resize the map when the browser resizes
          dojo.connect(dijit.byId('map'), 'resize', map,map.resize);
          
          Viewer.loadData();
        });
      }

      