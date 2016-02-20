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
    <script type="text/javascript" src="/js/forms.js"></script>
    <script type="text/javascript" src="/js/hash-parser.js"></script>
    <script type="text/javascript" src="/js/imagesloaded.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha256-Sk3nkD6mLTMOF0EOpNtsIry+s1CsaqQC1rVLTAy+0yc= sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>

    <link href="//www.transmap.com/css/custom.css" rel="stylesheet">

    <style>
    #main-image{
        position:absolute;
        top: -1250px;
        left:-1500px;
        cursor: move;
    }
    #image-toolbox{
        position:absolute;
        top:5%;
        left:5%;
        z-index:99;
    }
    #image-toolbox button{
        margin-right:20px;
        cursor:pointer;
    }
    #backwardBig,#forwardBig,#forward,#backward{
        display: inline-block;
    }
    .dirIcons {
        font-size:150%;
        color:#ccc;
        cursor:pointer;
        text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;
    }
    .dirIcons:hover, #forwardBig:hover>div, #backwardBig:hover>div {
        color:#fff;
    }
    </style>

</head>
<body>
<div style="height:20px;background-color:#000;"></div>
<div class="topwrapper">

            <nav class="navbar navbar-inverse" style="background-color: transparent; border:none; margin-bottom: 0px;">
                <div class="container-fluid">

                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#form-nav" style="background-color: #000">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand visible-xs-block" href="#" style="color:#000">Transmap</a>
                </div>


                        <form name="form-nav" id="form-nav" class="collapse navbar-collapse" style="margin:0px;padding:0px;overflow:hidden">
                            <div class="nav navbar-nav row">
                                <div class="col-xs-3 hidden-xs">
                                    <img id="logo" src="../lcms/images/logo.png" width="200"  class="img-responsive hidden-xs" alt="TransMap">
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="direction">Direction</label>
                                        <select name="direction" id="direction" class="form-control">
                                            <option vlaue="Front" selected>Front</option>
                                            <option vlaue="Back">Back</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group" id="form-projectDate">
                                        <label for="projectDate">Date</label>
                                        <input id="projectDate" name="date" class="form-control" placeholder="Choose Date" />
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label for="session">Session</label>
                                        <input id="session" name="session" class="form-control" placeholder="Enter Session" type="number" />
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <input class="form-control" name="image" type="number" step="1" id="image" placeholder="Image #"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="goBtn2">&nbsp;</label>
                                            <input type="button" id="goBtn2" style="min-width: 45px" class="form-control goBtn btn btn-primary"  value="Go">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>





                </div>
            </nav>


</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
            <div id="image-toolbox">
                <div style="text-align: center">
                    <div id="forwardBig">
                        <div class="glyphicon glyphicon-chevron-up dirIcons"></div><br/>
                        <div class="glyphicon glyphicon-chevron-up dirIcons" style="top:-10px"></div>
                    </div><br/>
                    <div id="forward" style="margin-bottom: 15px;">
                        <div class="glyphicon glyphicon-chevron-up dirIcons" ></div>
                    </div>
                </div>

                <div id="zoom" style="width:200px"></div>

                <div style="text-align: center">
                    <div id="backward" style="margin-top: 15px;">
                        <div class="glyphicon glyphicon-chevron-down dirIcons" ></div>
                    </div><br/>
                    <div id="backwardBig">
                        <div class="glyphicon glyphicon-chevron-down dirIcons" style="top:10px"></div><br/>
                        <div class="glyphicon glyphicon-chevron-down dirIcons"></div>
                    </div>
                </div>
            </div>
            <div class="embed-responsive embed-responsive-16by9">
                <div  class="embed-responsive-item" style="overflow:hidden;">
                    <img id="main-image" src="" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Start here-->
<div class="modal fade bs-example-modal-sm" id="loading" tabindex="-1"
     role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <span class="glyphicon glyphicon-time">
                    </span>&nbsp;Please Wait, Loading...
                </h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-info
                    progress-bar-striped active"
                         style="width: 100%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal ends Here -->

