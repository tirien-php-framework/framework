<?php
class Path{

	public static $urlProtocol;
	private static $urlBase;
	private static $fwRoot;
	private static $folders = Array(
		'css'		=> '/public/css',
		'docs'		=> '/public/docs',
		'videos'	=> '/public/videos',
		'images'	=> '/public/images',
		'scripts'	=> '/public/scripts',
		'skin' 		=> '/public/skins'
	);
	
	public static function init(){
		global $_config;
		global $_fwRoot;

		$url_root = trim( $_config['system']['url_root'], '/' );
		$url_root = explode( "://", $url_root ); var_dump($url_root);
		$url_root = !empty($url_root[1]) ? $url_root[1] : $url_root[0];
		
		self::$urlProtocol = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' ) . '://';
		self::$urlBase = self::$urlProtocol.$url_root;			
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
	
	public static function urlRoot($attachment=''){
		return trim( self::$urlBase.'/'.trim($attachment,'/'), '/');
	}
		
}

?>