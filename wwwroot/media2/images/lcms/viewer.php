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

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="/theme-jquery/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js" ></script>
    <script src="/js/jquery.uiselect.js"></script>
    <script type="text/javascript" src="/js/raphael-min.js"></script>


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
    </style>

</head>
<body>
<div class="container">
    <div id="nav">
        <div class="row">
            <div class="col-md-4">
                <form>
                    <div class="form-group">
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
                    <div class="form-group">
                        <label for="instance">Instance</label><br/>
                        <input type="text" id="instance"/>
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

        //look project name information up: project subId and records
        $.getJSON("service.php?action=projectData&project="+info.project).done(function(data){
            //clear everything
            $('#projectId').empty().uiselect('refresh');
            $('#subId').empty().uiselect('refresh');

            for(var x in data){
                var selected = (x === projectId) ? 'selected' : null;
                $('#projectId').append('<option value='+x+' '+selected+'>'+x+'</option>');

                for(var y in data[x]){
                    var selected = (y === subId) ? 'selected' : null;
                    $('#subId').append('<option value='+y+' '+selected+'>'+y+'</option>');
                }
            }
            $('#projectId').uiselect('refresh');
            $('#subId').uiselect('refresh');

            projectId = $('#projectId option:selected').text()
            subId = $('#subId option:selected').text()

            hashParser.add('projectId',projectId);
            hashParser.add('subId',subId);

            //have to set the instance
            $.getJSON("service.php?action=xml&path="+info.project+"/"+projectId+"/"+subId)
            .done(function(xml){
                console.log(xml);
            });

            //we want to load the map
        });


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
            //var subData =
        });
        $('#subId').change(function(){
            hashParser.add('subId',$('#subId option:selected').text());
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

</div>
</body>