<script>
    var Preloader = function(){
        var $container = $(document.createElement('div')).hide();
        $('body').append($container);

        var _img = function(url){
            return $(document.createElement('img')).attr('src',url);
        }

        this.preload = function(urls){
            $container.empty();
            for(var x in urls){
                $container.append(_img(urls[x]));
            }
            return $container.imagesLoaded();
        }
    };

    var pad = function(chr,len,val){
        while(val.length <= len){
            val = chr+val;
        }
        return val;
    };

    var imageUrl = function(dir,date,session,image){
        return "http://storage.googleapis.com/tmap_pano/"+date
            +"/"+session
            +"/"+dir+"/ladybug_panoramic_"+pad("0",5,image+"")
            +".jpg";
    };

    var setHashLocation = function(data){
        window.location.hash = '#/'+data.direction+'/date/'+data.date+'/session/'+data.session+'/image/'+data.image;
    }

    var loader = new Preloader();

    $(document).ready(function(){
        var $form = $('#form-nav');
        var $loading = $("#loading");
        var $img = $("#main-image");

        //the form
        var form;
        $('#form-nav').forms(function(f){
            form = f;
        });

        //fix the date on the way in and out
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

        //setup the hash route
        var router = new HashRouter();
        router.addPath("/{direction:[a-zA-Z]+}/date/{date:\\d+}/session/{session:\\d+}/image/{image:\\d+}",function(data){
            form.fill(data);

            var url = imageUrl(data.direction,data.date,data.session,data.image);

            $loading.modal('show');

            $img.fadeOut().attr('src',url)
                .imagesLoaded()
                .always(function(){
                    $loading.modal('hide');
                    $img.fadeIn();
                })
                .fail(function(){
                    alert('Failed to load image.  Please check the Date, Session, and Image are correct/valid.');
                });

        });
        router.start();

        //add datepicker for form
        $("#projectDate").datepicker({
            beforeShow: function() {
                setTimeout(function(){
                    $('.ui-datepicker').css('z-index', 9999);
                }, 1);
            }
        });

        //the go button clicks and Front/Back change
        $('.goBtn,#goBtn2,#direction').click(function(){
            setHashLocation(form.extract());
        });

        //listen for "enter" key
        $('.form-control').keypress(function(e) {
            if(e.which == 13) {
                setHashLocation(form.extract());
            }
        });

        //image navigation stuff
        $img.draggable();

        var zoomStep = .25;
        var imageInitWidth = 5400;
        var imageInitHeight = 2700;
        var imageInitPos = $img.position();

        var center = 20;
        $("#zoom").slider({
            value : center,
            step : 10,
            slide : function(evt,ui){
                //value between -5 and 5
                var val = (ui.value-center)/10;

                //what percent to increase/decreate image to
                var pct = 1+zoomStep*val;

                var newWidth = pct*imageInitWidth;
                var newHeight = pct*imageInitHeight;
                var newPosTop = imageInitPos.top+(imageInitHeight - newHeight)/ 1.5;
                var newPosLeft = imageInitPos.left+(imageInitWidth - newWidth)/2.5;
                //var pos = $img.position();
                //var newPosTop = pct*pos.top;
                //var newPosLeft = pct*pos.left;

                //set width and try to keep things centered
                $img.attr('width',newWidth+'px');
                $img.css('top',newPosTop+'px');
                $img.css('left',newPosLeft+'px');
            }
        });


        var goTo = function(add){
            var data = form.extract();

            var dir = (data.direction === 'Front') ? 1 : -1;

            data.image = parseInt(data.image) + add * dir;
            setHashLocation(data);
        }

        var doLoad = function(){
            //form may not finished loading, lets pull information from hash
            var data = router.evaluate(window.location.hash);
            var urls = [];

            var dir = (data.direction === 'Front') ? 1 : -1;

            var current = parseInt(data.image);
            urls.push(imageUrl(data.direction,data.date,data.session,current+1*dir));
            urls.push(imageUrl(data.direction,data.date,data.session,current-1*dir));
            urls.push(imageUrl(data.direction,data.date,data.session,current+bigStep*dir));
            urls.push(imageUrl(data.direction,data.date,data.session,current-bigStep*dir));

            loader.preload(urls);
        }

        var bigStep = 5;
        $("#forward").click(function(){
            goTo(1);
            doLoad();
        });
        $("#forwardBig").click(function(){
            goTo(bigStep);
            doLoad();
        });
        $("#backward").click(function(){
            goTo(-1);
            doLoad();
        });
        $("#backwardBig").click(function(){
            goTo(-bigStep);
            doLoad();
        });


    });
</script>

<div style="position: fixed;left:15px;bottom:10px;z-index: 100;box-shadow: 5px 5px 8px #888888;background-color: #efefef; border: solid #ccc 1px;padding:5px">
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