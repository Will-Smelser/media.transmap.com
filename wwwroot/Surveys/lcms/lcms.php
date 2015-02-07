<!DOCTYPE html>
<html>
<head>

    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/css.html'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/js.html'; ?>

    <script src="/js/cookie.js" ></script>

    <script type="text/javascript" src="../../js/raphael-min.js"></script>

</head>
<body>
<div class="container" id="container">

    <?php include '../../includes/html/header.html'; ?>

    <div id="paper" style="border:solid black 2px;background-image: url('http://media2.transmap.com/images/LCMS/EMPO/100514/1/LcmsResult_OverlayInt_000019.jpg')"></div>

    <script>
    <?php
    include 'Parser.php';
    $parser = new Parser('data/LcmsResult_000019.xml');
    ?>

    var paper = Raphael("paper", <?php echo $parser->getPageWidth() ?>, <?php echo $parser->getPageHeight() ?>);

    for(var x in cracks){
        var path = paper.path(cracks[x]);
        path.click(function(){alert('clicked');});
    }
    </script>

<?php
include '../../includes/html/footer.html';
?>