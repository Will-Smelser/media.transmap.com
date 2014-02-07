<!DOCTYPE html>
<html>
<head>
    <title>Sign Data Collection</title>

    <link rel="stylesheet" href="http://www.w3.org/StyleSheets/Core/Swiss" type="text/css">
    <link rel="stylesheet" href="/theme-jquery/jquery-ui-1.9.2.custom/css/custom-theme/jquery-ui-1.9.2.custom.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/jqpagination.css" />

    <script src="http://code.jquery.com/jquery-1.8.1.min.js" ></script>
    <script src="/theme-jquery/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js" ></script>
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRaB8SQtxaNa909sDMK8Py1etA6m1RSYg&sensor=true">
    </script>
    <script src="/js/cookie.js" ></script>
    <script src="/js/jquery.jqpagination.min.js" ></script>
    <script src="/js/oAuth.js"></script>
    <script src="/js/store.js"></script>

    <style type="text/css">
        html { height: 100% }
        body { height: 100%; margin: 0; padding: 0; line-height: normal; }
        #map-canvas { height: 100%; margin-right:300px; }
        li{
            margin:0px;
            padding:0px;
            list-style: none;
        }
        #savingMsg li{
            list-style:disc;
        }
        #savingMsg{
            padding-left: 30px;
        }
        h2,h3{
            margin-top:.2em;
        }
        hr{
            line-height: 3px;
            background-color: #FFF;
        }
        #sign-results-wrapper{
            position:absolute;
            right:0px;
            top:0px;
            height:100%;
            width:300px;
            background-color: #EFEFEF;
            z-index: 1000;
        }
        label{cursor:pointer;}
        .img-base{
            display:inline-block;
            width:18px;
            height:18px;
            background-repeat: no-repeat;
            float:right;
            margin-right:10px;
            cursor:pointer;
        }
        .GOOD{
            background-image:url("/images/layout/icons/accept.png");
        }
        .POOR{
            background-image:url("/images/layout/icons/cancel.png");
        }
        .ui-resizable-w{
            background-color:#DDD;
        }
        .color-key{
            opacity:.5;
            width:10px;
            height:10px;
            border:solid #333 2px;
            display:inline-block;
            margin: 0px 10px;
            cursor: pointer;
        }
        .center{margin:0px auto;text-align:center;}
        .name span{
            cursor: pointer;
        }
    </style>

</head>
<body>

<div id="sign-results-wrapper">
    <div style="padding:10px">
        <div>
            <label><input type="checkbox" id="gpsOn"> GPS on?</label><br>
            <label><input type="checkbox" id="saveFusion"> Save to Fusion?</label>
        </div>
        <div><h2>Sign Data</h2></div>
        <div style="font-size:12px">
            <div style="float:left" >[ <a id="delete-checked" style="cursor:pointer;" >Delete Checked</a> ]</div>
            <div style="float:right">[ <a id="delete-local"   style="cursor:pointer;">Empty LocalStore</a>]</div>
            <div style="clear:both"></div>
        </div>
        <ul id="sign-data" class="content"></ul>

        <div style="margin:0px auto;display: table">
            <div style="display:table-cell">
                <div class="pagination">
                    <a href="#" class="first" data-action="first">&laquo;</a>
                    <a href="#" class="previous" data-action="previous">&lsaquo;</a>
                    <input type="text" readonly="readonly" data-max-page="40" />
                    <a href="#" class="next" data-action="next">&rsaquo;</a>
                    <a href="#" class="last" data-action="last">&raquo;</a>
                </div>
            </div>
        </div>
        <hr>
        <div class="center"><input type="button" id="export-btn" value="Export Local Data" /></div>
    </div>

</div>

<!-- All the dialogs -->
<div id="dialog">
    <input type="hidden" id="row-key" />
    <form id="row-data" style="font-size:12px"></form>
    <div class="center"><input type="button" id="row-data-btn" value="Save" /></div>
</div>

<div id="export">
    <textarea id="export-data" style="width:100%;height:100%"></textarea>
</div>

<div id="saving">
    <h3>Performing Update...</h3>
    <ol id="savingMsg"></ol>
</div>

<!-- the map -->
<div id="map-canvas"/>


</body>


