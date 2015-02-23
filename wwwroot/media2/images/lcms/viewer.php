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
        .paper{
            overflow:hidden;
            border:solid black 2px;
            margin: 0px auto;
        }
        .data-container{
            margin:0px auto;
        }
        .data-container .data-body{
            max-height: 300px;
            overflow-y: auto;
        }
        .data-container .data-body TR:first-child TD, .data-container .data-body TR:first-child TH{
            border-top:none; !important
        }
        .data-container .data-body TR{
            cursor: pointer;
        }
        .data-container .data-head table, .data-container .data-head tr{
            margin-bottom: 0px;
            padding-bottom:0px;
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
        #goBtn{
            position:absolute;right:0px;top:0px;
        }
        .paper-nav{
            position:absolute;
            top:0px;
            z-index: 1;
        }
        .paper-nav div{
            position: absolute;
            left:20px;
            width:20px;
            height:20px;
            border:solid #333 2px;
            background-color: #FFF;
            cursor: pointer;
        }
        .paper-nav .plus{
            top:20px;
        }
        .paper-nav .minus{
            top:50px;
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
                        <div style="padding-right: 60px; position: relative;">
                            <input class="form-control" type="text" id="instance"/>
                            <button id="goBtn" type="button" class="btn btn-primary">Go</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div id="main" style="display:none">
        <div class="wrapper current">
            <div class="paper"></div>
            <div class="data-container">
                <div class="data-head"></div>
                <div class="data-body"></div>
            </div>
        </div>
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



    var info = hashParser.parse();
    var MAIN_WIDTH = 800;

    var createPath = function(){
        return hashParser.get('project')+"/"+hashParser.get('projectId')+"/"+hashParser.get('subId');
    }

    var createTableHeader = function(){

        var $table = $(document.createElement('table')).attr('class','table table-hover');
        var $thead = $(document.createElement('thead'));
        var $tbody = $(document.createElement('tbody'));
        var $row   = $(document.createElement('tr'));
        var $th    = $(document.createElement('th'));

        var header = $row
            .append($th.clone().html('ID'))
            .append($th.clone().html('Width'))
            .append($th.clone().html('Depth'));

        $thead.append(header);

        $table.append($thead).append($tbody);

        return $table;
    }

    var createTableBody = function(){
        var $table = $(document.createElement('table')).attr('class','table table-hover');
        var $tbody = $(document.createElement('tbody'));

        return $table.append($tbody);
    }

    var showLoading = function($target){
        $target.find('.paper:first').slideUp();
        $target.find('.data-container:first').slideUp();
    }

    var showLoadingComplete = function($target){
        $target.find('.paper:first').slideDown();
        $target.find('.data-container:first').slideDown();
    }

    var loadViewerError = function(){

        var $current = $('.current');

        //remove the data
        var $dataContainer = $current.find('.data-container').slideUp().empty().width(MAIN_WIDTH);

        //create the default error image
        var path = createPath()+'/'+hashParser.get('instance');
        var $target = $current.find('.paper').empty();
        var paper = Raphael($target.get(0), MAIN_WIDTH, height);
        paper.image('images/image.php?path='+path+'&maxWidth='+MAIN_WIDTH,0,0,MAIN_WIDTH,height);

        var $table = createTableHeader();
        var $tr = $(document.createElement('tr'));
        var $td = $(document.createElement('td')).attr('colspan','3').attr('style','text-align:center').text('No Data');
        $table.find('tbody').append($tr.append($td));

        $dataContainer.append($table).slideDown();
    }

    //initializes the project id selector
    var resetProjectIdSelect = function(project){
        var projectId = hashParser.get("projectId");

        $.getJSON("service.php?action=projectData&project="+project).done(function(data){
            $('#projectId').empty();

            for(var x in data){
                var selected = (x === projectId) ? 'selected' : null;
                $('#projectId').append('<option data-subs=\''+JSON.stringify(data[x])+'\' value='+x+' '+selected+'>'+x+'</option>');
            }
            $('#projectId').uiselect('refresh');
            hashParser.add('projectId',$('#projectId option:selected').val());
            resetSubIdSelect();
        }).fail(function(jqXHR){
            //cleanup the url

            hashParser.remove('project');
            hashParser.remove('subId');
            hashParser.remove('instance');

            //reset downstream inputs
            $('#subId').empty().uiselect('refresh');
            $("#instance").val('?????');

            //show the error location
            $("#form-projectId span:first").attr('style','border-color:#a94442');

            //give feedback to user about the error
            openErrorDialog('Lookup Failure - '+jqXHR.status,
                'Failed to lookup project data.',
                '<ul><li>Project: '+projectId+'<li>'+jqXHR.responseText,
                function(){
                    $("#form-projectId span:first").attr('style','');
                    $('#main').slideUp();
                    $('#noProject').slideDown();
                }
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

    var resetInstance = function(){
        var path = createPath();
        $.getJSON("service.php?action=summary&path="+path).done(function(info){
            var instance = hashParser.get('instance');

            if(instance > info.min && instance < info.max){
                $("#instance").val(instance);
            }else if(info.min >= 0){
                $("#instance").val(info.min);
                hashParser.add('instance',info.min);
            }else
                $("#instance").val('?????');

            $('#goBtn').trigger('click');

        }).fail(function(jqXHR){
            hashParser.remove('instance');
            $("#instance").val('?????');
            $("#form-instance").addClass('has-error');

            loadViewerError();

            openErrorDialog('Lookup Failure - '+jqXHR.status,
                'Failed to lookup xml documents for given project data.',
                '<ul><li>Project Path: '+path+'<li>'+jqXHR.responseText,
                function(){$("#form-instance").removeClass('has-error');}
            );
        });
    }

    var drawNavPanel = function(paper){
        var width = paper.width;
        var height = paper.height;
        var initWidth = width;
        var initHeight = height;

        var $svg = $(paper.canvas);

        var $cloned = $('.paper-nav.super').clone().removeClass('super');
        $svg.before($cloned);

        //also add the div data element
        $svg.after('<div class="data-raw"></div>')

        $cloned.show();

        var zoom = function(dir){
            width = width - 100 * dir;
            height = height - 100 * dir;

            //reset and sow error
            if(width <= 0 || height <= 0){
                paper.setViewBox(0,0,initWidth,initHeight,false);
                width = initWidth;
                height = initHeight;
                openErrorDialog('Cannot Zoom','Zoom limits reached.','',function(){console.log('no op');});
            }
            paper.myWidth = width;
            paper.myHeight = height;
            paper.setViewBox(0,0,width,height,false);
        }

        //bind clicks
        $cloned.find('.plus').click(function(){zoom(1.0); });
        $cloned.find('.minus').click(function(){zoom(-1.0);});
    }

    var drawPath = function(paper,pathData, $row){
        var path = paper.path(pathData.path);
        path.attr("stroke-width", "10");
        path.attr("opacity",0);
        path.data("with",pathData.width);
        path.data("height",pathData.height);
        path.id = 'path-'+pathData.id;

        path.hover(function(){
            this.g = path.glow({
                color: '#ff0',
                width: 15
            });
        },function(){
            this.g.remove();
        });
        path.click(function(){
            if(paper.myglow)
                paper.myglow.remove();

            paper.myglow = path.glow({
                color: '#ff0',
                width: 15
            });

            var $container = $(paper.canvas.parentElement).parent();
            $container.find('tr').removeClass('success');
            $row.addClass('success');

            //scroll into view
            var dataContainer = $container.find('.data-container .data-body');



            dataContainer.scrollTop(0);


            var containerTop = dataContainer.offset().top;
            var rowTop = $row.offset().top;
            var diff = rowTop-containerTop;

            dataContainer.scrollTop(diff);
        });
    };

    var addData = function(pathData,paper){
        var $row = $(document.createElement('tr'));
        var $td  = $(document.createElement('td'));
        var $th  = $(document.createElement('th'));

        $row
            .append($th.clone().html(pathData.id))
            .append($td.clone().html(pathData.width))
            .append($td.clone().html(pathData.depth));

        (function(id,paper){
            $row.click(function(){

                $(paper.canvas.parentElement).parent().find('tr').removeClass('success');
                $row.addClass('success');

                if(paper.myglow)
                    paper.myglow.remove();

                var path = paper.getById('path-'+id);
                paper.myglow = path.glow({
                    color: '#ff0',
                    width: 15
                });

                window.paper = paper;
            });
        })(pathData.id,paper);

        return $row;
    }

    var loadLcms = function(number,$target){

        var $paper = $target.find('.paper:first').width(MAIN_WIDTH);

        showLoading($target);

        var path = hashParser.get('project')+'/'+hashParser.get('projectId')+'/'+hashParser.get('subId');

        $.getJSON("service.php?action=dims&path="+path).done(function(info){
            //x and y are reversed because the images have to get rotated
            var x = info[1];
            var y = info[0];

            //ratio is the actual image size to the window width
            var ratio  = (MAIN_WIDTH * 1.0) / (x * 1.0);
            var height = ratio * y;

            path=path+"/"+number;

            $.getJSON("data.php?path="+path+'&ratio='+ratio).done(function(cracks){
                $paper.empty();

                var paper = Raphael($paper.get(0), MAIN_WIDTH, height);
                paper.image('images/image.php?path='+path+'&maxWidth='+MAIN_WIDTH,0,0,MAIN_WIDTH,height);

                drawNavPanel(paper);
                //$paper.animate({height:height});

                var $table = createTableHeader();
                var $tableBody = createTableBody();
                var $tbody = $tableBody.find('tbody');

                var $container = $target.find('.data-container').width(MAIN_WIDTH);
                $container.find('table').remove();
                $container.find('.data-head:first').append($table);
                $container.find('.data-body:first').append($tableBody);

                for(var x in cracks){
                    (function(data){
                        var row = addData(data,paper);
                        $tbody.append(row);
                        drawPath(paper,data,row);
                    })(cracks[x]);
                }

                showLoadingComplete($target);

                //add the drag functionality
                var $mTarget = $target.find('.paper:first');
                var $mTargetOffset = $mTarget.offset();
                var down = false;
                var mTargetX = $mTargetOffset.top;
                var mTargetY = $mTargetOffset.left;
                $mTarget.mousedown(function(evt) {
                    down = true;
                    $(this).mousemove(function(dragEvt){
                        if(down === true){
                            if(paper.myWidth)
                                paper.setViewBox(evt.pageX-dragEvt.pageX, evt.pageY- dragEvt.pageY, paper.myWidth, paper.myHeight, false);
                            else
                                paper.setViewBox(evt.pageX-dragEvt.pageX, evt.pageY- dragEvt.pageY, paper.width, paper.height, false);
                        }
                    });
                }).mouseup(function(){
                    down = false;
                });
            }).fail(function(jqXHR){
                loadViewerError();
            });
        }).fail(function(jqXHR){
            loadViewerError();
        });

    };

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
        var project = hashParser.get('project');
        var projectId = hashParser.get('projectId');
        var subId = hashParser.get('subId');

        resetProjectIdSelect(project);



        var nop = $('#noProject');
        if(nop.is(":visible")) nop.slideUp();

        $('#main').slideDown();

    }

    $(document).ready(function(){
        //setup the drop downs
        $("select").uiselect();

        //set the width
        $('#paper').width(MAIN_WIDTH);

        //get the document dimensions
        $.getJSON("service.php?action=projects")
            .done(function(data){
                for(var p in data){
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

        $('#goBtn').click(function(){
            hashParser.add('instance',$('#instance').val());
            loadLcms($('#instance').val(),$('.current'));
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

    </script>

    <div id="dialogErr" title="Error" style="display: none">
        <p id="dialogErrBody"></p>
        <p id="dialogErrDetail" class="bg-danger"></p>
    </div>

    <div class="paper-nav super" style="display: none">
        <div class="plus ui-icon ui-icon-plus"></div>
        <div class="minus ui-icon ui-icon-minus"></div>
        </table>
    </div>
</div>
</body>