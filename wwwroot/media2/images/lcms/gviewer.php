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


    <link href="//www.transmap.com/css/custom.css" rel="stylesheet">

    <link rel="stylesheet" href="/theme-jquery/jquery-ui-1.9.2.custom/css/custom-theme/jquery-ui-1.9.2.custom.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="/theme-jquery/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js" ></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="/js/raphael-min.js"></script>
    <script type="text/javascript" src="/js/forms.js"></script>
    <script type="text/javascript" src="/js/hash-parser.js"></script>
    <script type="text/javascript" src="/js/handlebars-v4.0.5.js"></script>
    <script type="text/javascript" src="/js/carousel-engine.js"></script>

    <style>

        /*
        * {
            -webkit-border-radius: 0 !important;
            -moz-border-radius: 0 !important;
            border-radius: 0 !important;
        }*/

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

        .image-container{
            border-left:solid black 1px;
            border-top:solid black 1px;
            border-bottom:solid black 1px;
            display: block;
            overflow:hidden;
            position:relative;
        }

        .paper-nav{
            position:absolute;
            top:20px;
            z-index: 20;
            visibility: hidden;
        }
        .carousel-active .paper-nav{
            visibility: visible;
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
            top:0px;
        }
        .paper-nav .minus{
            top:30px;
        }

        .carousel-active .paper-nav-right{
            visibility: visible;
        }
        .paper-nav-right{
            position:absolute;
            top:20px;
            right:20px;
            visibility: hidden;
            z-index:20;
        }
        .paper-nav-right .ext-link{
            width:20px;
            height:20px;
            border:solid #333 2px;
            background-color: #FFF;
            cursor: pointer;
        }

        .image-wrapper{
            position:relative;
            width:800px;
            height: 332px;
            overflow:hidden;
        }
        .image-inner{
            position: absolute;
            /*top:291px;*/
            left:800px;
        }
        .image-inner img{
            -ms-transform: rotate(-90deg) scale(0.32); /* IE 9 */
            -webkit-transform: rotate(-90deg) scale(0.32); /* Chrome, Safari, Opera */
            transform: rotate(90deg) scale(0.32);

            -ms-transform-origin: top left;
            -webkit-transform-origin: top left;
            transform-origin: top left;
        }

        .paper svg{
            position:absolute;
            top:0px;
            left:0px;
        }

        table th{
            text-align: center;
        }
        table th:nth-child(1), table td:nth-child(1){
            width:50px;
        }

    </style>

</head>
<body>

<div class="modal fade" tabindex="-1" id="pleaseWaitDialog" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Loading...</h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="alert" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Alert</h4>
            </div>
            <div class="modal-body">
                <p class="alert alert-danger">HELLO WORLD</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- navigation bar -->
<div style="height:20px;background-color:#000;"></div>
<div class="topwrapper">
    <div class="container">
        <form name="form-nav" id="form-nav">
            <div id="nav">
                <div class="row">
                    <div class="col-md-3">
                        <img id="logo" src="../lcms/images/logo.png" width="200"  class="img-responsive visible-lg-block visible-md-block" alt="TransMap">
                        <input type="button" id="goBtn2" class="goBtn btn btn-primary pull-right hidden-md hidden-lg"  value="Go">
                        <h2 class="hidden-md hidden-lg">TransMap Inc.</h2>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" id="form-projectDate">
                            <label for="projectDate">Date</label>
                            <input id="projectDate" name="date"  class="form-control" placeholder="Choose Date" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="session">Session</label>
                            <input id="session" name="session" class="form-control" placeholder="Enter Session" type="number" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input class="form-control" name="image" type="number" step="1" id="image" placeholder="Image #"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1  visible-lg-block visible-md-block">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="goBtn2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <input type="button" id="goBtn2" class="goBtn btn btn-primary"  value="Go">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- main content -->
<div style="margin-top:12px">
    <div id="main" class="container-fluid">
        <div class="row">
            <div class="col-sm-12" style="overflow:hidden;">
                <div id="slides" style="width:600%;position:relative;left:-250%;text-align: center"></div>
            </div>
        </div>
    </div>
</div>

<div id="moveRight"></div>
<div id="moveLeft"></div>

