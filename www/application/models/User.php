<?php 
	Class Model_User
	{
	//todo:srediti docs za ovaj model
		public function getUser( $data )
		{
			return DB::query( "SELECT * FROM user WHERE username=:username AND password_hash=:password_hash AND status=1", $data, true );		
		}
		
		public function createUser($data)
		{
			if( !empty($data['username']) && !empty($data['password']) ) {
				$user_exists = DB::query( "SELECT * FROM user WHERE username=?", $data['username'] );
				
				if( !empty($user_exists) ) return false;
				
				$data['password_hash'] = User::hash( $data['password'] );
				$data['dti'] = date('Y-m-d H:i:s');
				unset( $data['password'] );
				
				return DB::insert("user", $data);
			}
			else{
				return false;
			}
		}
		
	}
?>