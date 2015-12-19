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
    <link rel="stylesheet" href="/theme-jquery/jquery-ui-1.9.2.custom/css/custom-theme/jquery-ui-1.9.2.custom.css" type="text/css" media="screen" />

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="/theme-jquery/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js" ></script>
    <script type="text/javascript" src="/js/raphael-min.js"></script>
    <script type="text/javascript" src="/js/preload.js"></script>
    <script type="text/javascript" src="/js/forms.js"></script>
    <script type="text/javascript" src="/js/hash-parser.js"></script>
    <script type="text/javascript" src="/js/handlebars-v4.0.5.js"></script>
    <script type="text/javascript" src="http://coolcarousels.frebsite.nl/c/5/jquery.carouFredSel-6.0.4-packed.js"></script>

    <link href="//www.transmap.com/css/custom.css" rel="stylesheet">


    <style>

        /* remove the rounded corners to match UI already in place */
        * {
            -webkit-border-radius: 0 !important;
            -moz-border-radius: 0 !important;
            border-radius: 0 !important;
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
        .slide{
            display: inline-block;
            width:700px;
        }

    </style>

</head>
<body>
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
            <div class="col-sm-12"><ul id="slides"></ul></div>
        </div>
    </div>
</div>

<div id="moveRight"></div>
<div id="moveLeft"></div>

<!-- box -->
<script id="box-template" type="text/x-handlebars-template">
    <li class="slide">
        <div class="box" data-path="{{path}}" id="{{date}}-{{session}}-{{image}}" data-image="{{image}}">
            <div class="paper" style="width: {{width}}px; display: block;">
                <h2>IMAGE: {{image}}</h2>
            </div>
            <div class="data-container" style="width: {{width}}px; display: block;">
                <div class="data-head"><table class="table table-hover"><thead><tr><th>ID</th><th>Length (meter)</th><th>Width (millimeter)</th><th>Depth (millimeter)</th></tr></thead><tbody></tbody></table></div>
                <div class="data-body">
                    <table class="table table-hover">
                        <tbody>
                        {{#each data}}
                            <tr><td>{{id}}</td><td>{{length}}</td><td>{{width}}</td><td>{{depth}}</td></tr>
                        {{/each}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </li>
</script>
<script id="box-template-empty" type="text/x-handlebars-template">
    <li class="slide"><div class="box" data-image="-1"></div></li>
</script>

<script>

    var MAIN_WIDTH = 800;
    var IMAGE_MAX = 9999;
    var IMAGE_MIN = 0;
    var MAX_SLIDER_COUNT = 50;
    var CUR_IMAGE;
    var CUR_SESS;
    var CUR_DATE;

    var tpl = Handlebars.compile($('#box-template').html());
    var tplEmpty = Handlebars.compile($('#box-template-empty').html());

    var path = function(date,session,image){
        return "/date/"+date+"/session/"+session+"/image/"+image;
    }

    //setup the slider
    var $slider = $('#slides');

    var $slides = $("#slides");

    var getData = function(date,session,image){
        var $defer = $.Deferred();

        var example = {
            path : path({date:date,session:session,image:image}),
            image : image,
            session : session,
            date : date,
            width : MAIN_WIDTH,
            data : [
                {id:0,length:10,width:5,depth:99}
            ]
        };
        $defer.resolve(example);

        return $defer.promise();
    }

    var _createCarousel = function(idx){
        $slider.carouFredSel({
            circular: true,
            infinite: false,
            width: '100%',
            height: "variable",
            items: {
                visible: 3,
                start: idx,
                height: "variable",
                width:700
            },
            scroll: {
                items: 2,
                duration: 1000,
                timeoutDuration: 3000
            },
            auto:false
        });
    }

    //empty slider contents and reload
    var resetSlider = function(date,session,image){
        var image = parseInt(image);

        $slider.trigger('destroy');

        //remove everything
        $slides.empty();

        //then we 3 slides
        if(image > 0 && image < IMAGE_MAX){
            $.when(getData(date,session,image-1),getData(date,session,image),getData(date,session,image+1))
                .always(function(data1,data2,data3){
                    $slides.append(tpl(data1));
                    $slides.append(tpl(data2));
                    $slides.append(tpl(data3));
                    _createCarousel(1);
            });

        //1 image to right
        }else if(image === 0){
            $.when(getData(date,session,image),getData(date,session,image+1),getData(date,session,image+2))
                .always(function(data1,data2,data3){
                    $slides.append(tpl(data1));
                    $slides.append(tpl(data2));
                    $slides.append(tpl(data3));
                    _createCarousel(0);
            });

        //1 images to left
        }else if(image === IMAGE_MAX){
            $.when(getData(date,session,image-2),getData(date,session,image-1),getData(date,session,image))
                .always(function(data0,data1,data2){
                    $slides.append(tpl(data0));
                    $slides.append(tpl(data1));
                    $slides.append(tpl(data2));
                    _createCarousel(2);
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
            resetSlider(data.date,data.session,data.image);
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
            getData(data.date,data.sess,imgNumber+1).done(function(data){
                $slider.trigger('insertItem',[tpl(data),"end"]);
                $slider.trigger("next",[1,true,function(){
                    $slider.trigger('removeItem',[$(this), 0]);
                    navLock = false;
                }]);
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
            getData(data.date,data.sess,imgNumber-1).done(function(data){
                $slider.trigger('insertItem',[tpl(data),"end"]);
                $slider.trigger("prev",[1,true,function(){
                    $slider.trigger('removeItem',[$(this), 0]);
                    navLock = false;
                }]);
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