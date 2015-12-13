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
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css"/>

    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="/theme-jquery/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js" ></script>
    <script type="text/javascript" src="/js/raphael-min.js"></script>
    <script type="text/javascript" src="/js/preload.js"></script>
    <script type="text/javascript" src="/js/forms.js"></script>
    <script type="text/javascript" src="/js/hash-parser.js"></script>
    <script type="text/javascript" src="/js/handlebars-v4.0.5.js"></script>

    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

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
            <div class="col-sm-12" id="slider"></div>
        </div>
    </div>
</div>

<div id="moveRight"></div>
<div id="moveLeft"></div>

<!-- box -->
<script id="box-template" type="text/x-handlebars-template">
    <div class="box" data-path="{{path}}" data-image="{{image}}">
        <div class="paper" style="width: {{width}}px; display: block;">
            <h2>IMGE: {{image}}</h2>
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
</script>
<script id="box-template-empty" type="text/x-handlebars-template">
    <div class="box" data-image="-1"></div>
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

    var path = function(data){
        document.location.hash = "#/date/"+data.date+"/session/"+data.session+"/image/"+data.image;
    }

    //setup the slider
    var $slider = $('#slider').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        adaptiveHeight: true,
        variableWidth: true,
        centerMode: true,
        centerPadding: '0px'
    });

    var getData = function(image){
        var $defer = $.Deferred();

        var example = {
            path : "",
            image : image,
            width : MAIN_WIDTH,
            data : [
                {id:0,length:10,width:5,depth:99}
            ]
        };
        $defer.resolve(example);

        return $defer.promise();
    }

    //empty slider contents and reload
    var resetSlider = function(image){
        var image = parseInt(image);

        //make sure slider is empty
        $slider.slick('slickRemove');
        $slider.slick('slickRemove');
        $slider.slick('slickRemove');
        $slider.slick('slickRemove');//just have to have 1 extra!

        //then we 3 slides
        if(image > 0 && image < IMAGE_MAX){
            $.when(getData(image-1),getData(image),getData(image+1)).always(function(data1,data2,data3){
                $slider.slick('slickAdd',tpl(data1));
                $slider.slick('slickAdd',tpl(data2));
                $slider.slick('slickAdd',tpl(data3));
                $slider.slick('slickAdd',tplEmpty());//just have to have 1 extra!
            });

        //1 image to right
        }else if(image === 0){
            $.when(getData(image),getData(image+1)).always(function(data1,data2){
                $slider.slick('slickAdd',tplEmpty());
                $slider.slick('slickAdd',tpl(data1));
                $slider.slick('slickAdd',tpl(data2));
                $slider.slick('slickAdd',tplEmpty());//just have to have 1 extra!
            });

        //1 images to left
        }else if(image === IMAGE_MAX){
            $.when(getData(image-1),getData(image)).always(function(data1,data2){
                $slider.slick('slickAdd',tpl(data1));
                $slider.slick('slickAdd',tpl(data2));
                $slider.slick('slickAdd',tplEmpty());
                $slider.slick('slickAdd',tplEmpty());//just have to have 1 extra!
            });
        }

        $slider.slick('slickGoTo',1);
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

        var slickCnt = $slider.slick('getSlick').$slides.length;

        //just reset image
        if(CUR_DATE !== date || CUR_SESS !== sess || image > CUR_IMAGE+1 || image < CUR_IMAGE-1){
            resetSlider(image);

        //move right
        }else if(image > CUR_IMAGE){
            console.log("right");
            if(image === IMAGE_MAX){
                $slider.slick('slickNext');
                $slider.slick('slickAdd',tplEmpty());
            }else if(slickCnt > 5){
                console.log("HELLO",image);
                resetSlider(image);
            }else{
                console.log("move right - not max : image-"+(image+1));
                getData(image+1).done(function(data){
                    $slider.slick('slickNext');
                    //$slider.slick('slickAdd',tpl(data),3);
                });
            }

        //move left
        }else if(image < CUR_IMAGE){
            console.log("left");
            if(image === IMAGE_MIN){
                $slider.slick('slickAdd',tplEmpty(),0);
                $slider.slick('slickPrev');
            }else{
                getData(image-1).done(function(data){
                    $slider.slick('slickAdd',tpl(data),0);
                    $slider.slick('slickPrev');
                });
            }
        }
        console.log(CUR_IMAGE,image);


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
            path(data);
        });
    });


    $("#moveRight").click(function(){
        $form.forms(function(form){
            var data = form.extract();
            data.image = parseInt(data.image)+1;
            path(data);
        });
    });

    $("#moveLeft").click(function(){
        $form.forms(function(form){
            var data = form.extract();
            data.image = parseInt(data.image)-1;
            path(data);
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