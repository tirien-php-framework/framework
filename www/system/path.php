<?php
class Path{

	public static $urlProtocol;
	public static $urlPort;
	public static $urlBase;
	public static $assetsVersion;
	public static $appRoot;
	private static $folders = Array(
		'css'		=> '/public/css',
		'docs'		=> '/public/docs',
		'videos'	=> '/public/videos',
		'images'	=> '/public/images',
		'scripts'	=> '/public/scripts'
	);
	
	public static function init( $index_uri, $index_path, $assetsVersion = '' ){
		$index_uri = trim($index_uri, "/\\");
		self::$appRoot = rtrim($index_path, "/\\");
		
		self::$urlProtocol = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS']=='off' ? 'http' : 'https';
		self::$urlPort = $_SERVER['SERVER_PORT']=='80' ? '' : $_SERVER['SERVER_PORT'];
		self::$urlBase = self::$urlProtocol . '://' . trim($_SERVER['HTTP_HOST'], "/") . ( empty($index_uri) ? '' : '/'.$index_uri );
		self::$assetsVersion = empty($assetsVersion) ? '' : '?v='.$assetsVersion;
	}
	
	public static function scripts($attachment=''){
		return trim( self::$urlBase.self::$folders['scripts'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}
	
	public static function css($attachment=''){
		return trim( self::$urlBase.self::$folders['css'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}
	
	public static function docs($attachment=''){
		return trim( self::$urlBase.self::$folders['docs'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}	
	
	public static function videos($attachment=''){
		return trim( self::$urlBase.self::$folders['videos'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}	
	
	public static function images($attachment=''){
		return trim( self::$urlBase.self::$folders['images'], '/').'/'.trim($attachment,'/').self::$assetsVersion;
	}
	
	public static function appRoot($attachment=''){
		return rtrim( self::$appRoot.DIRECTORY_SEPARATOR.trim(str_replace(array('/','\\'), DIRECTORY_SEPARATOR, $attachment),'\/'), '\/');
	}
	
	public static function urlBase($attachment=''){
		return rtrim(self::$urlBase.'/'.trim($attachment,'/'), '/');
	}
	
	public static function urlQuery(){
		$uri = trim( substr( self::$urlProtocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"], strlen( self::$urlBase ) ), '/' );

		return strpos($uri,'?')!==false ? substr($uri, strpos($uri,'?')) : '';
	}

	public static function urlUri($strip_query=false){
		$uri = trim( substr( self::$urlProtocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"], strlen( self::$urlBase ) ), '/' );

		if (strpos($uri,'?')!==false && $strip_query) {
			$uri = substr($uri, 0, strpos($uri,'?'));
		}

		return $uri;
	}
	
	public static function pageUrl($strip_query=false){
		return  self::urlBase().'/'.self::urlUri($strip_query);
	}

	public static function stingToUri( $string ){
		$uri = trim($string);
		$uri = str_replace(' ', '-', $uri);
		$uri = preg_replace("/[^a-z0-9-_]+/i", "", $uri);
		$uri = strtolower($uri);

		return $uri;
	}
		
}

?>