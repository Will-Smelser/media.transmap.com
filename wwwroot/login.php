<?php
include 'class/Login.php';

session_start();
Login::interceptRequests();
?>
<!DOCTYPE html>
<html>
<head>

<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/css.html'; ?>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/html/js.html'; ?> 

  <script>
	$(document).ready(function(){

		$('input:button').button();
		$('input:submit').button();
		
	});
  </script>
  
  </head>
  <body>  
  


<div id="container" class="container">

<?php include 'includes/html/header.html'; ?>

<?php 
if(Login::hasMessage()){
	$msg = Login::getMessage();
	Login::clearMessage();
	echo  "<div class='message'>$msg</div>";
} 
?>
	
<form action="login.php" method="POST">
	<label for="username">
		<span style="display:inline-block;width:200px;">Username:</span>
		<input id="usrename" name="username" type="text"/>
	</label><br/>
	<label for="password">
		<span  style="display:inline-block;width:200px;">Password:</span>
		<input id="password" name="password" type="password" />
	</label><br/>
	
	<input type="hidden" value="login" name="action" />
	<input type="submit" value="submit" />
</form>

</div>
<?php 
include 'includes/html/footer.html';
?>