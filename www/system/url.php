<?php
class Url{

	public static $baseUrl;
	private static $fwRoot;
	private static $folders = Array(
		'css'		=> '/css',
		'docs'		=> '/docs',
		'images'	=> '/images',
		'scripts'	=> '/scripts',
		'skin' 		=> '/skins'
	);
	
	public static function setVars($config, $root){
		$protocol_pos = strpos($config['system']['domain'], 'http');
		if( $protocol_pos===false || $protocol_pos>5 ){
			$protocol = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' ) . '://';
		}
		else {
			$protocol = '';
		}
		
		self::$baseUrl = $protocol . trim($config['system']['domain'], '/');			
		self::$fwRoot = $root;
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
	
	public static function image($attachment=''){
		return trim( self::$baseUrl.self::$folders['images'].'/'.trim($attachment,'/'), '/');
	}
	
	public static function appRoot($attachment=''){
		return trim( self::$fwRoot.'/'.trim($attachment,'/'), '/');
	}
	
	public static function domain($attachment=''){
		return trim( self::$baseUrl.'/'.trim($attachment,'/'), '/');
	}
		
}

?>