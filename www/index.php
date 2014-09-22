<?php

/* Tirien Web Framework */
/* Version 1 */
/* $Rev$ */
/* www.tirien.com */

// Set include paths
$paths = array(
	get_include_path(),
	'application',
	'application'.DIRECTORY_SEPARATOR.'library'
	);

set_include_path( implode( PATH_SEPARATOR, $paths) );



$_config = parse_ini_file( 'configs/application.ini', true );
$_debug = $_config['system']['debug'] && in_array( $_SERVER['REMOTE_ADDR'], $_config['system']['development_ip'] ) ? true : false;

if( $_debug ){
	ini_set( 'display_errors', 1 );
	ini_set( 'error_reporting', E_ALL );
}
else{
	ini_set( 'display_errors', 0 );
	ini_set( 'error_reporting', null );
}


/* IF SITE IS ON MAINTENANCE */
if(
	$_config['system']['maintenance'] &&
	!in_array( $_SERVER['REMOTE_ADDR'], $_config['system']['development_ip'] )
){
	include( 'views'.DIRECTORY_SEPARATOR.'maintenance.htm' );
	die();
}


/* GET ALL INCLUDES */
require_once ('_beforeanyaction.php');

$folder_names = array( 'system', 'application'.DIRECTORY_SEPARATOR.'functions' );

foreach( $folder_names as $folder_name ){
	foreach( glob( $folder_name.DIRECTORY_SEPARATOR.'*.php' ) as $class ){
		require_once ( $class );
	}
}



/* AUTOLOAD */
function TirienFWAutoload( $classname ) {
	$foreign_class = 'application'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR . str_replace( array('\\', '_'), DIRECTORY_SEPARATOR, trim($classname,'\\_')) . '.php';
	if( strpos( $classname, "Model_" ) !== false ){
		include 'models'.DIRECTORY_SEPARATOR.str_replace("Model_", "", $classname).'.php';
	}
	else if( strpos( $classname, "Library_" ) !== false ){
		include 'library'.DIRECTORY_SEPARATOR.''.str_replace("Library_", "", $classname).'.php';
	}
	else if( file_exists($foreign_class) ){
		include $foreign_class;
	}
	else{
		include 'library'.DIRECTORY_SEPARATOR.$classname.'.php';
	}
}


if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
    if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
        spl_autoload_register('TirienFWAutoload', true, true);
    } else {
        spl_autoload_register('TirienFWAutoload');
    }
} else {
    // Autoload for old PHP versions
    function __autoload($classname)
    {
        TirienFWAutoload($classname);
    }
}

	

/* DISABLE MAGIC QUOTES */
disableMagicQuotes();

/* LOG SESSION */
if( empty($_SESSION) ){
	session_start(); 
}

if ($_config['system']['log_session']){
Log::session();	
}

/* INIT MULTILANGUAGE */
if (!empty($_config['system']['multilanguage'])) {
ml::init( array('en') ); 
}
	
/* INIT ROUTES */
Path::init( dirname($_SERVER['PHP_SELF']), dirname(__FILE__), $_config['application']['assets_version'] );
Router::init();



/* RUN SYSTEM */
$main_controller_path = 'application'.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.Router::$controller.'.php';
if( file_exists($main_controller_path) ){
	require_once($main_controller_path);
	$system_rq_controller = ucfirst( Router::$controller ).'Controller';
	$system_rq_action = Router::$action.'Action';
	$system = new $system_rq_controller;
}
else{
	pageNotFound();
}

if( method_exists( $system, $system_rq_action ) ){
	$system->setVars( Router::$controller, Router::$action, $_config['system']['default_layout'] );
	$system->init();
	$system->$system_rq_action();
	$system->run();
}
else{
	pageNotFound();
}

?>