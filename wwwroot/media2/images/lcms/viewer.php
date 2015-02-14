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

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/css.html'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/js.html'; ?>

    <script src="/js/cookie.js" ></script>

    <script type="text/javascript" src="/js/raphael-min.js"></script>


</head>
<body>
<div class="container" id="container">

    <?php //include '../../includes/html/header.html'; ?>

    <div style="position: relative;z-index:9999">
        <div id="zoomIn" style="cursor:pointer;width:20px;height:20px;background-color:#FFF;border:solid black 5px;font-size:24px;position:absolute;top:25px;left:20px"><b>+</b></div>
        <div id="zoomOut" style="cursor:pointer;width:20px;height:20px;background-color:#FFF;border:solid black 5px;font-size:24px;position:absolute;top:65px;left:20px"><b>-</b></div>
    </div>

    <div id="paper" style="overflow: hidden; border:solid black 2px;"></div>

    <script>
    <?php
    include 'Parser.php';
    include 'Lookup.php';

    $parser = new Parser(Lookup::findXml('osceola',10315,2,0),1024,2500,.395,true);

    $parts = explode('/',$_SERVER['REQUEST_URI']);

    //move from right
    ?>

    var height = 416;
    var width =  1000;
    var paper = Raphael("paper", width, height);

    paper.image('http://media.transmap.local/media2/images/lcms/images/image.php?path=Osceola/010315/2/000000&maxWidth=1000',0,0,width,height);

    paper.setViewBox(0, 0, width, height );

    // Setting preserveAspectRatio to 'none' lets you stretch the SVG
    paper.canvas.setAttribute('preserveAspectRatio', 'none');

    $('#zoomIn').click(function(){
        width = width - 100;
        height = height - 100;
        console.log("hello",width,height);
        //$('#paper').attr('width', width).attr('height', height);
        paper.setViewBox(0,0,width,height,false);
    });

    $('#zoomOut').click(function(){
        width = width + 100;
        height = height + 100;
        console.log("hello",width,height);
        //$('#paper').attr('width', width).attr('height', height);
        paper.setViewBox(0,0,width,height,false);
    });

    $('#paper')

    for(var x in cracks){
        //need a closure
        (function(data){
            var path = paper.path(data.path);
            path.attr("stroke-width", "10");
            path.attr("opacity",0);
            path.data("with",data.width);
            path.data("height",data.height);

            path.hover(function(){
                this.g = path.glow({
                        color: '#ff0',
                        width: 15
                    });
            },function(){
                this.g.remove();
            });
            path.click(function(){
                alert('width: '+data.width+"\ndepth: "+data.depth);
            });
        })(cracks[x]);
    }

    </script>

<?php
//include '../../includes/html/footer.html';
?>