<?php

/**
 * This require rewriting is on since it uses the path to load
 */

?>
<!DOCTYPE html>
<html>
<head>

    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>


    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/css/uiselect.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/theme-jquery/jquery-ui-1.9.2.custom/css/custom-theme/jquery-ui-1.9.2.custom.css" type="text/css" media="screen" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="/theme-jquery/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js" ></script>
    <script type="text/javascript" src="/js/jquery.uiselect-lcms.js"></script>
    <script type="text/javascript" src="/js/raphael-min.js"></script>
    <script type="text/javascript" src="/js/preload.js"></script>


    <style>
        #main{
            position:relative;
        }
        #paper{
            overflow:hidden;
            border:solid black 2px;
        }
        #paperControls{
            position: absolute;
            z-index:9999
        }
        #paperControls .zoom{
            cursor:pointer;
            width:20px;
            height:20px;
            background-color:#FFF;
            border:solid black 5px;
            font-size:24px;
        }
        #noProject{
            display: none;
            background-color: #efefef;
            height:300px;
        }
        #noProject form{
            position:relative;
            top:30%;
            text-align: center;
            background-color: #FFF;
            margin: 0px 100px;
            padding:20px;
        }

        .ui-select input, .ui-select span:first-child{
            width:100%;
        }

        /* remove the rounded corners to match UI already in place */
        * {
            -webkit-border-radius: 0 !important;
            -moz-border-radius: 0 !important;
            border-radius: 0 !important;
        }
    </style>

