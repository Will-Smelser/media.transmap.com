<!DOCTYPE html>
<html  lang="en">
<head>
    <title>Transmap - Home Page</title>

    <?php include 'includes/head.php'; ?>

    <script src="https://code.jquery.com/jquery-2.1.3.min.js" ></script>

    <style>
        #movie{max-width:600px;}
        #nomovie{
            font-size:16px;
            font-weight:bold;
            color:red;
        }
    </style>

    <!-- Modernizer video check -->
    <script>
        function supports_video() {
            return !!document.createElement('video').canPlayType;
        }
        function supports_h264_baseline_video() {
            if (!supports_video()) { return false; }
            var v = document.createElement("video");
            return v.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"');
        }

        $(document).ready(function(){
            var video = "http://www.10tv.com/content/mediaplayer/embed.html?ooid=V4b2YydDrKqB8kcVK8zvLODs_edz-Czz&cmpid=share";
            $("#10tvMovie").load(function(){
                setTimeout(function(){
                    $("#10tvLoading").hide();
                    $("#10tvMovie").show();
                },1000);
            }).attr("src",video);
        });
    </script>

</head>

<?php include 'includes/navigation.php'; ?>

<div class="container">
    <div class="row">
        <h1>Recent News</h1>
        <div class="col-md-6">
            <h3>City of Dublin, Ohio Project</h3>
            <div style="width:510px;height: 321px; position: relative;">
                <div id="10tvLoading" style="position: absolute;z-index: 1">Loading...</div>
                <iframe id="10tvMovie" style="position: absolute;z-index: 2;display: none;" width="510" height="321" frameborder="0" allowfullscreen scrolling="no"></iframe>
            </div>

            <h3>City of Evansville, IN MPO Project</h3>
            <div>
                <div id="nomovie" style="display:none">Your browser cannot play this movie.</div>
                <video width="510" id="movie" preload controls src="http://mediaassets.courierpress.com/video_src/2014/10/02/MPO_1412300614814_8653700_ver1.0.mp4">
                    Browser does not support this video.
                </video>
                <a href="http://www.courierpress.com/news/local-news/vanderburgh-warrick-henderson-team-up-on-road-repair-technology_27559833">
                    Vanderburgh, Warrick, Henderson team up on road repair technology
                </a>
            </div>

            <h3>Osceola County, Florida Project</h3>
            <p><a href="http://www.aroundosceola.com/?p=13468">http://www.aroundosceola.com/?p=13468</a></p>

            <hr/>

            <h3>Press Release</h3>
            <p>
            <a href="http://www10.giscafe.com/nbc/articles/1/1263409/Transmap-Corporation-Supports-City-Santa-Barbara-Goal-Having-Efficient-Sign-Management">
                Press Release - City of Santa Barbara, CA - Nighttime Sign Inspection 2013
            </a>
            </p>
            <p>
                <a href="http://www10.giscafe.com/nbc/articles/view_article.php?articleid=1273595">
                    Press Release - City of Hollywood, FL - Pavement and Asset Management 2013
                </a>
            </p>
            <h3>Facebook</h3>
            <p style="max-width:150px"><a href="https://www.facebook.com/transmap/timeline" class="btn btn-block btn-social btn-facebook"><i class="fa fa-facebook"></i>Facebook</a></p>
            <h3>Twitter</h3>
            <p style="max-width:150px"><a href="https://twitter.com/transmap" class="btn btn-block btn-social btn-twitter"><i class="fa fa-twitter"></i>Twitter</a></p>



        </div>
        <div class="col-md-6">
            <a class="twitter-timeline" href="https://twitter.com/transmap" data-widget-id="474003024096923650">Tweets by @transmap</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
    </div>
</div>


<div class="container">

    <!-- Site footer -->
    <?php include 'includes/foot.php'; ?>

</div> <!-- /container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
</html>