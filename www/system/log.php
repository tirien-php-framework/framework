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
					'http_cookie'		=>	!isset($_SERVER['HTTP_COOKIE']) ? null : $_SERVER['HTTP_COOKIE'],
					'http_host'			=>	!isset($_SERVER['HTTP_HOST']) ? null : $_SERVER['HTTP_HOST'],
					'http_user_agent'	=>	!isset($_SERVER['HTTP_USER_AGENT']) ? null : $_SERVER['HTTP_USER_AGENT'],
					'query_string'		=>	!isset($_SERVER['QUERY_STRING']) ? null : $_SERVER['QUERY_STRING'],
					'redirect_status'	=>	!isset($_SERVER['REDIRECT_STATUS']) ? null : $_SERVER['REDIRECT_STATUS'],
					'redirect_url'		=>	!isset($_SERVER['REDIRECT_URL']) ? null : $_SERVER['REDIRECT_URL'],
					'remote_addr'		=>	!isset($_SERVER['REMOTE_ADDR']) ? null : $_SERVER['REMOTE_ADDR'],
					'remote_port'		=>	!isset($_SERVER['REMOTE_PORT']) ? null : $_SERVER['REMOTE_PORT'],
					'request_method'	=>	!isset($_SERVER['REQUEST_METHOD']) ? null : $_SERVER['REQUEST_METHOD'],
					'request_uri'		=>	!isset($_SERVER['REQUEST_URI']) ? null : $_SERVER['REQUEST_URI'],
					'script_filename'	=>	!isset($_SERVER['SCRIPT_FILENAME']) ? null : $_SERVER['SCRIPT_FILENAME'],
					'server_addr'		=>	!isset($_SERVER['SERVER_ADDR']) ? null : $_SERVER['SERVER_ADDR'],
					'server_port'		=>	!isset($_SERVER['SERVER_PORT']) ? null : $_SERVER['SERVER_PORT'],
					'server_protocol'	=>	!isset($_SERVER['SERVER_PROTOCOL']) ? null : $_SERVER['SERVER_PROTOCOL'],
					'server_software'	=>	!isset($_SERVER['SERVER_SOFTWARE']) ? null : $_SERVER['SERVER_SOFTWARE'],
					'request_time'		=>	!isset($_SERVER['REQUEST_TIME']) ? null : $_SERVER['REQUEST_TIME'],
					'argc'				=>	!isset($_SERVER['argc']) ? null : $_SERVER['argc'],
					'dti'				=>date('Y-m-d H:i:s')
				));
				
			}
			
			return $_SESSION['sls_id'];
			
		}
	}
}
?>