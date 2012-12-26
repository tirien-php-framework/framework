<?php

Class ml {

    public static $active_language;
    private static $active_language_id;
    private static $default_language = 'en';
    private static $default_language_id = 0;
    public static $languages = array(0 => 'en', 1 => 'fr', 2 => 'de');
    private static $csv_path;
    private static $csv_array = array();
    static function init() {
        self::$csv_path = file_exists(rtrim(Path::appRoot(), '/') . '\\framework') ? rtrim(Path::appRoot(), '/') . '\\framework\\assets\\ml.csv' : Path::appRoot() . 'framework/assets/ml.csv';

        self::loadCsv();

        if (!isset($_SESSION)) {
            session_start();
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
            trigger_error("Language file doesn't exist", E_USER_ERROR);
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