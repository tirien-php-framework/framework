<?php
	function vd($var,$die=false) {
		global $_debug;
		if(!$_debug) return false;
		
		$debug_backtrace = debug_backtrace();
		
		echo '<pre style="background-color:#faa; color:#000; padding:20px; clear:both; font:bold 12px/24px Tahoma; position:relative; top:0px; text-align:left; width:100%; z-index: 99999;">';
		echo '<div style="background-color:#fbb; color:#333; padding:5px; clear:both; font:normal 10px Tahoma; margin:0px -10px 10px;">File:'.$debug_backtrace[0]['file'].' | Line:'.$debug_backtrace[0]['line'].'</div>';
		print_r($var);
		echo '</pre>';
		
		if($die){
			die();
		}
		else{
			return true;
		}
	}
	
	function cleanInput($input, $type="string") {
		if($type=="int") return (int)$input;
		
		if($type=="string") {
			$input = trim($input);
			$input = htmlentities($input);
		}
		
		return $input;
	}
	
	function showResult($rs){
		$table='<table style="border: 1px solid #CCC">';
		$table.='<tr>';
		$headers = array_keys($rs[0]);
		for ($i=0;$i<sizeof($headers);$i++){
			$table.='<th>' . $headers[$i] . '</th>';
		}
		$table.='</tr>';		
		foreach ($rs as $row) {
			$table.='<tr>';
			foreach ($row as $cell) {
				$table.='<td>'.$cell. '</td>';
			}
			$table.='</tr>';
		}
		$table .= "</table>";
		return $table;
	}
	
	function disableMagicQuotes()
	{
		if (get_magic_quotes_gpc()) {
		    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		    while (list($key, $val) = each($process)) {
		        foreach ($val as $k => $v) {
		            unset($process[$key][$k]);
		            if (is_array($v)) {
		                $process[$key][stripslashes($k)] = $v;
		                $process[] = &$process[$key][stripslashes($k)];
		            } else {
		                $process[$key][stripslashes($k)] = stripslashes($v);
		            }
		        }
		    }
		    unset($process);
		}
	}
	
	function pageNotFound(){
		sleep(3);
		header("Status: 404 Not Found");
		include('application/views/404.htm');
		die();
	}
?>