<!-- box -->
<script id="box-template" type="text/x-handlebars-template">
    <div data-image="{{image}}" class="slide" style="width:{{width}}px; overflow:hidden; margin:0px; padding:0px; position:relative; left:0px">
        <div class="box" data-path="{{path}}" id="{{date}}-{{session}}-{{image}}" data-image="{{image}}">

            <div class="paper-nav">
                <div class="plus ui-icon ui-icon-plus"></div>
                <div class="minus ui-icon ui-icon-minus"></div>
            </div>

            <div class="paper-nav-right super">
                <div class="ext-link ui-icon  ui-icon-extlink"></div>
            </div>

            <div class="image-alert" style="position:absolute;top:50px;left:75px;right:75px;z-index:99">
                <div class="alert alert-warning hide" >
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span class="alert-body"></span>
                </div>
            </div>

            <div class="image-container" style="height:{{height}}px; width:{{width}}px;">
                <div class="paper" id="paper-{{image}}" data-image-src="https://storage.googleapis.com/tmap_lcms/{{date}}/{{session}}/LcmsResult_OverlayInt_{{image}}.jpg" style="position:absolute;left:-1px;right:0px;z-index:10"><!-- the svg canvas//--></div>
            </div>
            <div class="data-container" id="data-{{image}}" style="display:none; margin:0px 10px;">
                <div class="data-body">
                    <table class="table table-hover" style="margin-bottom:0px">
                        <thead><tr><th>ID</th><th>Length ({{data.units.length}})</th><th>Width ({{data.units.width}})</th><th>Depth ({{data.units.depth}})</th></tr></thead>
                    </table>
                    <div style="height:{{dataHeight}}px; overflow-y:auto; width:100%" class="data-container-table">
                        <table id="data-table-{{image}}" class="table table-hover">
                            <tbody>
                            {{#each data.paths}}
                            <tr onclick="rowClick.call(this)" id="data-{{../image}}-{{id}}" data-id="{{id}}" data-image="{{../image}}"><td>{{id}}</td><td>{{length}}</td><td>{{width}}</td><td>{{depth}}</td></tr>
                            {{/each}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>
<script id="box-template-empty" type="text/x-handlebars-template">
    <div class="slide"><div class="box" data-image="-1"></div></div>
</script>

<script>

var MAIN_WIDTH = 800;
var MAIN_HEIGHT = 332;
var IMAGE_MAX = 9999;
var IMAGE_MIN = 0;
var IMAGE_SCALE = 0.32;

var CUR_IMAGE;
var CUR_SESS;
var CUR_DATE;

var WIN_Y;
var DATA_POS_Y;
var DATA_HEAD = 37;
var DATA_HEIGHT = 200;

var $loading = $('#pleaseWaitDialog').modal();

var setDataTableHeight = function(){
    WIN_Y = $(window).height();

    var $dataContainer = $('.carousel-active .data-container-table').first();
    if($dataContainer){
        var temp = $dataContainer.position().top;
        if(temp <= 0){
            return setTimeout(setDataTableHeight,20);
        }

        DATA_POS_Y = temp;
        DATA_HEIGHT = Math.max(200, WIN_Y - DATA_POS_Y - DATA_HEAD - 80);//I don't know why the 80 :(

        $('.data-container-table').height(DATA_HEIGHT);
    }
};

//track window size for settings data height
$(window).resize(setDataTableHeight);

var tpl = Handlebars.compile($('#box-template').html());
var tplEmpty = Handlebars.compile($('#box-template-empty').html());

var path = function(date,session,image){
    return "/date/"+date+"/session/"+session+"/image/"+image;
}

//setup the slider
var $slider = $('#slides');
var $slides = $("#slides");

var zoom = function(paper, dir){
    var width = paper.width + dir * 100;
    var height = paper.height + dir * 100;

    //reset and sow error
    if(width <= 0 || height <= 0){
        var $alert = $('#alert');
        $alert.find('.modal-body').html("<p class='alert alert-danger'><strong>WARNING</strong> Zoom limits reached</p>");
        $alert.modal('show');
        return;
    }

    paper.width = width;
    paper.height = height;
    paper.setViewBox(0,0,width,height,false);
}

var render = function(data){

    var $target = $('#paper-'+data.image);
    var src = $target.attr('data-image-src');
    var paper = Raphael($target.get(0), MAIN_WIDTH, MAIN_HEIGHT);

    //save the Raphael paper to the .paper element
    $.data($target.get(0),'paper',paper);

    //draw paths
    for(var x in data.data.paths){
        (function(data,image){
            drawPath(paper, image, data);
        })(data.data.paths[x],data.image);
    }

    var img = paper.image(src,0,0,MAIN_HEIGHT,MAIN_WIDTH);
    img.rotate(90,0,0);
    img.translate(0,-800);
    img.toBack();


    //make everything draggable
    //var $image = $('#image-wrapper-'+data.image);

    $target.draggable();

    //add zoom features
    var $nav = $('#'+data.date+'-'+data.session+'-'+data.image+' .paper-nav');
    var $navRt = $('#'+data.date+'-'+data.session+'-'+data.image+' .paper-nav-right');
    //var $img = $image.find("img");

    //na
    $nav.find('.plus').click(function(){
        zoom(paper,-1.0);//,scale,$img);
    });
    $nav.find('.minus').click(function(){
        zoom(paper,1.0);//,scale,$img);
    });
    $navRt.click(function(){
        window.open(src);
    });
}

var drawPath = function(paper, image, pathData, $row){
    var $allRows = $('#data-'+image+' tr');
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

        //reset the selected data row
        $allRows.removeClass('success');
        var $row = $('#data-'+image+'-'+pathData.id);

        $row.addClass('success');

        //scroll into view
        var dataContainer = $("#data-table-"+image).parent();

        dataContainer.scrollTop(0);

        var containerTop = dataContainer.offset().top;
        var rowTop = $row.offset().top;
        var diff = rowTop-containerTop;

        dataContainer.scrollTop(diff);
    });
};

var rowClick = function(){
    var id = $(this).attr('data-id');
    var image = $(this).attr('data-image');

    $(this).siblings().each(function(){$(this).removeClass('success')});
    $(this).addClass('success');

    var paper = $.data($('#paper-'+image).get(0),'paper');
    if(paper.myglow)
        paper.myglow.remove();

    var path = paper.getById('path-'+id);
    paper.myglow = path.glow({
        color: '#ff0',
        width: 15
    });
}

var pad = function(str,len){
    var res = str.toString();
    while(res.length < len){
        res = "0"+res;
    }
    return res;
};

var getData = function(date,session,image){
    var $deferred = $.Deferred();
    var image = pad(image,6);
    var date = pad(date,6);

    var xmlUrl = 'gdata.php?date='+date+'&session='+session+'&image='+image;

    var base = {
        path : path({date:date,session:session,image:image}),
        image : image,
        session : session,
        date : date,
        width : MAIN_WIDTH,
        height: MAIN_HEIGHT,
        dataHeight : DATA_HEIGHT,
        data : [
            /*{id:0,length:10,width:5,depth:99}*/
        ]
    };

    $.getJSON(xmlUrl).done(function(data){
        $deferred.resolve($.extend(base,{data:data}));
    }).fail(function(){
        $deferred.reject();
    });

    return $deferred.promise();
}

var carousel;

var _createCarousel = function(idx){
    if(carousel){
        carousel.gotTo(idx);
    }else{
        carousel = new CarouselEngine({
            showElements : 3,
            startElement: idx
        },$slider);

        setDataTableHeight();
    }
}

//empty slider contents and reload
var resetSlider = function(date,session,image){
    var image = parseInt(image);

    //remove everything
    if(carousel){
        carousel.destroy();
        carousel = null;
        $slides.empty();
    }

    $loading.modal('show');

    //then we 3 slides
    if(image > 0 && image < IMAGE_MAX){
        $.when(getData(date,session,image-1),getData(date,session,image),getData(date,session,image+1))
            .always(function(data1,data2,data3){
                $slides.append(tpl(data1)); render(data1);
                $slides.append(tpl(data2)); render(data2);
                $slides.append(tpl(data3)); render(data3);
                _createCarousel(1);
                $('#data-'+pad(data2.image,6)).slideDown();
                $loading.modal('hide');
            });

        //1 image to right
    }else if(image === 0){
        $.when(getData(date,session,image),getData(date,session,image+1),getData(date,session,image+2))
            .always(function(data1,data2,data3){
                $slides.append(tpl(data1)); render(data1);
                $slides.append(tpl(data2)); render(data2);
                $slides.append(tpl(data3)); render(data3);
                _createCarousel(0);
                $('#data-'+pad(data1.image,6)).slideDown();
                $loading.modal('hide');
            });

        //1 images to left
    }else if(image === IMAGE_MAX){
        $.when(getData(date,session,image-2),getData(date,session,image-1),getData(date,session,image))
            .always(function(data0,data1,data2){
                $slides.append(tpl(data0)); render(data0);
                $slides.append(tpl(data1)); render(data1);
                $slides.append(tpl(data2)); render(data2);
                _createCarousel(2);
                $('#data-'+pad(data2.image,6)).slideDown();
                $loading.modal('hide');
            });
    }
}

//main form events and logic here


//setup the form extract
var $form = $('#form-nav');

//some form manipulation
$form.forms(function(form){
    form.addNameExtractFilter('date',function(type,obj){
        if(obj.value && obj.value.length){
            var parts = obj.value.split("/");
            var month = parts[0];
            var day = parts[1];
            var yr = parts[2].substr(2);
            obj.value = month + day + yr;
        }
        return obj;
    });
    form.addNameFillFilter('date',function(type,obj){
        if(obj.value && obj.value.length === 6){
            var month = obj.value.substr(0,2);
            var day  =obj.value.substr(2,2);
            var yr = obj.value.substr(4,2);
            obj.value = month+"/"+day+"/20"+yr;
        }
        return obj;
    });
});

//setup routes
var router = new HashRouter();
router.addPath("/date/{date:[\\d+]}/session/{sess:[\\d+]}/image/{image:[\\d+]}",function(data){

    var sess = data.sess;
    var date = data.date;
    var image = parseInt(data.image);

    $form.forms(function(form){
        form.fill({date:date,session:sess,image:image});
    });

    //just reset image
    if(CUR_DATE !== date || CUR_SESS !== sess || image != CUR_IMAGE){
        resetSlider(date,sess,image);
    }

    CUR_DATE = date;
    CUR_IMAGE = image;
    CUR_SESS = sess;

});
router.start();
router.evaluate(document.location.hash);

//bind to form "Go" button, the hash-parser should be listening for these changes
$(".goBtn").click(function(){
    $form.forms(function(form){
        var data = form.extract();
        document.location.hash = "#"+path(data.date,data.session,data.image);
    });
});

var navLock = false;

$("#moveRight").click(function(){
    if(navLock) return;

    navLock = true;
    $form.forms(function(form){
        var data = form.extract();
        var imgNumber = parseInt(data.image)+1;

        CUR_IMAGE = imgNumber;

        document.location.hash = "#"+path(data.date,data.session,imgNumber);
        getData(data.date,data.session,imgNumber+1).done(function(data){

            $('#data-'+pad(imgNumber-1,6)).slideUp();

            carousel.slideAdd(tpl(data),99);
            render(data);
            carousel.next(function(){
                $('#data-'+pad(imgNumber,6)).slideDown();
                carousel.slideRemove(0);
            });
        }).always(function(){
            navLock = false;
        });
    });
});

$("#moveLeft").click(function(){
    if(navLock) return;

    navLock = true;
    $form.forms(function(form){
        var data = form.extract();
        var imgNumber = parseInt(data.image)-1;

        CUR_IMAGE = imgNumber;

        document.location.hash = "#"+path(data.date,data.session,imgNumber);

        getData(data.date,data.session,imgNumber-1).done(function(data){
            $('#data-'+pad(imgNumber+1,6)).slideUp();

            carousel.slideAdd(tpl(data),0);
            render(data);
            carousel.prev(function(){
                $('#data-'+pad(imgNumber,6)).slideDown();
                carousel.slideRemove(99);
            });
        }).always(function(){
            navLock = false;
        });

    });
});


</script>


<div style="position: fixed;left:15px;bottom:10px;z-index: 100;box-shadow: 5px 5px 5px #888888;background-color: #efefef; border: solid #ccc 1px;padding:5px">
    <!-- Site footer -->

    <div class="footer">
        <h4>Transmap Corporation</h4>
        <p>
            3366 Riverside Dr., Suite 103<br/>
            Upper Arlington, OH 43221<br/>
            (P) 614.481.6799<br/>
            (F) 614.481.4017

        </p>
        <p>&copy; Transmap 2014</p>
    </div>
</div> <!-- /container -->

</body>