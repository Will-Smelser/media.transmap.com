<?php
include 'includes/header.php';
include 'class/Login.php';

session_start();

Login::interceptRequests();

if(Login::hasMessage()){
?>
<div class="message">
	<?php echo Login::getMessage(); Login::clearMessage(); ?>
</div>

<?php } ?>
<div class="container">
<form action="login.php" method="POST">
	<label for"username">
		<span style="display:inline-block;width:200px;">Username:</span>
		<input id="usrename" name="username" type="text"/>
	</label><br/>
	<label for"password">
		<span  style="display:inline-block;width:200px;">Password:</span>
		<input id="password" name="password" type="password" />
	</label><br/>
	<input type="hidden" value="login" name="action" />
	<input type="submit" value="submit" />
</form>
</div>
<?php 
include 'includes/footer.php';
?>