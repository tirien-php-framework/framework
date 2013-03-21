<?php
/**
 * Multilanguage Library
 * Initiate with: 
 * ml::init( array('en', 'fr', 'de') ); 
 * where first element is default language
 */
Class ml {

    public static $active_language;
    private static $active_language_id;
    private static $default_language;
    private static $default_language_id = 0;
    public static $languages;
    private static $csv_path;
    private static $csv_array = array();

    static function init( $languages ) {
        self::$languages = $languages;
        self::$default_language = $languages[self::$default_language_id];

        self::$csv_path = 'application'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'ml.csv';
        self::loadCsv();

        if (!isset($_SESSION)) {
            session_start();
        }

        // PARSE REQUEST PARAMS
        if ( !empty($_REQUEST['rq_controller']) && in_array($_REQUEST['rq_controller'], $languages) ) {

            $_SESSION['active_language'] = $_REQUEST['rq_controller'];
            $_REQUEST['rq_controller'] = !empty($_REQUEST['rq_action']) ? $_REQUEST['rq_action'] : 'index';
			
			if( !empty($_REQUEST['rq_param']) ){
				$rq_param = explode("/",$_REQUEST['rq_param']);
				if( is_array($rq_param) ){
					$_REQUEST['rq_action'] = array_shift($rq_param);
					$_REQUEST['rq_param'] = implode('/',$rq_param);
				}
				else{
					$_REQUEST['rq_action'] = $_REQUEST['rq_param'];
				}
			}
			else {
				$_REQUEST['rq_action'] = 'index';
			}

        } 
        else {
            $_SESSION['active_language'] = self::$default_language;
        }        

        self::$active_language = !empty($_SESSION['active_language']) ? $_SESSION['active_language'] : self::$default_language;

        if (!empty($_REQUEST['set_language'])) {
            self::$active_language = $_SESSION['active_language'] = $_REQUEST['set_language'];
        }

        self::$active_language_id = array_search(self::$active_language, self::$languages);
    }

    private static function loadCsv() {
        if (file_exists(self::$csv_path)) {
            $handle = fopen(self::$csv_path, "r");

            while (($row = fgetcsv($handle, 500, ",")) !== FALSE) {
                self::$csv_array[$row[self::$default_language_id]] = $row;
            }
            fclose($handle);
        } else {
            trigger_error("Multilanguage file doesn't exist", E_USER_ERROR);
        }
    }

    public static function t($word) {
        if (!empty(self::$csv_array[$word][self::$active_language_id])) {
            return self::$csv_array[$word][self::$active_language_id];
        } else {
            if (!isset(self::$csv_array[$word])) {
                if (file_exists(self::$csv_path)) {
                    $handle = fopen(self::$csv_path, "a");
                    fputcsv($handle, array($word,'',''));
                    fclose($handle);
                }
            }
        }
    }

    public static function p() {
        return self::$active_language !== self::$default_language ? self::$active_language . '/' : '';
    }

}

?>