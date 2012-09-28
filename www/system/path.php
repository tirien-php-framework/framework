<?php
class Path{

	public static $urlProtocol;
	public static $urlPort;
	public static $urlBase;
	private static $fwRoot;
	private static $folders = Array(
		'css'		=> '/public/css',
		'docs'		=> '/public/docs',
		'videos'	=> '/public/videos',
		'images'	=> '/public/images',
		'scripts'	=> '/public/scripts',
		'skin' 		=> '/public/skins'
	);
	
	public static function init( $index_uri ){
		global $_fwRoot;
		$index_uri = trim($index_uri, "/");
		
		self::$urlProtocol = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' );
		self::$urlPort = $_SERVER['SERVER_PORT']=='80' ? '' : $_SERVER['SERVER_PORT'];

		self::$urlBase = self::$urlProtocol . '://' . trim($_SERVER['HTTP_HOST'], "/") . ( empty($index_uri) ? '' : '/'.$index_uri );
		
		self::$fwRoot = $_fwRoot;
	}
	
	public static function skin($attachment=''){
		return trim( self::$urlBase.self::$folders['skin'], '/').'/'.trim($attachment,'/');
	}
	
	public static function script($attachment=''){
		return trim( self::$urlBase.self::$folders['scripts'], '/').'/'.trim($attachment,'/');
	}
	
	public static function css($attachment=''){
		return trim( self::$urlBase.self::$folders['css'], '/').'/'.trim($attachment,'/');
	}
	
	public static function doc($attachment=''){
		return trim( self::$urlBase.self::$folders['docs'], '/').'/'.trim($attachment,'/');
	}	
	
	public static function video($attachment=''){
		return trim( self::$urlBase.self::$folders['video'], '/').'/'.trim($attachment,'/');
	}	
	
	public static function image($attachment=''){
		return trim( self::$urlBase.self::$folders['images'], '/').'/'.trim($attachment,'/');
	}
	
	public static function appRoot($attachment=''){
		return trim( self::$fwRoot.'/'.trim($attachment,'/'), '/');
	}
	
	public static function urlBase($attachment=''){
		return trim( self::$urlBase.'/'.trim($attachment,'/'), '/');
	}
		
}

?>