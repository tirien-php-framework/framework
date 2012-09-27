<?php 
	class Router
	{
	
		public static $controller;
		public static $action;
		public static $param;
		
		public static function init()
		{
			global $_config;
			
			$default_controller = empty($_config['system']['default_controller']) ? 'index' : $_config['system']['default_controller'];
			
			$default_action = empty($_config['system']['default_action']) ? 'index' : $_config['system']['default_action'];
			
			
			self::$controller = empty($_REQUEST['rq_controller']) ? $default_controller : strtolower(trim($_REQUEST['rq_controller'],'/'));
			self::$action = empty($_REQUEST['rq_action']) ? $default_action : strtolower(trim($_REQUEST['rq_action'],'/'));
			self::$param = empty($_REQUEST['rq_param']) ? null : strtolower(trim($_REQUEST['rq_param']));
			
			
			
			self::$controller = explode("#",self::$controller);
			if(is_array(self::$controller)) self::$controller = self::$controller[0];
			self::$controller = explode("?",self::$controller);
			if(is_array(self::$controller)) self::$controller = self::$controller[0];
			
			self::$action = explode("#",self::$action);
			if(is_array(self::$action)) self::$action = self::$action[0];
			self::$action = explode("?",self::$action);
			if(is_array(self::$action)) self::$action = self::$action[0];
			
			self::$param = explode("#",self::$param);
			if(is_array(self::$param)) self::$param = self::$param[0];
			self::$param = explode("?",self::$param);
			if(is_array(self::$param)) self::$param = self::$param[0];
			

			
			$request_url = trim( Path::$urlProtocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], "/" );
			
			$get_string = trim( strstr($request_url, "?" ), "?" );
			parse_str( $get_string, $get_string );
			$_GET = array_merge( $_GET, $get_string );
			
			if( !empty($get_string) ) {
				$request_url_array =  explode( "?", $request_url );
				$request_url =  $request_url_array[0];
			}

			
			// REDIRECTIONS
			$redirects = parse_ini_file('./application/configs/redirects.ini', true);
			
			foreach($redirects as &$redirect){
				if( !empty($redirect['uri']) ){
					$redirect_url = trim( Path::urlRoot().'/'.$redirect['uri'], "/" );

					if( $request_url == $redirect_url ){
					
						self::$controller = !empty($redirect['controller']) ? $redirect['controller'] : self::$controller ;
						
						self::$action = !empty($redirect['action']) ? $redirect['action'] : self::$action ;
						
						self::$param = !empty($redirect['param']) ? $redirect['param'] : self::$param ;
						
					}
				}
			}
			
			if(self::$controller=="event"){
				self::$controller = "subscribe";
				self::$param = self::$action;
				self::$action = "index";
			}
			// END REDIRECTIONS
			
		}
		
	}
?>