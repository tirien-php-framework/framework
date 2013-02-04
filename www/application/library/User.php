<?php

class User{
	//todo:srediti docs za ovaj lib
	public static $data = null;
		
	public static function login( $data = null )
	{
		//todo:remember me funtionality
		if( isset($data['username']) && isset($data['password']) ){
		
			$data['password_hash'] = self::hash( $data['password'] );
			unset( $data['password'] );
			
			$user_model = new UserModel();
			$rs = $user_model->getUser( $data );
			
			if( !empty($rs[0]) ) {
				self::$data = $_SESSION['user_data'] = $rs[0];
				return true;
			}
			else{
				sleep(5);
				return false;
			}
			
		}
		else{
			return false;
		}
		
	}
	
	public static function logged()
	{
	
		if( !empty( $_SESSION['user_data'] ) ){
			self::$data = $_SESSION['user_data'];
			return true;			
		}
		else{
			self::$data = null;
			return false;			
		}

	}
	
	public static function logout()
	{
		unset( $_SESSION['user_data'] );
		self::$data = null;
		return true;	
	}
	
	public static function hash( $string ){	
		global $_config;
		return sha1($_config['application']['salt'].$string);	
	}
		
	public static function create( $data ){	
		$user = new UserModel();
		return !empty($data) ? $user->createUser($data) : false;
	}
	
}

?>