<script>
    var Display = {
        conditionField : 'Night_Insp',

        //pagination
        curPage : 1,
        pageSize : 3,

        rectColors : [
            '#00ffff','#000000','#0000ff','#ff00ff','#008000','#00ff00','#800000',
            '#000080','#808000','#ffa500','#800080','#ff0000','#c0c0c0','#008080',
            '#ffff00'
        ],
        rectPath : 'M-10 -10 L-10 10 L10 10 L10 -10 Z',
        rectMarks : [],

        $wrapper : null,
        store : null,
        map : null,

        //override these
        clickColor : function(key,$el){console.log("warn: clickColor not overriden")},
        clickCond : function(key,$el){console.log("warn: clickCond not overriden")},
        clickName : function(key,$el){console.log("warn: clickName not overriden")},

        init : function($wrapper, store, map){
            this.$wrapper = $wrapper;
            this.store = store;
            this.map = map;
            this._initMarkers();
        },
        _initMarkers : function(){
            var path = {
                path: this.rectPath,
                fillColor: 'yellow',
                fillOpacity: 0.7,
                scale: 1,
                strokeColor: '#333',
                strokeWeight: 4
            };
            for(var i=0; i<this.pageSize; i++){
                var lpath = $.extend(null,path);
                lpath.fillColor = this.rectColors[i];
                var marker = new google.maps.Marker({
                    map: this.map,
                    title: 'Visited Sign',
                    icon:lpath
                });
                /*
                google.maps.event.addListener(marker, 'click', function(e) {
                    alert('clicked');
                });
                */
                this.rectMarks.push(marker);
            }
        },
        getRange : function(page){
            if(typeof page == "undefined")
                page = this.curPage;

            var start = this.pageSize * (page-1);
            var stop = start + this.pageSize;
            return {start:start,stop:stop};
        },
        getName : function(row){
            var fields = ['MUTCD','SIGN_FACE_'];
            var result = "";
            for(var x in fields)
                result += row[fields[x]].value+"&nbsp;&nbsp";
            return result;
        },
        getCondition : function(row){
            return row[this.conditionField].value;
        },
        _hideMarkers : function(){
            for(var x in this.rectMarks){
                this.rectMarks[x].setVisible(false);
            }
        },
        redrawPaginate : function(){
            var size = Math.max(1,Math.ceil(this.store.length/this.pageSize));

            if(size < this.curPage){
                this.curPage = size;
                $('.pagination').jqPagination('option', 'current_page', this.curPage);
            }

            $('.pagination').jqPagination('option', 'max_page', size);
        },
        redraw : function(page, scope){
            this._hideMarkers();

            if(typeof page != "undefined")
                this.curPage = page;

            var info = this.getRange(page);

            this.$wrapper.empty();

            var self = this;

            var counter = 0;
            this.store.iterate(function(key,row){
                var name = self.getName(row);
                var condition = self.getCondition(row);
                var color = self.rectColors[counter%self.rectColors.length];
                var marker = self.rectMarks[counter];

                var $li = self._row.call(self, scope, key, name,
                    self.rectColors[counter], condition, self.clickName, self.clickColor, self.clickCond);

                self.$wrapper.append($li);
                self._setMarker(marker, row);

                counter++;
            },info.start,info.stop);

            var size = 88;//Math.max(1,Math.ceil(this.store.length/this.store.pageSize));
            //$('.pagination').jqPagination('option', 'max_page', size);
        },
        _setMarker : function(marker, row){
            var pos = new google.maps.LatLng(row.Y.value,row.X.value);
            marker.setPosition(pos);
            marker.setVisible(true);

            var icon = marker.getIcon();
            icon.strokeColor = (row[this.conditionField].value=="POOR")?"red":"green";
            marker.setIcon(icon);
        },
        _row : function(scope, key, name, color, imgClass, clickName, clickColor, clickCond){

            var $cbox = $(document.createElement('input')).attr('type','checkbox').attr('id','key-'+key);
            var $div = $(document.createElement('div'));

            var $box = $(document.createElement('div')).addClass('color-key')
                    .attr('style','background-color:'+color);

            var $label = $(document.createElement('div')).addClass('name');//.attr('for','key-'+key)
            var $li = $(document.createElement('li')).attr('data-key',key);

            var $img = $(document.createElement('span')).addClass('img-base').addClass(imgClass);

            var $nameWrap = $(document.createElement('span')).html(name);
            $nameWrap.click(function(){clickName.call(scope,key,$(this));});

            $label.append($cbox).append($box).append($nameWrap).append($img);
            $div.append($label);
            $li.append($div);

            //add color key event
            $box.click(function(){clickColor.call(scope, key, $(this));});

            //add img event
            $img.click(function(){clickCond.call(scope, key, $(this));});

            return $li;
        }
    };

    var App = {
        fusionSave : false,
        fusionTableId : null,
        nameUnique : 'SignID',
        nameUpdateColumns : ["Roadname","MUTCD","SIGN_FACE_","CONDITION","POST_TYPE","X","Y","Night_Insp","IMAGE_LINK","Inspection_Flag","Label","Insp_Comment","timestamp"],
        nameColumns : ['MUTCD','SIGN_FACE_'],
        conditionField : 'Night_Insp',
        exportFields : ['SignID','Night_Insp','Insp_Comment','timestamp'],

        //track click index
        index : 99999,

        display : null,
        store : null,
        map : null,

        marker : null,

        init : function(display,store,map){
            this.display = display;
            this.store = store;
            this.map = map;

            this.display.clickColor = this._clickColor;
            this.display.clickCond = this._clickCond;
            this.display.clickName = this._clickName;
            this.display.clickPaginate = this._clickPaginate;

            //generic marker
            this.marker = new google.maps.Marker({
                position:new google.maps.LatLng(34.409191, -119.692953),
                map: map,
                title: 'Location'
            });

            //geolocation marker
            this._geoMarker = new google.maps.Circle(
                {
                    strokeColor: 'blue',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: 'blue',
                    fillOpacity: 0.35,
                    map: map,
                    //center: citymap[city].center,
                    radius: 10
                }
            );

            //setup the index
            var lastIndex = this.index;
            store.iterate(function(key,row){
                var temp = parseInt(key);
                if(temp < lastIndex)
                    lastIndex = temp;
            });
            this.index = lastIndex-1;

            var scope = this;

            //setup pagination
            $('.pagination').jqPagination({
                current_page:this.curPage,
                max_page:Math.max(1,Math.ceil(this.store.length/this.display.pageSize)),
                paged: function(page){
                    scope._clickPaginate.call(scope,page);
                }
            });

            //SOME DIALOGS
            //edit dialog
            var ht = ($(window).height()-50);
            $('#dialog').dialog({
                title:'Point Data',autoOpen:false,
                width:400,height:ht
            });
            $('#row-data-btn').button().click(function(){scope._saveDialog.call(scope);});

            //export dialog
            $('#export').dialog({
                title:'Result Data',autoOpen:false,
                width:'90%',height:ht
            });
            $('#export-btn').button().click(function(){scope.export.call(scope);});

            //save dialog
            $('#saving').dialog({
                title:'Saving to Fusion Tables',
                autoOpen:false, modal:true,
                width:500,height:300
            });
        },

        redraw : function(page){
            this.display.redrawPaginate();
            this.display.redraw(page,this);
        },
        getName : function(row){
            var name = "";
            for(var x in this.nameColumns)
                name += row[this.nameColumns[x]].value + "&nbsp;&nbsp;&nbsp;";
            return name;
        },
        _clickPaginate : function(page){
            this.display.redraw(page,this);
        },
        _clickCond : function(key,$el){
            var row = this.store.getItem(key);
            var cond = (row[this.conditionField].value === "POOR")?"GOOD":"POOR";
            var obj = {
                columnName:this.conditionField,
                "value":cond
            };

            row[this.conditionField] = obj;
            this.setItemAdapter(key,row);
            this.redraw();
        },
        _clickName : function(key,$el){
            var row = this.store.getItem(key);

            $('#row-key').val(key);

            var $table = $(document.createElement('table'));
            var $tr = $(document.createElement('tr'))
                .append($(document.createElement('th')).html('Field'))
                .append($(document.createElement('th')).html('Value'));
            $table.append($tr);

            for(var x in row){
                var key = row[x].columnName;
                var val = row[x].value;
                var $input = $(document.createElement('input')).attr('id',key).val(val).attr('name',key);
                var $tr = $(document.createElement('tr'))
                    .append($(document.createElement('td')).html(key))
                    .append($(document.createElement('td')).html($input));
                $table.append($tr);
            }

            $('#row-data').empty().html($table);

            var ht = ($(window).height()-50);
            $('#dialog').dialog("option","height",ht).dialog('open');
        },
        _clickColor : function(key,$el){
            var row = this.store.getItem(key);
            var pos = new google.maps.LatLng(row.Y.value,row.X.value);

            this.map.panTo(pos);
            this.marker.setPosition(pos);
        },
        _saveDialog : function(){
            var result = {};
            var data = $('#row-data').serializeArray();
            for(var x in data){
                var name = data[x].name;
                var value= data[x].value;
                result[name] = {columnName:name,"value":value};
            }

            var key = $('#row-key').val();

            try{
                this.setItemAdapter(key,result);
                this.redraw();
                $('#dialog').dialog('close');
            }catch(e){
                alert('Save failed!\n'+ e);
            }
        },
        fusionDialog : function(msg,status){
            var $msg = $('#savingMsg');
            if(status === "close"){
                $msg.empty();
            }else{
                $msg.append($(document.createElement('li')).html(msg));
            }
            $('#saving').dialog(status);
        },
        setItemAdapter : function(key,obj){
            if(this.fusionSave)
                return this._fusionUpdate(key,obj);

            return this.store.setItem(key,obj);
        },
        _fusionUpdate : function(key,obj){
            this.redraw();

            //do the update for fusion table
            var scope = this;

            scope.fusionDialog('Authentication token','open');
            oAuth.getToken(function(token){
                scope.fusionDialog('Row identifier','open');

                //first we need the unique row
                var base = "https://www.googleapis.com/fusiontables/v1/query";
                var qGet = "?sql=SELECT ROWID FROM "+scope.fusionTableId+" WHERE "+
                    scope.nameUnique+"="+obj[scope.nameUnique].value+
                    "&access_token="+token;
                $.ajax({
                    type: "GET",
                    url : base + qGet,
                    beforeSend : function(request){
                        request.setRequestHeader("Authorization", 'Bearer ' + token);
                    }
                })
                .done(function(data){
                    scope.fusionDialog('Update request','open');
                    var update = "sql=UPDATE "+scope.fusionTableId+" SET ";

                    var comma = "";
                    for(var x in scope.nameUpdateColumns){
                        var fname = scope.nameUpdateColumns[x];
                        update += comma+obj[fname].columnName+" = '"+obj[fname].value.replace(/\&/g,'%26')+"'";
                        comma = " , ";
                    }

                    update+=" WHERE ROWID = '"+data.rows[0][0]+"'";
                    $.ajax({
                        type: 'POST',
                        url: base,
                        beforeSend: function (request){
                            request.setRequestHeader("Authorization", 'Bearer ' + token);
                        },
                        data : update
                    }).done(function(data) {
                        scope.fusionDialog('Save success','open');
                        setTimeout(function(){scope.fusionDialog('','close');},1000);

                        //save locally
                        scope.store.setItem(key,obj);
                        scope.redraw();
                    }).fail(function(){
                        scope.fusionDialog('Save failed','open');
                        scioe.fusionDialog('Update query failed');
                    });
                })
                .fail(function(){
                    scope.fusionDialog('Save failed','open');
                    scope.fusionDialog('Could not get ROWID from fusion table','open');
                });
            });
        },
        export : function(){
            var $text = $('#export-data').empty();
            var str = "";
            var obj = this;

            var comma = "";
            for(var x in obj.exportFields){
                str += comma + obj.exportFields[x];
                comma = ", ";
            }
            str += "\n";

            this.store.iterate(function(key, row){
                var comma = "";
                for(var x in obj.exportFields){
                    var name = obj.exportFields[x];
                    if(typeof row[name] == "undefined"){
                        str += comma+"Undefined Field";
                    }else if(typeof row[name] === "object"){
                        var field = row[name].columnName;
                        var value = row[name].value;
                        if(value === ""){
                            str += comma+"No Data";
                        }else{

                            str += comma+value;
                        }
                    }else{
                        str += row[name]+", ";
                    }
                    comma = ", ";
                }

                str += "\n";
            });

            $text.val(str);
            $('#export').dialog('open');
        },
        _geoMarker : null,
        _geoInterval : null,
        startGeoTracking : function(){
            if($('#gpsOn:checked').length > 0){
                if(navigator.geolocation) {
                    var scope = this;

                    scope._geoMarker.setVisible(true);

                    scope.stopGeoTracking();
                    scope._geoInterval = setInterval(function(){
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var pos = new google.maps.LatLng(position.coords.latitude,
                                position.coords.longitude);
                            scope.map.panTo(pos);
                            scope._geoMarker.setCenter(pos);

                        }, function() {
                            alert('Failed to geolocate.');
                        });
                    },500);
                }else{
                    scope._geoMarker.setVisible(false);
                    alert('Device does not support geolocation.');
                }
            }else{
                this.stopGeoTracking();
            }
        },
        stopGeoTracking : function(){
            clearInterval(this.geoInterval);
        }
    }

    var store = new Store('beta3');

    var fusionTableId = "1xPEsfqQOgx8Cne-u2QQ1evWonVCmgvVY0LDcG3k";
    var startloc = new google.maps.LatLng(34.409191, -119.692953);
    function initialize() {
        var map = new google.maps.Map(document.getElementById("map-canvas"),
            {center: startloc,zoom: 18});

        oAuth.scope = 'https://www.googleapis.com/auth/fusiontables';
        oAuth.redirect_uri = 'http://media.transmap.us/oauth2/client.php';
        Display.init($('#sign-data'),store, map);
        App.fusionTableId = fusionTableId;
        App.init(Display,store,map);
        App.redraw();

        layer = new google.maps.FusionTablesLayer({
            query: {select: 'Y',from: fusionTableId},
            styles : [{markerOptions : {iconName : "placemark_circle_highlight"}}],
            suppressInfoWindows : false
        });

        //handle map click
        layer.addListener('click', function(evt){

            var latLng = evt.latLng;
            var d = new Date();

            //just accessing the row content
            var id = evt.row[App.nameUnique].value;
            evt.row['timestamp'] = {
                'columnName':'timestamp',
                'value': d.toISOString()
            };
            evt.row[App.conditionField] = {
                'columnName':App.conditionField,
                'value':'GOOD'
            };

            //remove item if it exists
            var remove = [];
            store.iterate(function(key,obj){
                if(obj[App.nameUnique].value == id){
                    remove.push(key);
                }
            });
            for(var x in remove) store.removeItem(remove[x]);

            //store item and redraw
            App.setItemAdapter(App.index--,evt.row);
            App.redraw();
        });
        layer.setMap(map);


    }
    google.maps.event.addDomListener(window, 'load', initialize);


    $(document).ready(function(){
        //setup pane
        var width = $.cookie('column-width');
        $('#map-canvas').css('margin-right',width);
        $('#sign-results-wrapper').css('width',width);

        //resize pane
        $('#sign-results-wrapper').resizable({
            handles: "w",
            stop: function(evt, ui){
                $('#map-canvas').css('margin-right',ui.size.width+'px');
                $.cookie("column-width",ui.size.width+'px');
                google.maps.event.trigger(map,'resize')
            }
        });

        App.startGeoTracking();
        $('#gpsOn').change(function(){
            App.startGeoTracking();
        });

        $('#delete-local').click(function(){
            var r=confirm("Are you sure want to clear your local storage?");
            if (r==true){
                store.clear();
                App.redraw(1);
            }
        })

        $('#delete-checked').click(function(){
            $('#sign-data input:checked').each(function(){
                var id = $(this).attr('id').split('-');
                store.removeItem((id[1]));
                App.redraw();
            });
        });

        $('#saveFusion').change(function(){
            if($(this).is(':checked')){
                App.fusionSave = true;
                if(oAuth.token == null && $.cookie('gtoken') === null){
                    oAuth.getToken(function(token,expire){
                        $.cookie('gtoken',token);
                        $.cookie('gexpire',expire.toISOString());
                    });
                }else if(oAuth.token == null && $.cookie('gtoken') !== null){
                    var token = $.cookie('gtoken');
                    var expire = new Date($.cookie('gexpire'));
                    var now = new Date();
                    if(expire < now){
                        oAuth.getToken(function(token,expire){
                            $.cookie('gtoken',token);
                            $.cookie('gexpire',expire.toISOString());
                        })
                    }else{
                        oAuth.setToken(token,expire);
                    }
                }else{
                    //do nothing
                }
            }else{
                App.fusionSave = false;
            }
        });
    });

</script>


</html>