<?php

/**
 * APPLICATION ALERT HANDLING CLASS
 * 
 * Use to set arbitrarily types of alerts with: 
 * Alerts::set("error", "Error occured");
 * 
 * Print block of alerts with:
 * Alerts::show();
 */

class Alerts {

    static function show() {

		if ( !empty($_SESSION["alerts"]) ){ 

			ksort($_SESSION["alerts"]);

			$alert_type = '';
			$i = 0;

			foreach ($_SESSION["alerts"] as $key => $alert_group) {

				$i++;

				if($alert_type != $key){

					$alert_type = $key;

					if( $i != 1 ){
						echo '</div>';
					}

					echo '<div class="alert alert-'.$alert_type.'">';

				}

				foreach ($alert_group as $alert) {
					echo $alert . "<br>";
				}

				if( $i == count($_SESSION["alerts"]) ){
					echo '</div>';
				}


			} 

			unset($_SESSION["alerts"]);

		}

    }

    static function set($type, $alert) {
    	$_SESSION["alerts"][$type][] = $alert;
    }

    static function clear() {
    	unset($_SESSION["alerts"]);
    }

}

?>