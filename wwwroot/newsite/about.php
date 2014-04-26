<!DOCTYPE html>
<html  lang="en">
<head>
    <title>Transmap - Home Page</title>

    <?php include 'includes/head.php'; ?>

    <style>
        #movie{max-width:600px;}
        #nomovie{
            font-size:16px;
            font-weight:bold;
            color:red;
        }
    </style>
</head>

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
</script>

<?php include 'includes/navigation.php'; ?>

<div class="container">

    <h1>Our Company</h1>

    <div id="nomovie" style="display:none">Your browser cannot play this movie.</div>
    <video id="movie" preload controls src="http://tmapproject.s3.amazonaws.com/Website%20Content/TransmapVideo2.mp4"></video>

    <h2>Firm Profile</h2>
    <p>
        Transmap is a national provider of professional, technical, and management support services to the transportation industry.  The focus of Transmap’s services is directed towards city-owned and county-owned transportation systems in order to provide the highest quality infrastructure management solutions.  Transmap specializes in the mobile data collection, processing, analysis and inventory of roadway assets (e.g., traffic signs and pavement condition).
    </p>
    <p>
        Transmap was founded in 1994 by Dr. Kurt Novak as a spin-off from The Ohio State University’s Center for Mapping.  Transmap graduated from the business incubator at the university, Tech Columbus, in 2002.  This experience has led to Transmap’s recognition as a leading innovator in the mobile mapping, public works and infrastructure management arenas for over 20 years.
    </p>
    <p>
        Like any truly successful firm, our people are the leading reason behind our success.  From ownership down to staff personnel, Transmap’s team consists of Professional Engineers (PEs), professionals holding advanced degrees in physical sciences (PhDs), Geographic Information Systems Professionals (GISPs), and experienced technical professionals to deliver the highest quality solutions.  Customer experience is paramount at Transmap.  With a highly effective and experienced project management team, Transmap provides an incomparable customer-centric approach to implementing desired outcomes.
    </p>
    <p>
        The technologies and equipment utilized for Transmap’s operations are state of the art and regularly updated to exceed industry standards.  With our fleet of mobile mapping vehicles, high definition imagery in conjunction with vehicle-based LiDAR, web-based implementation, and an emphasis on green operations, we are a progressive specialist for any modern, urban or rural environment.  We are extremely well-versed in Geographic Information Systems (GIS), and how to best utilize GIS tools in the infrastructure management needs for all of our customers.  With an increasingly changing software environment, Transmap’s open source policy allows for data to be seamlessly implemented into dozens of software systems.
    </p>
    <p>
        Transmap constantly emphasizes client satisfaction by providing the highest value at the lowest cost for all of its customers.  With a highly capable and exceptionally trained staff, implementing the best solutions for infrastructure management is what separates Transmap as the best provider in the industry.
    </p>
</div>


<script>
    if (!supports_h264_baseline_video()){
        document.getElementById('nomovie').setAttribute("style","display:block");
    }
</script>

<div class="container">
    <!-- Site footer -->
    <?php include 'includes/foot.php'; ?>

</div> <!-- /container -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
</html>