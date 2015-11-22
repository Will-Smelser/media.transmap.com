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
    <script type="text/javascript" src="/js/preload.js"></script>
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
                    </span>Please Wait
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

    var pad = function(chr,len,val){
        while(val.length <= len){
            val = chr+val;
        }
        return val;
    };

    var imageUrl = function(date,session,image){
        return "http://storage.googleapis.com/tmap_pano/"+date
            +"/"+session
            +"/Front/ladybug_panoramic_"+pad("0",5,image+"")
            +".jpg";
    };

    var loader = new Preload();

    $(document).ready(function(){
        var $form = $('#form-nav');
        var $loading = $("#loading");

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


        //add datepicker for form
        $("#projectDate").datepicker();

        //the go button clicks
        var $img = $("#main-image");
        $('.goBtn').click(function(){
            $('#form-nav').forms(function(form){
                var data = form.extract();
                var url = imageUrl(data.date,data.session,data.image);

                for(var x in data){
                    hashParser.add(x,data[x]);
                }

                $loading.modal('show');
                $img.fadeOut();
                loader.preload(url);
                loader.waitOnImage(url,function(){
                    $img.attr('src',url).imagesLoaded(function(){
                        $loading.modal('hide');
                        $img.fadeIn();
                    });
                    //$loading.modal('hide');
                });

            });
        });


        $form.forms(function(form){
            var initData = hashParser.parse();
            if(initData && initData.date){
                form.fill(initData);
                $("#goBtn2").click();
            }
        });

        $img.draggable();

        var zoomStep = .25;
        var imageInitWidth = 5400;
        var imageInitHeight = 2700;
        var imageInitPos = $img.position();

        //this does not actually work, atleast not reliably
        $img.load(function(){
            imageInitWidth=$(this).width();
            imageInitHeight=$(this).height();
        });

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

        var getNextNurls = function(max,dir){
            var result = [];
            $form.forms(function(form){
                var data = form.extract();
                var start = parseInt(data.image);
                for(var i=0; i<=max; i++){
                    result.push(imageUrl(data.date,data.session,start+i*dir));
                }
            });
            return result;
        };

        var goTo = function(add){
            $form.forms(function(form){
                var data = form.extract();
                data.image = parseInt(data.image) + add;
                form.fill(data);
                $("#goBtn2").trigger('click');
            });
        }

        var doLoad = function(){
            var next = getNextNurls(bigStep,1);
            var prev = getNextNurls(bigStep,-1);

            /*
            for(var x in next){
                loader.preload(next[x]);
            }*/
            //loader.preload(next[1]);
            loader.preload(next[bigStep]);
            //loader.preload(prev[1]);
            loader.preload(prev[bigStep]);

        }

        var doCleanup = function(){
            var prev = getNextNurls(bigStep*2,-1);
            var remove = prev.slice(bigStep+1);
            for(var x in remove){
                loader.removeImage(remove[x]);
            }
        }

        var bigStep = 5;
        $("#forward").click(function(){
            goTo(1);
            doLoad();
            doCleanup();
        });
        $("#forwardBig").click(function(){
            goTo(bigStep);
            doLoad();
            doCleanup();
        });
        $("#backward").click(function(){
            goTo(-1);
            doLoad();
            doCleanup();
        });
        $("#backwardBig").click(function(){
            goTo(-bigStep);
            doLoad();
            doCleanup();
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