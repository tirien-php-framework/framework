<?php
class Path{

	private static $baseUrl;
	private static $fwRoot;
	private static $folders = Array(
		'css'		=> '/css',
		'docs'		=> '/docs',
		'videos'	=> '/videos',
		'images'	=> '/images',
		'scripts'	=> '/scripts',
		'skin' 		=> '/skins'
	);
	
	public static function init(){
		global $_config;
		global $_fwRoot;

		$protocol_pos = strpos($_config['system']['url_root'], 'http');
		if( $protocol_pos===false || $protocol_pos>5 ){
			$protocol = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' ) . '://';
		}
		else {
			$protocol = '';
		}
		
		self::$baseUrl = $protocol . trim($_config['system']['url_root'], '/');			
		self::$fwRoot = $_fwRoot;
	}
	
	public static function skin($attachment=''){
		return trim( self::$baseUrl.self::$folders['skin'].'/'.trim($attachment,'/'), '/');
	}
	
	public static function script($attachment=''){
		return trim( self::$baseUrl.self::$folders['scripts'].'/'.trim($attachment,'/'), '/');
	}
	
	public static function css($attachment=''){
		return trim( self::$baseUrl.self::$folders['css'].'/'.trim($attachment,'/'), '/');
	}
	
	public static function doc($attachment=''){
		return trim( self::$baseUrl.self::$folders['docs'].'/'.trim($attachment,'/'), '/');
	}	
	
	public static function video($attachment=''){
		return trim( self::$baseUrl.self::$folders['video'].'/'.trim($attachment,'/'), '/');
	}	
	
	public static function image($attachment=''){
		return trim( self::$baseUrl.self::$folders['images'].'/'.trim($attachment,'/'), '/');
	}
	
	public static function appRoot($attachment=''){
		return trim( self::$fwRoot.'/'.trim($attachment,'/'), '/');
	}
	
	public static function urlRoot($attachment=''){
		return trim( self::$baseUrl.'/'.trim($attachment,'/'), '/');
	}
		
}

?>