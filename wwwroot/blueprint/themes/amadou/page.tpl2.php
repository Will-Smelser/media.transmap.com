<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language ?>" lang="<?php print $language ?>">
  <head>
    <title><?php print $head_title ?></title>
    <?php print $head ?>
    <?php print $styles ?>
    <?php print $scripts ?>
    <link rel="stylesheet" media="screen" href="style.css" />

      <!--[if IE 6]>
        <style type="text/css" media="all">@import "<?php print base_path() . path_to_theme() ?>/ie-fixes/ie6.css";</style>
      <![endif]-->
      <!--[if lt IE 7.]>
        <script defer type="text/javascript" src="<?php print base_path() . path_to_theme() ?>/ie-fixes/pngfix.js"></script>
      <![endif]-->
  </head>

  <body>

    <!-- begin container -->
    <div id="container">

      <!-- primary links -->
      <div id="menu">
        <?php if (isset($primary_links)) : ?>
          <?php print theme('links', $primary_links) ?>
        <?php endif; ?>
      </div><!-- end primary links -->

      <!-- begin header -->

    
    
    
    
    
  											<div id="daddy">
	<div id="header">
		<div id="logo"><a href="/"><img src="images/circle.gif" alt="Transmap Company Logo" width="318" height="85" /></a><span id="logo-text"><a href="index.html"></a></span></div><!-- logo -->
		<div id="menu">
			<ul>
				<li><a href="/" id="active">Home</a></li>
				<li><a href="http://projects.transmap.com/">Projects</a></li>
				<li><a href="http://projects.transmap.com/docs/?q=node/112">Contact Us</a></li>
				<li><a href="http://projects.transmap.com/docs/?q=contact">Suport</a></li>
				<li><a href="sitemap.html">Learn</a></li>
				<li><a href="http://projects.transmap.com/docs/?q=node/75">Demos</a></li>
			</ul>
		</div><!-- menu -->
		<div id="ticker">
			Helping you turn data into knowledge.
		</div><!-- ticker -->
		<div id="headerimage">
			 <div id="download"><span id="download-text"><a href="#">Visit our Projects Site<br />Blogs, Forums, and More!</a></span></div> 
			<!-- download -->
			<div id="icons">
				<a href="index.html" ><img src="images/icon_home.gif" alt="Home" width="13" height="13" id="home" /></a>
				<a href="sitemap.html"><img src="images/icon_sitemap.gif" alt="Sitemap" width="13" height="13" id="sitemap" /></a>
				<a href="http://projects.transmap.com/docs/?q=node/112"><img src="images/icon_contact.gif" alt="Contact" width="13" height="13" id="contact" /></a>			</div><!-- icons -->
			<div id="slogan">Roadway Inventory. Asset Management. Integration.</div>
		</div>
		<!-- headerimage -->
	</div>
	<!-- header -->  
    
    
    
    
    
    
    <!-- end header -->

      <!-- content -->

      <!-- begin mainContent -->
      <div id="mainContent" style="width: <?php print amadou_get_mainContent_width( $sidebar_left, $sidebar_right) ?>px;">
        
        <?php if ($mission): print '<div class="mission">'. $mission .'</div>'; endif; ?>
        <?php if ($breadcrumb): print '<div class="breadcrumb">'. $breadcrumb . '</div>'; endif; ?>
        <?php if ($title) : print '<h1 class="pageTitle">' . $title . '</h1>'; endif; ?>
        <?php if ($tabs) : print '<div class="tabs">' . $tabs . '</div>'; endif; ?>
        <?php if ($help) : print '<div class="help">' . $help . '</div>'; endif; ?>
        <?php if ($messages) : print '<div class="messages">' .$messages . '</div>'; endif; ?>
        <?php print $content_top; ?>
        <?php print $content; ?>
        <?php print $content_bottom; ?>
        <?php print $feed_icons; ?>

      </div><!-- end mainContent -->

      <!-- begin sideBars -->

      <div id="sideBars-bg" style="width: <?php print amadou_get_sideBars_width( $sidebar_left, $sidebar_right) ?>px;">
        <div id="sideBars" style="width: <?php print amadou_get_sideBars_width( $sidebar_left, $sidebar_right) ?>px;">

          <!-- left sidebar -->
          <?php if ($sidebar_left) : ?>
            <div id="leftSidebar">
              <?php print $sidebar_left; ?>
            </div>
          <?php endif; ?>
        
          <!-- right sidebar -->
          <?php if ($sidebar_right) : ?>
            <div id="rightSidebar">
              <?php print $sidebar_right; ?>
            </div>
          <?php endif; ?>

        </div><!-- end sideBars -->
      </div><!-- end sideBars-bg -->
    
      <!-- footer -->
      <div id="footer">
        <?php print $footer_message; ?>
        <?php print $footer; ?>
      </div><!-- end footer -->
    
    </div><!-- end container -->
  
    <?php print $closure ?>
<!-- Google Code -->    
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-4481117-1";
urchinTracker();
</script>
<!-- End Google Code -->

  </body>
</html>
