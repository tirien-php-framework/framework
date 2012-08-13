<?php 
	class Router
	{
		public static $controller;
		public static $action;
		public static $param;
		
		public static function init($default_controller='index', $default_action='index')
		{
			self::$controller = empty($_REQUEST['rq_controller']) ? $default_controller : strtolower(trim($_REQUEST['rq_controller'],'/'));
			self::$action = empty($_REQUEST['rq_action']) ? $default_action : strtolower(trim($_REQUEST['rq_action'],'/'));
			self::$param = empty($_REQUEST['rq_param']) ? null : strtolower(trim($_REQUEST['rq_param']));
			
			
			self::$controller = explode("#",self::$controller);
			if(is_array(self::$controller)) self::$controller = self::$controller[0];
			
			self::$action = explode("#",self::$action);
			if(is_array(self::$action)) self::$action = self::$action[0];
			
			self::$param = explode("#",self::$param);
			if(is_array(self::$param)) self::$param = self::$param[0];
			
			
			// REDIRECTIONS
			if(self::$controller=="event"){
				self::$controller = "subscribe";
				self::$param = self::$action;
				self::$action = "index";
			}
			// END REDIRECTIONS
		}
		
	}
?>