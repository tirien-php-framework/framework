<?php

	/* Tirien Web Framework */
	/* Version 1 */
	/* Build 20120813 */
	/* www.tirien.com */
	
	$_config = parse_ini_file('application/configs/application.ini', true);
	$_fwRoot = dirname(__FILE__);
	
	$_debug = $_config['system']['debug'] && in_array($_SERVER['REMOTE_ADDR'],$_config['system']['development_ip']) ? true : false;
	
	if($_debug){
		ini_set('display_errors', 1);
		ini_set('error_reporting', E_ALL);
	}
	else{
		ini_set('display_errors', 0);
		ini_set('error_reporting', null);
	}
	
	
	
	/* IF SITE IS ON MAINTENANCE */
	if(
		$_config['system']['maintenance'] && 
		!in_array($_SERVER['REMOTE_ADDR'],$_config['system']['development_ip'])
	){
		include('application/views/maintenance.htm');
		die();
	}

	
	
	/* GET ALL INCLUDES */
	$folder_names = array('system','application/functions');
	foreach($folder_names as $folder_name) {
		if ($handle = opendir('./'.$folder_name.'/')) {
			while ( ($file = readdir($handle)) !== false ) {
				if ( $file != "." && $file != ".." ) {
					$path = $folder_name.'/'.$file;
					$info = pathinfo($path);
					if ($info['extension']=='php'){
						include_once($path);
					}						
				}
			}
			closedir($handle);
		}
	}
	function __autoload($class_name) {
		if( stristr($class_name,"Model_") ){
			include 'application/model/'.$class_name.'.php';
		}
		else{
			include 'application/library/'.$class_name.'.php';
		}
	}
	
	
	
	/* URL CLASS */
	Url::setVars($_config, $_fwRoot);

	
	
	/* DISABLE MAGIC QUOTES */
	disableMagicQuotes();


	
	/* GET REQUEST */
	session_start(); 
	Log::session();
	Router::init(
		$_config['system']['default_controller'], 
		$_config['system']['default_action']
	);
	
	
	
	/* RUN SYSTEM */
	if( file_exists('application/controllers/'.Router::$controller.'.php') ){
		require_once('application/controllers/'.Router::$controller.'.php');
		$system_rq_controller = ucfirst(Router::$controller).'Controller';
		$system_rq_action = Router::$action.'Action';
		$system = new $system_rq_controller;
	}
	else{
		pageNotFound();
	}
	
	if (method_exists($system,$system_rq_action)){
		$system->setVars(Router::$controller, Router::$action, $_config['system']['default_layout']);
		$system->init();
		$system->$system_rq_action();
		$system->run();
	}
	else{
		pageNotFound();	
	}
	
?>