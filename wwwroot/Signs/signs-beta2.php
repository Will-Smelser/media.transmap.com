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
        .good{
            background-image:url("/images/layout/icons/accept.png");
        }
        .bad{
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
    /**
     * The gapi from google can do all this, but it is a little more complicated and requires the
     * apiKey, which seems like a bad idea.
     */
    var oAuth = {
        client_id : '16942626072-oqu5fdjnaed93hua437avv7k5skb5jgl.apps.googleusercontent.com',
        redirect_uri : 'http://media.transmap.com/oauth2/client.php',
        scope : 'https://www.googleapis.com/auth/fusiontables',
        token : null, expires : null, win : null,
        winHeight : 300, winWidth : 500,
        setToken : function(token, expires){
            this.token = token;
            this.expires = expires;
        },
        getToken : function(callback){
            var now = new Date();
            if(this.token !== null && now < this.expires){
                callback(this.token);
                return;
            }

            var obj = this;
            this.makeRequest(this.client_id,this.redirect_uri,this.scope);
            this.onLoad(function(parsedUrl){
                obj.token = parsedUrl['access_token'];
                obj.expires = new Date(now.getTime() + parsedUrl['expires_in']*60000);
                callback(obj.token,obj.expires);
            });
        },
        getUrl : function(){
            return this.win.document.location.href;
        },
        parseUrl : function(url){
            var result = {}, queryString = this.win.location.hash.substring(1),
                regex = /([^&=]+)=([^&]*)/g, m;
            while (m = regex.exec(queryString)) {
                result[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
            }
            return result;
        },
        checkForToken : function(){
            return (this.getUrl().indexOf('#access_token=')<0)?false:true;
        },
        makeRequest : function(client_id, redirect, scope){
            var request = 'https://accounts.google.com/o/oauth2/auth?'+
            'client_id='+client_id+'&'+'redirect_uri='+redirect+'&'+
            'scope='+scope+'&'+'response_type=token';
            this.win = window.open(request,"",'left=100,top=100,width='+this.winWidth+",height="+this.winHeight);
        },
        onLoad : function(callback){
           if(this.getUrl().indexOf(this.redirect_uri)<0){
               var obj = this;
               setTimeout(function(){obj.onLoad.call(obj,callback);},1500);
               return;
           }
           callback(this.parseUrl(this.getUrl()));
           this.win.close();
        }
    };

    var store = {
        fusionSave : false,
        fusionTableId : null,
        nameUnique : 'SignID',
        nameUpdateColumns : ["Roadname","MUTCD","SIGN_FACE_","CONDITION","POST_TYPE","X","Y","Night_Insp","IMAGE_LINK","Inspection_Flag","Label","Insp_Comment","timestamp"],
        nameColumns : ['MUTCD','SIGN_FACE_'],
        conditionField : 'Night_Insp',
        conditionToggle : ['GOOD','POOR'],
        conditionClass : ['good','bad'],
        conditionColor : ['green','red'],
        exportFields : ['SignID','Night_Insp','Insp_Comment','timestamp'],
        storage : localStorage,
        $display : $('#sign-data'),
        _check : function(){
            try {
                return 'localStorage' in window && window['localStorage'] !== null;
            } catch (e) {
                return false;
            }
        },
        jsonToStr : function(obj){
            return JSON.stringify(obj);
        },
        /* set entry in localStorage */
        setItem : function(key,obj){

            //dont save
            if(this.fusionSave){

                //do the update for fusion table
                var scope = this;

                scope.fusionDialog('Authentication token','open');
                oAuth.getToken(function(token){
                    scope.fusionDialog('Row identifier','open');

                    //first we need the unique row
                    var base = "https://www.googleapis.com/fusiontables/v1/query";
                    var qGet = "?sql=SELECT ROWID FROM "+fusionTableId+" WHERE "+
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
                            scope.storage.setItem(key,scope.jsonToStr(obj));
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
            }else{
                //store locally
                return this.storage.setItem(key,this.jsonToStr(obj));
            }
        },
        /* update the attribute of an item in localStorage */
        setAttribute : function(key, attr, value){
            var obj = this.getItem(key);
            obj[attr] = value;
            this.setItem(key,obj);
        },
        /* get an item */
        getItem : function(key){
            return JSON.parse(this.storage.getItem(key));
        },
        removeItem : function(key){
            this.storage.removeItem(key);
        },

        showDialog : function(key){
            row = this.getItem(key);

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
        saveDialog : function(){
            var result = {};
            var data = $('#row-data').serializeArray();
            for(var x in data){
                var name = data[x].name;
                var value= data[x].value;
                result[name] = {columnName:name,"value":value};
            }

            var key = $('#row-key').val();

            try{
                this.setItem(key,result);
                this.redraw();
                $('#dialog').dialog('close');
            }catch(e){
                alert('Save failed!\n'+ e);
            }
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

            this.iterate(function(key, row){
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

        //track click index
        index : 99999999999,

        //pagination
        curPage : 1,
        pageSize : 10,

        //initialize
        init : function(map, marker, fusionTableId){
            var scope = this;

            scope.fusionTableId = fusionTableId;

            if(this.storage.length > 0)
                scope.index = this.storage.key(0);

            scope.map = map;
            scope.marker = marker;

            var size = Math.ceil(scope.storage.length/scope.pageSize);
            $('.pagination').jqPagination({
                current_page:scope.curPage,
                max_page:Math.max(1,size),
                paged: function(page){
                    store.curPage=page;
                    store.redraw(page);
                }
            });

            scope.startGeoTracking();
            $('#gpsOn').change(function(){
                scope.startGeoTracking();
            });

            //set the dialog
            var ht = ($(window).height()-50);
            $('#dialog').dialog({
                title:'Point Data',autoOpen:false,
                width:400,height:ht
            });
            $('#row-data-btn').button().click(function(){scope.saveDialog.call(scope);});

            $('#export').dialog({
                title:'Result Data',autoOpen:false,
                width:'90%',height:ht
            });
            $('#export-btn').button().click(function(){scope.export.call(scope);});

            //precreate the markers
            var colors = [
                '#00ffff','#000000','#0000ff','#ff00ff','#008000','#00ff00','#800000',
                '#000080','#808000','#ffa500','#800080','#ff0000','#c0c0c0','#008080',
                '#ffff00'
            ];
            var path = {
                path: 'M-10 -10 L-10 10 L10 10 L10 -10 Z',
                fillColor: 'yellow',
                fillOpacity: 0.7,
                scale: 1,
                strokeColor: '#333',
                strokeWeight: 4
            };

            for(var i= 0,c=0; i< scope.pageSize; i++, c++){
                if(c > colors.length) c = 0;
                var lpath = $.extend(null,path);
                lpath.fillColor = colors[c];
                var marker = new google.maps.Marker({
                    map: map,
                    title: 'Visited Sign',
                    icon:lpath
                });
                google.maps.event.addListener(marker, 'click', function(e) {
                    //alert('clicked');
                });
                scope.markerVisited.push(marker);
            }

            scope.geoMarker = cityCircle = new google.maps.Circle(
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
            scope.redraw(1);

            //save dialog
            $('#saving').dialog({
                title:'Saving to Fusion Tables',
                autoOpen:false,
                width:500,height:300
            });
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
        geoMarker : null,
        geoInterval : null,
        startGeoTracking : function(){
            if($('#gpsOn:checked').length > 0){
                if(navigator.geolocation) {
                    var scope = this;

                    scope.geoMarker.setVisible(true);

                    scope.stopGeoTracking();
                    scope.geoInterval = setInterval(function(){
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var pos = new google.maps.LatLng(position.coords.latitude,
                                    position.coords.longitude);
                            scope.map.setCenter(pos);
                            scope.geoMarker.setCenter(pos);

                        }, function() {
                            alert('Failed to geolocate.');
                        });
                    },500);
                }else{
                    scope.geoMarker.setVisible(false);
                    alert('Device does not support geolocation.');
                }
            }
        },
        stopGeoTracking : function(){
            clearInterval(this.geoInterval);
        },
        /* iterrates in reverse order */
        iterate : function(func, start, stop){
            if(typeof start == 'undefined')
                start = 0;
            if(typeof stop == 'undefined' || stop == 0)
                stop = this.storage.length;
            if(stop > this.storage.length)
                stop = this.storage.length;

            for(var i=start; i < stop; i++){
                var key = this.storage.key(i);
                func(key,this.getItem(key));
            }
        },
        _createRow : function(key, name, row){
            var scope = this;

            var $cbox = $(document.createElement('input')).attr('type','checkbox').attr('id','key-'+key);
            var $div = $(document.createElement('div'));

            var $box = $(document.createElement('div')).addClass('color-key');

            var $label = $(document.createElement('div')).addClass('name');//.attr('for','key-'+key)
            var $li = $(document.createElement('li')).attr('data-key',key);

            var condition = row[this.conditionField].value;
            var imgClass = scope._getCurrentConditionClass(condition);
            var $img = $(document.createElement('span')).addClass('img-base').addClass(imgClass);


            var $nameWrap = $(document.createElement('span')).html(name);
            $nameWrap.click(function(){scope.showDialog(key,row);});

            $label.append($cbox).append($box).append($nameWrap).append($img);
            $div.append($label);
            $li.append($div);

            //add color key event
            $box.click(function(){
                var key = $(this).parent().parent().parent().attr('data-key');
                var row = scope.getItem(key);
                var pos = new google.maps.LatLng(row.Y.value,row.X.value);
                scope.map.setCenter(pos);
                scope.marker.setPosition(pos);
            });

            //add img event
            $img.click(function(){
                var key = $(this).parent().parent().parent().attr('data-key');
                var obj = {
                    columnName:scope.conditionField,
                    "value":scope._getNextCondition.call(scope, condition)
                };
                scope.setAttribute(key,scope.conditionField,obj);
                scope.redraw();
            });

            return $li;
        },
        _getNextConditionIndex:function(currentCondition){
            var next = 0;
            for(var x in this.conditionToggle){
                if(this.conditionToggle[x] == currentCondition){
                    var temp = parseInt(x);
                    next = (temp+1 >= this.conditionToggle.length) ? 0 : temp+1;
                    break;
                }
            }
            console.log("got index:",next,currentCondition);
            return next;
        },
        _getCurrentConditionIndex : function(currentCondition){
            for(var x in this.conditionToggle)
                if(this.conditionToggle[x] == currentCondition)
                    return x;
            return 0;
        },
        _getCurrentConditionClass : function(currentCondition){
            return this.conditionClass[this._getCurrentConditionIndex(currentCondition)];
        },
        _getNextConditionClass : function(currentCondition){
            return this.conditionClass[this._getNextConditionIndex(currentCondition)];
        },
        _getNextCondition:function(currentCondition){
            return this.conditionToggle[this._getNextConditionIndex(currentCondition)];
        },
        _getConditionColor : function(currentCondition){
            return this.conditionColor[this._getCurrentConditionIndex(currentCondition)];
        },
        deleteIfExists : function(attr,value){
            var scope = this;
            this.iterate(function(key,row){
                if(row != null && typeof row[attr] != "undefined" && value === row[attr])
                    scope.removeItem(key);
            });
        },
        checkIfExists : function(attr,value){
            var scope = this;
            var result = false;
            this.iterate(function(key,row){
                if(row != null && typeof row[attr] != "undefined" && value === row[attr].value){
                    result = true;
                    return;
                }
            });
            return result;
        },
        redraw : function(page){
            if(typeof page == "undefined")
                page = this.curPage;

            this.$display.empty();

            //hide all markers
            for(var x in this.markerVisited)
                this.markerVisited[x].setVisible(false);

            var obj = this;

            var counter = 0;
            var start = obj.pageSize * (page-1);
            var stop = start + obj.pageSize;
            this.iterate(function(key,row){
                var str = "";
                for(var i in obj.nameColumns)
                    str += row[obj.nameColumns[i]].value + "&nbsp;&nbsp;";

                var div = obj._createRow.call(obj, key, str, row);
                obj.$display.append(div);

                var pos = new google.maps.LatLng(row.Y.value,row.X.value);
                obj.markerVisited[counter].setPosition(pos);
                obj.markerVisited[counter].setVisible(true);
                var icon = obj.markerVisited[counter].getIcon();
                icon.strokeColor = obj._getConditionColor(row[obj.conditionField].value);
                obj.markerVisited[counter].setIcon(icon);

                var color = obj.markerVisited[counter].getIcon().fillColor;
                div.find('.color-key').attr('style','background-color:'+color);

                counter++;
            },start,stop);
        },
        locMarker : null,
        marker : null,
        markerVisited : [],
        map : null
    }



    var fusionTableId = "1xPEsfqQOgx8Cne-u2QQ1evWonVCmgvVY0LDcG3k";//"1j0WCPS_KacasKJiCSsB2hqpfXO5ouldt_X0Om-Q";
    var startloc = new google.maps.LatLng(34.409191, -119.692953);
    function initialize() {
        var mapOptions = {
            center: startloc,
            zoom: 18
        };

        window.map = new google.maps.Map(document.getElementById("map-canvas"),
                mapOptions);

        layer = new google.maps.FusionTablesLayer({
            query: {
                select: 'Y',
                //from: '1mZ53Z70NsChnBMm-qEYmSDOvLXgrreLTkQUvvg'//google example
                //from: '1AX2G1_UCWwnbuNNBfzAhwfD0VptlKHPXvMVC3o4'//origional
                from: fusionTableId//new entire SB dataset
            },
            styles : [
                {
                    markerOptions : {
                        iconName : "placemark_circle_highlight"
                    }
                }
            ],
            suppressInfoWindows : false
        });

        layer.addListener('click', function(evt){
            var latLng = evt.latLng;

            var d = new Date();

            //just accessing the row content
            var id = evt.row[store.nameUnique].value;
            evt.row['timestamp'] = {
                'columnName':'timestamp',
                'value': d.toISOString()
            };
            evt.row[store.conditionField] = {
                'columnName':store.conditionField,
                'value':store.conditionToggle[0]
            };

            if(!store.checkIfExists(store.nameUnique,id)){
                store.setItem(--store.index,evt.row);
                store.redraw();
            }

            var size = Math.max(1,Math.ceil(store.storage.length/store.pageSize));
            $('.pagination').jqPagination('option', 'max_page', size);

            store.marker.setPosition(latLng);
        });
        layer.setMap(map);

        var marker = new google.maps.Marker({
            position: startloc,
            map: map,
            title: 'Current Sign'
        });

        store.init(map, marker, fusionTableId);


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

        $('#delete-local').click(function(){
            var r=confirm("Are you sure want to clear your local storage?");
            if (r==true){
                localStorage.clear();
                var size = Math.ceil(store.storage.length/store.pageSize);
                $('.pagination').jqPagination('option', 'max_page', size);
                store.redraw(0);
            }
        })

        $('#delete-checked').click(function(){
            $('#sign-data input:checked').each(function(){
                var id = $(this).attr('id').split('-');
                store.removeItem((id[1]));

                var size = Math.max(1,Math.ceil(store.storage.length/store.pageSize));
                $('.pagination').jqPagination('option', 'max_page', size);

                store.redraw();
            });
        });

        $('#saveFusion').change(function(){
            if($(this).is(':checked')){
                store.fusionSave = true;
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
                store.fusionSave = false;
            }
        });
    });

</script>


</html>