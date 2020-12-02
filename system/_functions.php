<?php

function vd( $var ) {
	global $_debug;
	if( !$_debug ) return false;

	$debug_backtrace = debug_backtrace();
	
	header( 'Content-type: text/html' );

	echo '<pre style="background-color:#faa; color:#000; padding:20px; clear:both; font:bold 12px/24px Tahoma; position:relative; top:0; text-align:left; width:100%; z-index: 99999; box-sizing: border-box;">';
	echo '<div style="background-color:#fbb; color:#333; padding:5px; clear:both; font:normal 10px Tahoma; margin:0px -10px 10px;">File:'.$debug_backtrace[0]['file'].' | Line:'.$debug_backtrace[0]['line'].'</div>';
	var_dump( $var );
	echo '</pre>';

	return true;
}

function dd( $var ) {
	vd($var);
	die();
}

function cleanInput( $input, $type = "string" ) {
	if( $type == "int" ) return (int)$input;

	if( $type == "string" ){
		$input = trim( $input );
		$input = htmlentities( $input );
	}

	return $input;
}

function showResult( $rs ) {
	$table = '<table style="border: 1px solid #CCC">';
	$table .= '<tr>';
	$headers = array_keys( $rs[0] );
	for( $i = 0; $i < sizeof( $headers ); $i++ ){
		$table .= '<th>'.$headers[$i].'</th>';
	}
	$table .= '</tr>';
	foreach( $rs as $row ){
		$table .= '<tr>';
		foreach( $row as $cell ){
			$table .= '<td>'.$cell.'</td>';
		}
		$table .= '</tr>';
	}
	$table .= "</table>";
	return $table;
}

function pageNotFound() {
	if (ob_get_contents()) ob_end_clean();
	header( 'Content-type: text/html' );
	header( $_SERVER["SERVER_PROTOCOL"]." 404 Not Found" );
	include( 'views/404.htm' );
	die();
}

function pageForbidden() {
	if (ob_get_contents()) ob_end_clean();
	header( 'Content-type: text/html' );
	header( $_SERVER["SERVER_PROTOCOL"]." 403 Forbidden" );
	include( 'views/403.htm' );
	die();
}

function generateRandomHash($length = 32){

	$charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	$hash = '';

	for ($i=0; $i < $length; $i++) { 
		$hash .= $charset[mt_rand(0, strlen($charset) - 1)];
	}

	return $hash;

}

?>