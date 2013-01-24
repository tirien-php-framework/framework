<?php
class Path{

	public static $urlProtocol;
	public static $urlPort;
	public static $urlBase;
	public static $assetsVersion;
	private static $fwRoot;
	private static $folders = Array(
		'css'		=> '/public/css',
		'docs'		=> '/public/docs',
		'videos'	=> '/public/videos',
		'images'	=> '/public/images',
		'scripts'	=> '/public/scripts'
	);
	
	public static function init( $index_uri ){
		global $_fwRoot;
		global $_config;
		$index_uri = trim($index_uri, "/");
		
		self::$urlProtocol = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' );
		self::$urlPort = $_SERVER['SERVER_PORT']=='80' ? '' : $_SERVER['SERVER_PORT'];

		self::$urlBase = self::$urlProtocol . '://' . trim($_SERVER['HTTP_HOST'], "/") . ( empty($index_uri) ? '' : '/'.$index_uri );
		
		self::$fwRoot = $_fwRoot;
		self::$assetsVersion = empty($_config['application']['assets_version']) ? '' : '?v='.$_config['application']['assets_version'];
	}
	
	public static function script($attachment=''){
		return trim( self::$urlBase.self::$folders['scripts'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}
	
	public static function css($attachment=''){
		return trim( self::$urlBase.self::$folders['css'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}
	
	public static function doc($attachment=''){
		return trim( self::$urlBase.self::$folders['docs'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}	
	
	public static function video($attachment=''){
		return trim( self::$urlBase.self::$folders['video'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}	
	
	public static function image($attachment=''){
		return trim( self::$urlBase.self::$folders['images'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}
	
	public static function appRoot($attachment=''){
		return rtrim( self::$fwRoot.'/'.trim($attachment,'/'), '/');
	}
	
	public static function urlBase($attachment=''){
		return rtrim(self::$urlBase.'/'.trim($attachment,'/'), '/');
	}
		
}

?>