<?php
/**
 *	Authorization Library
 *	Tirien.com
 *	$Rev$
 */
class Auth{
	
	//todo:srediti docs za ovaj lib
	private static $entity_object;
	private static $entity_method;
	private static $loginPageUrl;
	private static $return_as_array;
	private static $session_var_name;

	static function init(){
		global $_config;
		
		// customize options here
		self::$entity_object = new Model_User();
		self::$entity_method = 'getUser';
		self::$loginPageUrl = Path::urlBase('admin/login');
		self::$return_as_array = true;
		self::$session_var_name = 'AuthLib_user_data_'.md5($_config['application']['salt']);
	}

	static function login( $username, $password )
	{
		//todo:remember me funtionality
		if( isset($username) && isset($password) ){
		
			$data['username'] = $username;
			$data['password_hash'] = self::hash( $password );
			
			$rs = self::$entity_object->{self::$entity_method}( $data );
			
			if( !empty($rs) ) {

				$_SESSION[self::$session_var_name] = $rs;
				$_SESSION[self::$session_var_name]['csrf_token'] = sha1(time().rand());

				// for JS CSRF use set cookie
				setcookie('csrf_token', $_SESSION[self::$session_var_name]['csrf_token'], 0, '/');

				return true;

			}
			else{
				return false;
			}
			
		}
		else{
			return false;
		}
		
	}
	
	static function check()
	{
		if( !empty( $_SESSION[self::$session_var_name] ) ){
			return true;			
		}
		else{
			return false;			
		}
	}
	
	static function logout()
	{
		unset( $_SESSION[self::$session_var_name] );
		return true;	
	}
	
	static function loginPage( $loginPageUrl = null )
	{
		self::$loginPageUrl = empty($loginPageUrl) ? self::$loginPageUrl : Path::urlBase($loginPageUrl);
		header( "Location: " . self::$loginPageUrl );
		die();
	}
	
	static function hash( $string ){	
		global $_config;
		$salt = isset($_config['application']['salt']) ? $_config['application']['salt'] : '';
		return sha1($salt.$string);	
	}

	static function data( $field = null )
	{
		if ($field !== null) {
			return isset( $_SESSION[self::$session_var_name][$field] ) ? $_SESSION[self::$session_var_name][$field] : null;
		} else {
			return isset( $_SESSION[self::$session_var_name] ) ? $_SESSION[self::$session_var_name] : null;
		}
	}
		
	static function checkCSRF(){
		
		if( !empty($_SESSION[self::$session_var_name]['csrf_token']) && !empty($_POST) ){
			if( empty($_POST['csrf_token']) || ($_POST['csrf_token'] != $_SESSION[self::$session_var_name]['csrf_token']) ){
				
				mail("mladen@tirien.com", "Possible CSRF Attack", json_encode($_POST).json_encode($_SERVER));
				die();
				
			}
		}
		
	}
		
	static function getCSRFtoken(){
		
		return !empty($_SESSION[self::$session_var_name]['csrf_token']) ? $_SESSION[self::$session_var_name]['csrf_token'] : null;
		
	}
		
}

Auth::init();

?>