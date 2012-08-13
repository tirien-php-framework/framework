<?php
class Url{

	private static $baseUrl;
	private static $fwRoot;
	private static $folders = Array(
		'css'		=> '/css',
		'docs'		=> '/docs',
		'images'	=> '/images',
		'scripts'	=> '/scripts',
		'skin' 		=> '/skins'
	);
	
	public static function setVars($config, $root){
		self::$baseUrl = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' ).'://'.$config['system']['domain'];			
		self::$fwRoot = $root;
	}
	
	public static function skin($attachment=''){
		return self::$baseUrl.self::$folders['skin'].'/'.ltrim($attachment,'/');
	}
	
	public static function script($attachment=''){
		return self::$baseUrl.self::$folders['scripts'].'/'.ltrim($attachment,'/');
	}
	
	public static function css($attachment=''){
		return self::$baseUrl.self::$folders['css'].'/'.ltrim($attachment,'/');
	}
	
	public static function doc($attachment=''){
		return self::$baseUrl.self::$folders['docs'].'/'.ltrim($attachment,'/');
	}	
	
	public static function image($attachment=''){
		return self::$baseUrl.self::$folders['images'].'/'.ltrim($attachment,'/');
	}
	
	public static function appRoot($attachment=''){
		return self::$fwRoot.'/'.ltrim($attachment,'/');
	}
	
	public static function domain($attachment=''){
		return self::$baseUrl.'/'.ltrim($attachment,'/');
	}
		
}

?>