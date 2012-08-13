<?php
class Log {
	private static $db = null;
	
	public static function init() {
		self::$db = new DB;
	}
	
	public static function session(){
		global $_config;
		
		if ($_config['system']['log_session']){
		
			if( empty($_SESSION['sls_id']) ) {
			
				if (empty($db)) self::init();
				
				$_SESSION['sls_id'] = self::$db->insert('sys_log_session',array(
					'session_id'=>session_id(),
					'http_cookie'=>empty($_SERVER['HTTP_COOKIE']) ? null : $_SERVER['HTTP_COOKIE'],
					'http_host'=>$_SERVER['HTTP_HOST'],
					'http_user_agent'=>$_SERVER['HTTP_USER_AGENT'],
					'query_string'=>$_SERVER['QUERY_STRING'],
					'redirect_status'=>$_SERVER['REDIRECT_STATUS'],
					'redirect_url'=>$_SERVER['REDIRECT_URL'],
					'remote_addr'=>$_SERVER['REMOTE_ADDR'],
					'remote_port'=>$_SERVER['REMOTE_PORT'],
					'request_method'=>$_SERVER['REQUEST_METHOD'],
					'request_uri'=>$_SERVER['REQUEST_URI'],
					'script_filename'=>$_SERVER['SCRIPT_FILENAME'],
					'server_addr'=>$_SERVER['SERVER_ADDR'],
					'server_port'=>$_SERVER['SERVER_PORT'],
					'server_protocol'=>$_SERVER['SERVER_PROTOCOL'],
					'server_software'=>$_SERVER['SERVER_SOFTWARE'],
					'request_time'=>$_SERVER['REQUEST_TIME'],
					'argc'=>empty($_SERVER['argc']) ? null : $_SERVER['argc'],
					'dti'=>date('Y-m-d H:i:s')
				));
				
			}
			
			return $_SESSION['sls_id'];
			
		}
	}
}
?>