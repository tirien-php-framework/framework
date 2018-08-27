<?php
/**
 *	Authorization Library
 */
class Auth{

	private static $entity_object;
	private static $entity_get_method;
	private static $entity_secure_get_method;
	private static $loginPageUrl;
	private static $return_as_array;
	private static $session_var_name;
	private static $config;

	static function init(){
		global $_config;

		self::$config = $_config;
		
		// customize options self
		self::$entity_object = new Model_User();
		self::$entity_get_method = 'getUser';
		self::$entity_secure_get_method = 'getUser';
		self::$loginPageUrl = Path::urlBase('admin/login');
		self::$return_as_array = true;
		self::$session_var_name = 'AuthLib_user_data_'.md5(self::$config['application']['salt']);
	}

	/**
	 * @param string $username
	 * @param string $password
	 * @return boolan
	 */
	static function login( $username, $password, $remember_me = false )
	{
		if( isset($username) && isset($password) ){
		
			$data['username'] = $username;
			$data['password_hash'] = self::hash( $password );
			
			$rs = self::$entity_object->{self::$entity_secure_get_method}( $data );
			
			if( !empty($rs) ) {
				self::saveSessionData($rs);

				if ($remember_me) {
					self::rememberMe();
				}

				return true;
			}
			else{
				Alert::set('error','Wrong credentials');
				return false;
			}
		}
		else{
			Alert::set('error','Mandatory fields can\'t be empty');
			return false;
		}
		
	}
	
	static function check()
	{
		if( !empty( $_SESSION[self::$session_var_name] ) ){
			return true;			
		}
		else if (isset($_COOKIE["Remember_me_".$_SERVER['SERVER_NAME']])) {
			// CHECK REMEMBER ME
			$user = self::$entity_object->where('remember_me_token', $_COOKIE["Remember_me_".$_SERVER['SERVER_NAME']]);

			if (!empty($user) && $user[0]['remember_me_ip'] == $_SERVER['REMOTE_ADDR']) {
				// LOG ADMIN IN
				$_SESSION['AuthLib_user_data_'.md5(self::$config['application']['salt'])] = $user[0];

				return true;
			}
		}
		else{
			return false;			
		}
	}
	
	static function refreshData()
	{
		if (isset($_SESSION[self::$session_var_name])) {
			$user = self::$entity_object->{self::$entity_get_method}( self::data('id') );
			return self::saveSessionData($user);
		}
		else{
			return false;
		}
	}

	private static function rememberMe()
	{
		$rememberMeToken = substr(md5(rand()), 0, 60);

		$data = array(
			'remember_me_token' => $rememberMeToken,
			'remember_me_ip' => $_SERVER['REMOTE_ADDR'],
		);

		setcookie("Remember_me_".$_SERVER['SERVER_NAME'], $rememberMeToken, time() + (86400 * 5), "/"); // EXPIRE IN 5 DAYS

		return self::$entity_object->update(self::data('id'), $data);
	}

	private static function saveSessionData($user)
	{
		$_SESSION[self::$session_var_name] = $user;
		$_SESSION[self::$session_var_name]['csrf_token'] = sha1(time().rand());

		// for JS CSRF use set cookie
		setcookie('csrf_token', $_SESSION[self::$session_var_name]['csrf_token'], 0, '/');

		return $_SESSION[self::$session_var_name];
	}
	
	static function logout()
	{
		// REMOVE REMEMBER ME
		if (isset($_COOKIE["Remember_me_".$_SERVER['SERVER_NAME']])) {
			setcookie("Remember_me_".$_SERVER['SERVER_NAME'], "", time() - 3600, '/'); // DELETE COOKIE
		}

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
		$salt = isset(self::$config['application']['salt']) ? self::$config['application']['salt'] : '';
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

	static function checkCSRF()
	{
		if( !empty($_SESSION[self::$session_var_name]['csrf_token']) && !empty($_POST) ){
			if( empty($_POST['csrf_token']) || ($_POST['csrf_token'] != $_SESSION[self::$session_var_name]['csrf_token']) ){
				
				mail("mladen@tirien.com", "Possible CSRF Attack", json_encode($_POST).json_encode($_SERVER));
				die();
				
			}
		}
	}

	static function getCSRFtoken()
	{
		return !empty($_SESSION[self::$session_var_name]['csrf_token']) ? $_SESSION[self::$session_var_name]['csrf_token'] : null;
	}
		
}

Auth::init();

?>
