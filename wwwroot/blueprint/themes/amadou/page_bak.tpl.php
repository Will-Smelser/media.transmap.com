<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language ?>" lang="<?php print $language ?>">
  <head>
    <title><?php print $head_title ?></title>
    <?php print $head ?>
    <?php print $styles ?>
    <?php print $scripts ?>
      <!--[if IE 6]>
        <style type="text/css" media="all">@import "<?php print base_path() . path_to_theme() ?>/ie-fixes/ie6.css";</style>
      <![endif]-->
      <!--[if lt IE 7.]>
        <script defer type="text/javascript" src="<?php print base_path() . path_to_theme() ?>/ie-fixes/pngfix.js"></script>
      <![endif]-->



<style type="text/css" title="text/css">

    #tabsB {
      float:left;
      width:100%;
      background:#F4F4F4;
      font-size:120%;
      line-height:normal;
      }
    #tabsB ul {
	margin:0;
	padding:10px 10px 0 50px;
	list-style:none;
      }
    #tabsB li {
      display:inline;
      margin:0;
      padding:0;
      }
    #tabsB a {
      float:left;
      background:url("image/nav/tableftB.gif") no-repeat left top;
      margin:0;
      padding:0 0 0 4px;
      text-decoration:none;
      }
    #tabsB a span {
      float:left;
      display:block;
      background:url("image/nav/tabrightB.gif") no-repeat right top;
      padding:5px 15px 4px 6px;
      color:#666;
      }
    /* Commented Backslash Hack hides rule from IE5-Mac \*/
    #tabsB a span {float:none;}
    /* End IE5-Mac hack */
    #tabsB a:hover span {
      color:#000;
      }
    #tabsB a:hover {
      background-position:0% -42px;
      }
    #tabsB a:hover span {
      background-position:100% -42px;
      }
      
      
        #tabsC {
      float:left;
      width:100%;
      background:#F4F4F4;
      font-size:120%;
      line-height:normal;
      }
    #tabsC ul {
	margin:0;
	padding:10px 10px 0 50px;
	list-style:none;
      }
    #tabsC li {
      display:inline;
      margin:0;
      padding:0;
      }
    #tabsC a {
      float:left;
      background:url("image/nav/tableftB.gif") no-repeat left top;
      margin:0;
      padding:0 0 0 4px;
      text-decoration:none;
      }
    #tabsC a span {
      float:left;
      display:block;
      background:url("image/nav/tabrightB.gif") no-repeat right top;
      padding:5px 15px 4px 6px;
      color:#666;
      }
    /* Commented Backslash Hack hides rule from IE5-Mac \*/
    #tabsC a span {float:none;}
    /* End IE5-Mac hack */
    #tabsC a:hover span {
      color:#000;
      }
    #tabsC a:hover {
      background-position:0% -42px;
      }
    #tabsC a:hover span {
      background-position:100% -42px;
      }
      
      
      .teaser {
		width:550px;
		padding:0 0 16px 0;
		margin:10px auto;
		background:url("image/tsr1.gif") bottom left no-repeat;
	}
	.teaser h3 {
		margin:0;
		padding:7px 10px 3px 10px;
		background:url("image/tsr1.gif") top left no-repeat;
	}
	.teaser p, .teaser a.more {
		margin:0;
		padding:0 10px 3px;
		border:1px solid #d8d8d8;
		border-width:0 1px;
		background:#fff;
               font-size: 10pt;
               line-height: 110%;
	}
	.teaser a.more {
		display:block;
		text-align:right;
		background:url("image/tsr-a.gif") 410px 50% no-repeat;
		padding:0 24px 0 0;
		text-decoration:none;
		color:#44a;
	}
	.teaser a.more:hover {
		text-decoration:underline;
	}

</style>		

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

      <div id="header">

        <!-- site logo -->
        <br />
        <?php if ($logo) : ?>
          <a href="<?php print $base_path ?>" title="<?php print t('Home') ?>">
            <img class="logo" src="<?php print $logo ?>" alt="<?php print t('Home') ?>" />
          </a> 
          <a href="http://transmap.com/webinar"><img src="http://projects.transmap.com/index.html/themes/amadou/webinars.png" alt="webinars" border="0" align="left" valign="bottom" /></a>
        <?php endif; ?><!-- end site logo -->

       

      </div><!-- end header -->

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
