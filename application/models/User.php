<?php 

require(dirname(__FILE__).'/'.'Query_Builder.php');

/**
 * Class Model_User
 */

Class Model_User extends Query_Builder
{
    /**
     * @var string
     */
    public $tablePrefix = "";

    /**
     * Get an active User from database
     *
     * @param $data
     * @return mixed
     */
    public function getUser($data )
	{
		return DB::query( "SELECT * FROM user WHERE username=:username AND password_hash=:password_hash AND status=1", $data, true );		
	}

    /**
     * Create User in database
     *
     * @param $data
     * @return false
     */
    public function createUser($data)
	{
		if( !empty($data['username']) && !empty($data['password']) ) {
			$user_exists = DB::query( "SELECT * FROM user WHERE username=?", $data['username'] );
			
			if( !empty($user_exists) ) return false;
			
			$data['password_hash'] = Auth::hash( $data['password'] );
			$data['dti'] = date('Y-m-d H:i:s');
			unset( $data['password'] );
			
			return DB::insert("user", $data);
		}
		else{
			return false;
		}
	}

    /**
     * Update User password in database
     *
     * @param $data
     * @return bool
     */
    public function changePassword($data){

		if( !empty($data['username']) && !empty($data['password']) ) {
			
			Auth::logout();

			$set['password_hash'] = Auth::hash( $data['password'] );
			
			$set['remember_me_token'] = null;
			$set['remember_me_ip'] = null;

			return DB::update("user", $set, array("username" => $data['username']));
		}
		else{
			return false;
		}
	}
	
}
