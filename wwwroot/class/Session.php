<?php

class SessionInfo{}

class Session{
	
	
	
	private static $SESS_NAME = 'session';
	private static $MESSAGES = 'messages';
	private static $CREATED = 'created';
	
	private $info;
	
	private static $instance;
	
	private $registered = array();
	
	/**
	 * Setup the Session Class
	 */
	private function __construct(){
		session_start();
		
		$this->info = new SessionInfo();
		$this->register($this->info);
	}
	
	/**
	 * Singleton
	 */
	public static function &getInstance(){
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
		
		if(!$this->isRegistered($obj)){
			$_SESSION[$name] = null;
			$this->registered[$name] = true;
			$this->setNameValue($this->info, $name, array());
			$this->setChildNameValue($this->info, $name, self::$CREATED, time());
		}
	}
	
	/**
	 * Check if the object is already registered
	 * @param unknown_type $obj
	 */
	public function isRegistered($obj){
		$name = get_class($obj);
		
		if(isset($_SESSION[$name]))
			return true;
		
		return false;
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
	
	public function setChildNameValue($obj, $parent, $name, $value, &$session=null){
		if($session === null)
			$session = &$this->getSession($obj);
				
		if(!is_array($session)) return;
		
		if(isset($session[$parent])){
			$session[$parent][$name] = $value;
			return;
		} else {
			foreach($session as $key=>$val){
				$this->setChildNameValue(null, $parent, $name, $value, $session[$key]);
			}
		}
				
	}
	
	public function getChildValue($obj, $parent, $name, &$session=null){
		if($session === null)
			$session = &$this->getSession($obj);
		
		if(empty($session) || !is_array($session)) return;
		
		//the parent is in this array
		if(isset($session[$parent])){
			return (isset($session[$parent][$name])) ? $session[$parent][$name] : null;
			
		//gotta look deeper
		}else{
			foreach($session as $key=>$val){
				$result = $this->getChildValue($obj, $parent, $name, $session[$key]);
				
				if($result !== null) return $result;
			}
		}
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
	
	public function getCreatedValue($obj){
		if(!$this->isRegistered($obj))
			$this->register($obj);
		
		$objname = get_class($obj);
		$info = &$this->getSession($this->info);
		
		return $this->getChildValue(null, $objname, self::$CREATED, $info);
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
	
	
	public function clearRegistered(){
		foreach($this->registered as $key=>$value){
			unset($_SESSION[$key]);
		}
	}
}