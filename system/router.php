<?php 
	class Router
	{
	
		public static $controller;
		public static $action;
		public static $rq_controller;
		public static $rq_action;
		public static $params;
		
		public static function init()
		{
			global $_config;
			
			$default_controller = empty($_config['system']['default_controller']) ? 'index' : $_config['system']['default_controller'];
			
			$default_action = empty($_config['system']['default_action']) ? 'index' : $_config['system']['default_action'];
			
			
			self::$controller = !isset($_REQUEST['rq_controller']) ? $default_controller : strtolower(trim($_REQUEST['rq_controller'],'/'));
			self::$action = !isset($_REQUEST['rq_action']) ? $default_action : strtolower(trim($_REQUEST['rq_action'],'/'));
			self::$params = !isset($_REQUEST['rq_params']) ? null : strtolower(trim($_REQUEST['rq_params']));
			
			
			self::$rq_controller = self::$controller;
			self::$rq_action = self::$action;
			
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
			
			if(!empty(self::$params)) self::$params = explode("#",self::$params);
			if(is_array(self::$params)) self::$params = self::$params[0];
			if(!empty(self::$params)) self::$params = explode("?",self::$params);
			if(is_array(self::$params)) self::$params = self::$params[0];
			if(!empty(self::$params)) self::$params = explode("/",self::$params);
			self::$params = is_array(self::$params) ? self::$params : array(self::$params);


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
			
			
			// CUSTOM ROUTES
			$custom_routes = parse_ini_file('configs/custom_routes.ini', true);
			
			foreach($custom_routes as $custom_route){
				if( !empty($custom_route['uri']) ){
					$custom_route_url = trim( Path::urlBase().'/'.$custom_route['uri'], "/" );

					if( $request_url == $custom_route_url ){
					
						self::$controller = isset($custom_route['controller']) ? $custom_route['controller'] : self::$controller ;
						
						self::$action = isset($custom_route['action']) ? $custom_route['action'] : self::$action ;
						
						self::$params = isset($custom_route['params']) ? $custom_route['params'] : self::$params ;
						
					}
				}
			}
			// END REDIRECTIONS
			
		}
		
		public static function go( $uri )
		{
			header( "Location: " . ( strpos($uri,"http") === 0 ? $uri : Path::$urlBase."/".$uri ) );
			die();
		}

		public static function back( $query = null )
		{
			$query = is_array($query) || is_object($query) ? http_build_query($query) : $query;
			
			header( "Location: ".$_SERVER['HTTP_REFERER'] . (!empty($query) ? '?'.$query : '') );
			die();
		}
		
	}
?>
