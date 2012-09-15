<?php
class Login{
	
	private static $USERS = array(
		"admin"=>"luxhoj4u"
	);
	
	private static $LOGIN_URL = "login.php";
	private static $LOGGED_IN_URL = "admin.php";
	private static $NAMESPACE = "LOGIN";
	private static $LOGGED_IN = "LOGGEDIN";
	private static $MESSAGE = "message";
	
	
	
	public static function protect(){
		session_start();
		
		if(!$_SESSION[self::$NAMESPACE][self::$LOGGED_IN]){
			$_SESSION[self::$NAMESPACE][self::$MESSAGE] = 'You must be logged in.'; 
			header("Location: ".self::$LOGIN_URL);
			exit;
		}
	}
	
	public static function interceptRequests(){
		if($_POST['action']=="login"){
			if(self::testLogin($_POST['username'],$_POST['password'])){
				$_SESSION[Login::$NAMESPACE][Login::$LOGGED_IN] = true;
				self::setMessage('Successfully Logged in.');
				header("Location: ".self::$LOGGED_IN_URL);
			} else {
				self::setMessage('Login Failed.');
			}
			
		}
	}
	
	public static function setMessage($message){
		$_SESSION[self::$NAMESPACE][self::$MESSAGE] = $message;
	}
	
	public static function hasMessage(){
		return (!empty($_SESSION[self::$NAMESPACE][self::$MESSAGE]));
	}
	
	public static function getMessage(){
		if(self::hasMessage()){
			return $_SESSION[self::$NAMESPACE][self::$MESSAGE];
		}
	}
	
	public static function clearMessage(){
		unset($_SESSION[self::$NAMESPACE][self::$MESSAGE]);
	}
	
	public static function testLogin($username, $password){
		if(!array_key_exists($username, self::$USERS))
			return false;
		
		if(self::$USERS[$username] == $password){
			return true;
		}
		
		return false;
	}
	
	public static function isLoggedIn(){
		if(    isset($_SESSION[self::$NAMESPACE])
			&& isset($_SESSION[self::$NAMESPACE][self::$LOGGED_IN])
		){
			return $_SESSION[self::$NAMESPACE][self::$LOGGED_IN];
		}
		return false;
	}
	
	public function logout(){
		$_SESSION[self::$NAMESPACE][self::$LOGGED_IN] = false;
		unset($_SESSION[self::$NAMESPACE]);	
	}
}