</head>
<body>
<div class="container">
    <div id="nav">
        <div class="row">
            <div class="col-md-4">
                <form>
                    <div class="form-group" id="form-projectId">
                        <label for="projectId">Project ID</label>
                        <select id="projectId"></select>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form>
                    <div class="form-group">
                        <label for="subId">Sub ID</label>
                        <select id="subId"></select>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form>
                    <div class="form-group" id="form-instance">
                        <label for="instance">Instance</label><br/>
                        <input class="form-control" type="text" id="instance"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div id="main" style="display:none">
        <div id="paperControls">
            <div>
                <div id="zoomIn" class="zoom"><b>+</b></div>
                <div id="zoomOut" class="zoom"><b>-</b></div>
            </div>
        </div>
        <div id="paper"></div>
    </div>
    <div id="noProject" style="display: none">
            <p id="noProjectMsg" class="bg-danger" style="display: none"></p>
            <form>
                <div class="form-group" style="max-width:200px;text-align:left">
                    <label for="projectSelector">Choose Project</label>
                    <select id="projectSelector"></select>
                    <a id="noProjectBtn" class="btn btn-default" style="margin-top:5px">Submit</a>
                </div>
            </form>
    </div>

    <script>

    var hashParser = {
        _keyPattern : "[a-z\\d]+",
        _valPattern : "[a-z\\d]+",
        _delim : ":",
        parse : function(){
            var pattern = new RegExp("("+this._keyPattern+"\\"+this._delim+this._valPattern+")+","gi");
            var found = location.hash.match(pattern);

            var result = {};
            for(var x in found){
                var parts = found[x].split(':');
                result[parts[0]] = parts[1];
            }
            return result;
        },
        add : function(key,val){
            this.remove(key);
            var joiner = (location.hash.length > 1) ? '&' : '';
            location.hash += joiner + key + this._delim + val
        },
        get : function(key){
           var all = this.parse();
            for(var x in all){
                if(x === key)
                    return all[x];
            }
            return null;
        },
        remove : function(key){
            var all = this.parse();
            location.hash = "";
            for(var x in all)
                if(x !== key) this.add(x,all[x]);
        }
    }

    var current = null;
    var projects = [];
    var dims = null;

    var info = hashParser.parse();

    var projectDataCache = null;

    var resetProjectIdSelect = function(){
        var projectId = hashParser.get("project");

        $.getJSON("service.php?action=projectData&project="+info.project).done(function(data){
            $('#projectId').empty();

            for(var x in data){
                var selected = (x === projectId) ? 'selected' : null;
                $('#projectId').append('<option data-subs=\''+JSON.stringify(data[x])+'\' value='+x+' '+selected+'>'+x+'</option>');
            }
            $('#projectId').uiselect('refresh');
            hashParser.add('projectId',$('#projectId option:selected').val());
            resetSubIdSelect();
        }).fail(function(jqXHR){
            hashParser.remove('project');
            hashParser.remove('subId');
            hashParser.remove('instance');

            $('#subId').empty().uiselect('refresh');
            $("#instance").val('?????');

            $("#form-projectId span:first").attr('style','border-color:#a94442');

            openErrorDialog('Lookup Failure - '+jqXHR.status,
                'Failed to lookup project data.',
                '<ul><li>Project: '+projectId+'<li>'+jqXHR.responseText,
                function(){$("#form-projectId span:first").attr('style','');}
            );
        });
    };

    var resetSubIdSelect = function(){

        var subId = hashParser.get("subId");
        var data = JSON.parse($('#projectId option:selected').attr('data-subs'));

        $('#subId').empty();
        for(var x in data){
            var selected = (x === subId) ? 'selected' : '';
            $('#subId').append('<option value='+x+' '+selected+'>'+x+'</option>');
        }

        $('#projectId').uiselect('refresh');
        $('#subId').uiselect('refresh');

        hashParser.add('subId',$('#subId option:selected').val());
        resetInstance();
    };

    var data = {};
    var resetInstance = function(){
        var path = hashParser.get('project')+"/"+hashParser.get('projectId')+"/"+hashParser.get('subId');
        if(typeof data[path] === "undefined"){
            $.getJSON("service.php?action=summary&path="+path).done(function(info){
                data[path]=info;

                if(info.min >= 0){
                    $("#instance").val(info.min);
                    hashParser.add('instance',info.min);
                }else
                    $("#instance").val('?????');
            }).fail(function(jqXHR){
                hashParser.remove('instance');
                $("#instance").val('?????');
                $("#form-instance").addClass('has-error');
                openErrorDialog('Lookup Failure - '+jqXHR.status,
                    'Failed to lookup xml documents for given project data.',
                    '<ul><li>Project Path: '+path+'<li>'+jqXHR.responseText,
                    function(){$("#form-instance").removeClass('has-error');}
                );
            });
        }
    }

    var openErrorDialog = function(title,message,detail,cb){
        var d = $('#dialogErr');
        d.dialog("option","title",title);
        $('#dialogErrBody').html(message);
        $('#dialogErrDetail').html(detail);
        d.dialog("open");
        d.on( "dialogclose", cb);
    }

    var init = function(){
        info = hashParser.parse();

        //check if info has been loaded, if not show the choose project form
        if(info == null || !info.project){
            $('#noProjectMsg').html("Please select a project.");
            $('#noProject').slideDown();
            return;
        }

        //we have info in hash tags
        var projectId = hashParser.get('projectId');
        var subId = hashParser.get('subId');

        resetProjectIdSelect();



        var nop = $('#noProject');
        if(nop.is(":visible")) nop.slideUp();

        $('#main').slideDown();

    }

    $(document).ready(function(){
        //setup the drop downs
        $("select").uiselect();

        //get the document dimensions
        $.getJSON("service.php?action=projects")
            .done(function(data){
                for(var p in data){
                    projects.push(p);
                    $('#projectSelector').append('<option value='+p+'>'+p+'</option>');
                }
                $('#projectSelector').uiselect('refresh');
            });

        $('#noProjectBtn').click(function(){
            location.hash = 'project:'+$('#projectSelector option:selected').text();
            init();
        });

        //bind drop downs
        $('#projectId').change(function(){
            hashParser.add('projectId',$('#projectId option:selected').text());
            hashParser.remove('subId');
            resetSubIdSelect();
        });

        $('#subId').change(function(){
            hashParser.add('subId',$('#subId option:selected').text());
            hashParser.remove('instance');
            resetInstance();
        });

        //only allow digits for input box
        $('#instance').keypress(function(e){
            var a = [];
            var k = e.which;

            for (var i = 48; i < 58; i++){
                a.push(i);
            }

            if (!(a.indexOf(k)>=0)){
                e.preventDefault();
            }
        });

        $("#dialogErr").dialog({
            autoOpen:false, width:500, modal:true
        });

        if(info == null || !info.project){
            $('#noProject').slideDown();
            return;
        }
        init();
    });



    var height = 416;
    var width =  1000;

    /*
    var paper = Raphael("paper", width, height);

    paper.image('http://media.transmap.local/media2/images/lcms/images/image.php?path=Osceola/010315/2/000000&maxWidth=1000',0,0,width,height);

    paper.setViewBox(0, 0, width, height );

    // Setting preserveAspectRatio to 'none' lets you stretch the SVG
    paper.canvas.setAttribute('preserveAspectRatio', 'none');

    $('#zoomIn').click(function(){
        width = width - 100;
        height = height - 100;
        console.log("hello",width,height);
        paper.setViewBox(0,0,width,height,false);
    });

    $('#zoomOut').click(function(){
        width = width + 100;
        height = height + 100;
        console.log("hello",width,height);
        //$('#paper').attr('width', width).attr('height', height);
        paper.setViewBox(0,0,width,height,false);
    });

    for(var x in cracks){
        //need a closure
        (function(data){
            var path = paper.path(data.path);
            path.attr("stroke-width", "10");
            path.attr("opacity",0);
            path.data("with",data.width);
            path.data("height",data.height);

            path.hover(function(){
                this.g = path.glow({
                        color: '#ff0',
                        width: 15
                    });
            },function(){
                this.g.remove();
            });
            path.click(function(){
                alert('width: '+data.width+"\ndepth: "+data.depth);
            });
        })(cracks[x]);
    }
    */
    </script>

    <div id="dialogErr" title="Error" style="display: none">
        <p id="dialogErrBody"></p>
        <p id="dialogErrDetail" class="bg-danger"></p>
    </div>
</div>
</body>