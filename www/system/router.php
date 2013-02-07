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
			
			
			self::$controller = !isset($_REQUEST['rq_controller']) ? $default_controller : strtolower(trim($_REQUEST['rq_controller'],'/'));
			self::$action = !isset($_REQUEST['rq_action']) ? $default_action : strtolower(trim($_REQUEST['rq_action'],'/'));
			self::$param = !isset($_REQUEST['rq_param']) ? null : strtolower(trim($_REQUEST['rq_param']));
			
			
			self::$controller = str_replace( array('-','_'), '', self::$controller );
			self::$action = str_replace( array('-','_'), '', self::$action );

			
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
			

			$request_url = trim( Path::$urlProtocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], "/" );
			
			
			// GET PARAMS
			$get_string = trim( strstr($request_url, "?" ), "?" );
			parse_str( $get_string, $get_string );
			$_GET = array_merge( $_GET, $get_string );
			$_REQUEST = array_merge( $_REQUEST, $get_string );
			
			if( !empty($get_string) ) {
				$request_url_array =  explode( "?", $request_url );
				$request_url =  $request_url_array[0];
			}
			// END GET PARAMS
			
			
			// REDIRECTIONS
			$redirects = parse_ini_file('./application/configs/redirects.ini', true);
			
			foreach($redirects as &$redirect){
				if( !empty($redirect['uri']) ){
					$redirect_url = trim( Path::urlBase().'/'.$redirect['uri'], "/" );

					if( $request_url == $redirect_url ){
					
						self::$controller = isset($redirect['controller']) ? $redirect['controller'] : self::$controller ;
						
						self::$action = isset($redirect['action']) ? $redirect['action'] : self::$action ;
						
						self::$param = isset($redirect['param']) ? $redirect['param'] : self::$param ;
						
					}
				}
			}
			// END REDIRECTIONS
			
		}
		
		public static function go( $uri )
		{
			header( "Location: ".Path::$urlBase."/".$uri );
			die();
		}
		
	}
?>