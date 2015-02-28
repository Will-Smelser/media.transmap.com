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
            width:100%;
            overflow:hidden;
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
        .paper{
            overflow: hidden;
            margin: 0px auto;
        }
        .paper svg{
            border-top: solid 2px;
            border-bottom: solid 2px;
        }
        .current .paper svg{
            border: solid 2px;
        }
        .current .paper .paper-nav, .current .paper .paper-nav-right{
            visibility: visible;
        }
        .paper-nav{
            position:absolute;
            top:0px;
            z-index: 1;
            visibility: hidden;
        }
        .paper-nav div{
            position:absolute;
            top:0px;
            left:20px;
            border:solid #333 2px;
            background-color: #FFF;
            cursor: pointer;
            width:20px;
            height:20px;
        }
        .paper-nav .plus{
            top:20px;
        }
        .paper-nav .minus{
            top:50px;
        }

        .paper-nav-right{
            position:absolute;
            top:0px;
            visibility: hidden;
        }
        .paper-nav-right .ext-link{
            position:absolute;
            top:20px;
            right:20px;
            width:20px;
            height:20px;
            border:solid #333 2px;
            background-color: #FFF;
            cursor: pointer;
            z-index:2;
        }

        .front{
            z-index:99;
        }
        .wrapper{
            margin:0px auto;
            padding:0px;
            position:relative;
            min-height:400px;
        }
        .current{
            position:relative;
        }
        .current div.box{
            position:relative;
        }
        .before{
            position:absolute;
            top:0px;
            left:-800px;
            width:800px;
            height:400px;
        }
        .after{
            position:absolute;
            top:0px;
            left:800px;
            width:800px;
            height:400px;
        }
        .box{
            position: absolute;
            top:0px;
        }

        #moveRight, #moveLeft{
            position:fixed;
            top:0px;
            bottom:0px;
            width:50px;
            background-repeat: no-repeat;
            background-position: center center;
            background-color: #efefef;
            opacity: .5;
            cursor: pointer;
            z-index:100;
        }
        #moveRight:hover, #moveLeft:hover{
            opacity: .85;
        }
        #moveLeft{
            left:0px;
            background-image: url(images/arrow_left_grey.png);
        }
        #moveRight{
            right:0px;
            background-image: url(images/arrow_right_grey.png);
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
                    <div class="form-group" id="form-projectDate">
                        <label for="projectDate">Date</label>
                        <select id="projectDate"></select>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form>
                    <div class="form-group">
                        <label for="session">session</label>
                        <select id="session"></select>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form>
                    <div class="form-group" id="form-instance">
                        <label id="image-label" for="image">Image</label><br/>
                        <div style="padding-right: 60px; position: relative;">
                            <input class="form-control" type="number" step="1" id="image"/>
                            <button id="goBtn" type="button" class="btn btn-primary">Go</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div>
    <div id="main" class="container-fluid" style="display:none">
        <div class="wrapper">
            <div class="before">
                <div class="box">
                    <div class="paper"></div>
                    <div class="data-container">
                        <div class="data-head"></div>
                        <div class="data-body"></div>
                    </div>
                </div>
            </div>
            <div class="current">
                <div class="box">
                    <div class="paper"></div>
                    <div class="data-container">
                        <div class="data-head"></div>
                        <div class="data-body"></div>
                    </div>
                </div>
            </div>
            <div class="after">
                <div class="box">
                    <div class="paper"></div>
                    <div class="data-container">
                        <div class="data-head"></div>
                        <div class="data-body"></div>
                    </div>
                </div>
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

    <div id="moveRight" style="display: none"></div>
    <div id="moveLeft" style="display: none"></div>

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
        return hashParser.get('project')+"/"+hashParser.get('projectDate')+"/"+hashParser.get('session');
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

    var showLoadingComplete = function($target, isCurrent){
        $target.find('.paper:first').slideDown();

        if(isCurrent)
            $target.find('.data-container:first').slideDown(
                function(){$('.wrapper').slideDown();}
            );
        else
            $('.wrapper').slideDown();
    }

    var loadViewerError = function($target){

        //remove the data
        var $dataContainer = $target.find('.data-container').slideUp().width(MAIN_WIDTH);

        //empty the container data content
        $dataContainer.find('.data-head:first').empty();
        $dataContainer.find('.data-body:first').empty();

        //create the default error image
        var path = createPath()+'/'+hashParser.get('image');
        var $paper = $target.find('.paper').empty();
        var height = 300;
        var paper = Raphael($paper.get(0), MAIN_WIDTH, height);
        paper.image('images/image.php?path='+path+'&maxWidth='+MAIN_WIDTH,0,0,MAIN_WIDTH,height);

        var $table = createTableHeader();
        var $tr = $(document.createElement('tr'));
        var $td = $(document.createElement('td')).attr('colspan','3').attr('style','text-align:center').text('No Data');
        $table.find('tbody').append($tr.append($td));

        $dataContainer.find('.data-head:first').append($table);
        $dataContainer.slideDown();

    }

    //initializes the project id selector
    var resetProjectDateSelect = function(project){
        var projectDate = hashParser.get("projectDate");

        $.getJSON("service.php?action=projectData&project="+project).done(function(data){
            $('#projectDate').empty();

            for(var x in data){
                var selected = (x === projectDate) ? 'selected' : null;
                $('#projectDate').append('<option data-subs=\''+JSON.stringify(data[x])+'\' value='+x+' '+selected+'>'+
                    prettyDate(x)+'</option>');
            }
            $('#projectDate').uiselect('refresh');
            hashParser.add('projectDate',$('#projectDate option:selected').val());
            resetSessionSelect();
        }).fail(function(jqXHR){
            //cleanup the url

            hashParser.remove('project');
            hashParser.remove('session');
            hashParser.remove('image');

            //reset downstream inputs
            $('#session').empty().uiselect('refresh');
            $("#image").val('?????');

            //show the error location
            $("#form-projectDate span:first").attr('style','border-color:#a94442');

            //give feedback to user about the error
            openErrorDialog('Lookup Failure - '+jqXHR.status,
                'Failed to lookup project data.',
                '<ul><li>Project: '+projectDate+'<li>'+jqXHR.responseText,
                function(){
                    $("#form-projectDate span:first").attr('style','');
                    $('#main').slideUp();
                    $('#noProject').slideDown();
                }
            );
        });
    };

    var prettyDate = function(input){
        return input.substring(0,2)+'/'+input.substring(2,4)+'/'+input.substring(4,6);
    };

    var resetSessionSelect = function(){

        var session = hashParser.get("session");
        var data = JSON.parse($('#projectDate option:selected').attr('data-subs'));

        $('#session').empty();
        for(var x in data){
            var selected = (x === session) ? 'selected' : '';
            $('#session').append('<option value='+x+' '+selected+'>'+x+'</option>');
        }

        $('#projectDate').uiselect('refresh');
        $('#session').uiselect('refresh');

        hashParser.add('session',$('#session option:selected').val());

        resetImage();
    };

    var resetImage = function(){
        var path = createPath();
        $.getJSON("service.php?action=summary&path="+path).done(function(info){
            var image = hashParser.get('image');

            $('#image-label').html('Image <small>min: '+info.min+', max: '+info.max+"</small>");
            $('#image').attr('min',info.min).attr('max',info.max);

            if(image > info.min && image <= info.max){
                $("#image").val(image);
            }else if(info.min >= 0){
                $("#image").val(info.min);
                hashParser.add('image',info.min);
            }else
                $("#image").val('?????');

            $('#goBtn').trigger('click');

        }).fail(function(jqXHR){
            hashParser.remove('image');
            $("#image").val('?????');
            $("#form-image").addClass('has-error');

            loadViewerError($('.current'));

            openErrorDialog('Lookup Failure - '+jqXHR.status,
                'Failed to lookup xml documents for given project data.',
                '<ul><li>Project Path: '+path+'<li>'+jqXHR.responseText,
                function(){$("#form-image").removeClass('has-error');}
            );
        });
    }

    var drawRightNavPanel = function(paper,path){
        var width = paper.width;
        var $cloned = $('.paper-nav-right.super').clone().removeClass('super');
        $cloned.width(width);

        $(paper.canvas).before($cloned);

        $cloned.click(function(){
            window.open('images/raw.php?path='+path)
        });

        $cloned.show();
    }

    var drawNavPanel = function(paper){
        var width = paper.width;
        var height = paper.height;
        var initWidth = width;
        var initHeight = height;

        var $svg = $(paper.canvas);

        var $cloned = $('.paper-nav.super').clone().removeClass('super');
        $svg.before($cloned);

        $cloned.show();

        var zoom = function(dir){
            width = width - 100 * dir;
            height = height - 100 * dir;

            //reset and sow error
            if(width <= 0 || height <= 0){
                paper.setViewBox(0,0,initWidth,initHeight,false);
                width = initWidth;
                height = initHeight;
                openErrorDialog('Cannot Zoom','Zoom limits reached.','',function(){/*no-op*/;});
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

    var loadLcms = function(number, $target, path, isCurrent){

        var $paper = $target.find('.paper:first').width(MAIN_WIDTH);

        showLoading($target);

        $.getJSON("service.php?action=dims&path="+path).done(function(info){
            //x and y are reversed because the images have to get rotated
            var x = info[1];
            var y = info[0];

            //ratio is the actual image size to the window width
            var ratio  = (MAIN_WIDTH * 1.0) / (x * 1.0);
            var height = ratio * y;

            path=path+"/"+number;

            $target.find('.box:first').attr('data-path',path);

            $.getJSON("data.php?path="+path+'&ratio='+ratio).done(function(cracks){
                $paper.empty();

                var paper = Raphael($paper.get(0), MAIN_WIDTH, height);
                paper.image('images/image.php?path='+path+'&maxWidth='+MAIN_WIDTH,0,0,MAIN_WIDTH,height);

                drawNavPanel(paper);
                drawRightNavPanel(paper,path);

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

                showLoadingComplete($target, isCurrent);

                //add the drag functionality
                var $mTarget = $target.find('.paper:first');
                var $mTargetOffset = $mTarget.offset();
                var down = false;
                var mTargetX = $mTargetOffset.top;
                var mTargetY = $mTargetOffset.left;
                $mTarget.off();
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
                console.log('load failed ',number);
                loadViewerError($target);
            });
        }).fail(function(jqXHR){
            loadViewerError($target);
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

        $('#moveRight').fadeIn();
        $('#moveLeft').fadeIn();

        //we have info in hash tags
        var project = hashParser.get('project');
        var projectDate = hashParser.get('projectDate');
        var session = hashParser.get('session');

        resetProjectDateSelect(project);

        var nop = $('#noProject');
        if(nop.is(":visible")) nop.slideUp();

        $('.wrapper').width(MAIN_WIDTH);
        $('#main').slideDown();

    }

    var move = function(direction){
        //thes are left (.before) - middle (.current) - right (.after)
        var $right = $('.after:first .box:first');
        var $middle  = $('.current:first .box:first');
        var $left= $('.before:first .box:first');

        var $curTarget = $('.current:first');
        var $leftTarget = $('.before:first');
        var $rightTarget = $('.after:first');

        var duration = 1000;

        var dir = (direction === 'right') ? -1.0 : 1.0;
        var option = {left:dir*MAIN_WIDTH};

        //animate the middle to (right or left) $target
        $middle.addClass('front').animate(option,duration,'swing',function(){
            var $target = (direction === 'left') ? $rightTarget : $leftTarget;

            //remove the current box before attaching this one
            $target.find('.box:first').detach();

            //add this box
            $(this).prependTo($target).attr('style','').removeClass('front');

            //hide the data
            $target.find('.data-container:first').slideUp();
        });



        //animate to current, (right or left) $from to $curTarget
        var $fromTarget = (direction === 'right') ? $rightTarget : $leftTarget; //where we add a new lcms
        var $from = (direction === 'right') ? $right : $left;
        var $to = $curTarget;

        $from.addClass('front').animate(option,duration,'swing',function(){
            //add the $from to $curTarget
            $(this).prependTo($to).attr('style','');

            //clone empty object and add to $fromTarget (where the element animated from)
            $('.box.super:first').clone().removeClass('super').appendTo($fromTarget).show();

            //change the window location fragment
            var path = hashParser.get('project')+'/'+hashParser.get('projectDate')+'/'+hashParser.get('session');
            var $img = $('#image');
            $img.val($img.val()*1-1*dir); //we are actually 1 ahead or 1 behind current
            hashParser.add('image',$img.val());

            showLoadingComplete($to,true);

            //the new element
            loadLcms($img.val()-1*dir,$fromTarget,path);
        });

        //animate (right or left) out and remove from DOM
        var $remove = (direction === 'right') ? $left : $right;
        $remove.animate(option,duration,'swing');

    }

    $(document).ready(function(){
        //setup the drop downs
        $("select").uiselect();

        //set the width
        $('.paper').width(MAIN_WIDTH);

        //get the document dimensions
        $.getJSON("service.php?action=projects")
            .done(function(data){
                for(var p in data){
                    $('#projectSelector').append('<option value='+p+'>'+p+'</option>');
                }
                $('#projectSelector').uiselect('refresh');
            });

        $('#noProjectBtn').click(function(){
            location.hash = 'project:'+$('#projectSelector option:selected').val();
            init();
        });

        //bind drop downs
        $('#projectDate').change(function(){
            hashParser.add('projectDate',$('#projectDate option:selected').val());
            hashParser.remove('session');
            resetSessionSelect();
        });

        $('#session').change(function(){
            hashParser.add('session',$('#session option:selected').val());
            hashParser.remove('image');
            resetImage();
        });


        //only allow digits for input box
        $('#image').keypress(function(e){
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
            hashParser.add('image',$('#image').val());
            var path = hashParser.get('project')+'/'+hashParser.get('projectDate')+'/'+hashParser.get('session');
            var image = $('#image').val() * 1;

            loadLcms(image,$('.current'),path,true);
            loadLcms(image+1,$('.after'),path);
            loadLcms(image-1,$('.before'),path);
        });

        $("#dialogErr").dialog({
            autoOpen:false, width:500, modal:true
        });

        if(info == null || !info.project){
            $('#noProject').slideDown();
            return;
        }
        init();

        $('#moveLeft').click(function(){
            move('left');
        });
        $('#moveRight').click(function(){
            move('right');
        })
    });

    </script>

    <div id="dialogErr" title="Error" style="display: none">
        <p id="dialogErrBody"></p>
        <p id="dialogErrDetail" class="bg-danger"></p>
    </div>

    <div class="paper-nav super" style="display: none">
        <div class="plus ui-icon ui-icon-plus"></div>
        <div class="minus ui-icon ui-icon-minus"></div>
    </div>

    <div class="paper-nav-right super" style="display: none">
        <div class="ext-link ui-icon  ui-icon-extlink"></div>
    </div>

    <div class="box super" style="display: none">
        <div class="paper"></div>
        <div class="data-container">
            <div class="data-head"></div>
            <div class="data-body"></div>
        </div>
    </div>
</div>
</body>