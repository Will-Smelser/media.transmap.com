<!DOCTYPE html>
<html  lang="en">
<head>
    <title>Transmap - Home Page</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="css/justified-nav.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        #logo{
            padding:5px;
            width:100%;
        }
        .topwrapper{
            background-color:#f8981d;
            border-top:solid #CCC 1px;
            border-bottom:solid #CCC 3px;
        }
        .bgimage{
            position:relative;
            margin-top:20px;
            border-top:solid #000 1px;
            border-bottom:solid #000 1px;
            width:100%;
            overflow:hidden;
        }
        .bgimage #background{
            width:100%;
            position:absolute;
            bottom:0px;

        }
        .iconselections{
            text-align: center;
        }
        .iconselections img{

        }
        .wrap{
            background-image: url("images/blackbg.png");
            border-radius: 5px;
            margin-bottom:20px;
            margin-top:20px;
            padding:10px;
            color:#FFF;
            vertical-align: top;
        }
        .wrap p{
            text-align: left;
            display:inline-block;

        }
    </style>
</head>
<div style="height:20px;background-color:#000;"></div>
<div class="topwrapper">
    <div class="container">
        <div class="masthead">
            <div class="row">
                <div class="col-md-2" style="max-width:200px">
                    <img id="logo" src="images/logo.png" title="Transmap Logo" />
                </div>
            </div>
            <!-- <h3 class="text-muted">Project name</h3> -->
            <ul class="nav nav-justified">
                <li class="active"><a href="#">Home</a></li>

                <li><a href="services.html">Services</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="news.html">News</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="bgimage">
    <img class="hidden-xs" id="background" src="images/street.jpg" />
    <div class="container">
        <div style="height:25px" class="visible-md visible-lg"></div>
        <!-- Jumbotron -->
        <div style="position: relative">
            <div class="row iconselections" style="position: relative">
                <div class="col-md-4">
                    <div class="wrap">
                        <h2>Our Company</h2>
                        <p>
                            <img class="img-thumbnail" src="images/truck.png" />
                            Learn more about our company.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wrap">
                        <h2>Our Services</h2>
                        <p>
                            <img class="img-thumbnail" src="images/service.png" />
                            Learn more about our services
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="wrap">
                        <h2>Recent News</h2>
                        <p>
                            <img class="img-thumbnail" src="images/news.png" />
                            Learn about what we are up to.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div style="height:175px" class="visible-md visible-lg"></div>
    </div>
</div>

<div class="container">
    <!-- Example row of columns -->
    <div class="row">
        <div class="col-lg-4">
            <h2>Safari bug warning!</h2>
            <p class="text-danger">As of v7.0.1, Safari exhibits a bug in which resizing your browser horizontally causes rendering errors in the justified nav that are cleared upon refreshing.</p>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-lg-4">
            <h2>Heading</h2>
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-lg-4">
            <h2>Heading</h2>
            <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa.</p>
            <p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
        </div>
    </div>
</div>

<!-- Site footer -->
<div class="footer">
    <p>&copy; Company 2014</p>
</div>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
</html>