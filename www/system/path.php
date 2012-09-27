<?php
class Path{

	public static $urlProtocol;
	public static $urlPort;
	public static $urlBase;
	public static $urlUri;
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

		self::$urlProtocol = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' );
		self::$urlPort = $_SERVER['SERVER_PORT']=='80' ? '' : $_SERVER['SERVER_PORT'];
		
		$url_root = trim( $_config['system']['url_root'], '/' );
		$url_root = explode( "://", $url_root );
		$url_root = !empty($url_root[1]) ? $url_root[1] : $url_root[0];	
		$url_root_segments = explode( "/", $url_root );
		$url_root = $url_root_segments[0];
		
		if( count($url_root_segments)>1 ){
			unset($url_root_segments[0]);
			self::$urlUri = implode( "/", $url_root_segments );
		}
		else {
			self::$urlUri = '';
		}
		
		$url_root = explode( ":", $url_root );

		self::$urlPort = count($url_root)>1 ? $url_root[1] : self::$urlPort;
		$url_root = $url_root[0];
		
		self::$urlBase = self::$urlProtocol . '://' . $url_root . ( empty(self::$urlPort) ? '' : ':'.self::$urlPort ) . ( empty(self::$urlUri) ? '' : '/'.self::$urlUri );
		
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