<?php
class Session{
	
	private static $SESS_NAME = 'session';
	private static $MESSAGES = 'messages';
	
	private static $instance;
	
	private $registered = array();
	
	private function __construct(){
		session_start();
		
		$_SESSION[self::$SESS_NAME] = array(
			self::$MESSAGES	=> array()
		);
		
	}
	
	/**
	 * Singleton
	 */
	public function &getInstance(){
		if(!self::$instance)
			self::$instance = new Session();
		
		return self::$instance;
	}
	
	/**
	 * Register the object
	 * @param $obj Object The object to register.  Only one class can be registered
	 */
	public function register($obj){
		$name = get_class($obj);
		$_SESSION[$name] = null;
		$this->registered[$name] = true;
	}
	
	/**
	 * Get a reference to the session value
	 * @param $obj Object The session value for this object
	 */
	public function &getSession($obj){
		try{
			$class = get_class($obj);
		}catch(Exception $e){
			return null;
		}
		
		if(!isset($_SESSION[$class]))
			$_SESSION[$class] = null;
			
		return $_SESSION[$class];
		
	}
	
	/**
	 * set a name vlaue pair
	 * 
	 * @param $obj Object The class object in session
	 * @param $name String The name value pair to remove
	 * @param $value Object The value to set
	 * 
	 */
	public function setNameValue($obj, $name, $value){
		$session = &$this->getSession($obj);
		$session[$name] = $value;
	}
	
	/**
	 * Convienence method to get session value
	 * @param $obj Object The class object in session
	 * @param $name String The name value pair to remove
	 */
	public function getValue($obj, $name){
		$session = &$this->getSession($obj);
		
		if(empty($session) || !isset($session[$name]))
			return null;
		
		return $session[$name]; 
	}
	
	/**
	 * remove a session name value pair
	 * @param $obj Object The class object in session
	 * @param $name String The name value pair to remove
	 */
	public function removeValue($obj, $name){
		$session = &$this->getSession($obj);
		
		if(empty($session) || !isset($session[$name]))
			return null;
		
		unset($session[$name]);
	}
	
	/**
	 * Remove object values from session
	 * @param $obj Object The object to remove session values of 
	 */
	public function removeSession($obj){
		$session = &$this->getSession($obj);
		
		if(!empty($session)){
			$class = get_class($obj);
			unset($_SESSION[$class]);
		}
	}
	
	private function &getMessages(){
		$session = &$this->getSession($this);
		return $session[self::$MESSAGES];
	}
	
	public function setMessage($message, $level=0){
		$messages = &$this->getMessages();
		$messages[$level] = $message;
	}
	
	public function getMessage($level=0){
		$messages = &$this->getMessages();
		if(!isset($messages[$level]))
			return null;
		
		return $messages[$level];
	}
	
	public function removeMessage($level=0){
		$messages = &$this->getMessages();
		unset($messages[$level]);
	}
	
	public function removeMessages(){
		$messages = &$this->getMessages();
		foreach($messages as $key=>$val){
			unset($messages[$key]);
		}
	}
	
	public function clearRegistered(){
		foreach($this->registered as $key=>$value){
			unset($_SESSION[$key]);
		}
	}
}