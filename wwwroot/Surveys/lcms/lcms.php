<!DOCTYPE html>
<html>
<head>

    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/css.html'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/js.html'; ?>

    <script src="/js/cookie.js" ></script>

</head>
<body>
<div class="container" id="container">

    <?php include '../../includes/html/header.html'; ?>

    <?php
    include 'Parser.php';
    $parser = new Parser('data/LcmsResult_000019.xml');
    ?>

<?php
include '../../includes/html/footer.html';
?>