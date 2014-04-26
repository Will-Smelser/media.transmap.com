<?php
/**
 * Author: Will Smelser
 * Date: 4/26/14
 * Time: 8:29 AM
 * Project: media.transmap.com
 */

function page($name){
    if(preg_match("@$name@i",basename($_SERVER['PHP_SELF']))){
        echo ' class="active" ';
    }
}
?>

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
                <li <?php page('index'); ?> ><a href="index.php">Home</a></li>

                <li <?php page('services'); ?> ><a href="services.php">Services</a></li>
                <li <?php page('about'); ?> ><a href="about.php">About</a></li>
                <li <?php page('news'); ?> ><a href="news.php">News</a></li>
                <li <?php page('contact'); ?> ><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</div>