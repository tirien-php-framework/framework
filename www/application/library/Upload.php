<?php

class Upload {
    private static $reporting=false;
    public static function setReporting($reporting){
        self::$reporting = $reporting;
    }
    public static function isReporting(){
        return self::$reporting;
    }
    public static function file($fileName, $uploadFolder = false) {
        if (isset($_FILES[$fileName])) {
            global $config;
            if (false === $uploadFolder) {
                $uploadFolder = 'public/uploads/';
            }
            $targetFolder = Path::appRoot('/' . trim($uploadFolder, '/') . '/');
            if (!is_dir($targetFolder)) {
                if (!mkdir($targetFolder)) {
                    return false;
                }
            }

            if ($_FILES[$fileName]["error"] > 0) {
                return false;
            } else {
                $targetFile = $targetFolder . '/' . $_FILES[$fileName]["name"];
                $fileParts = pathinfo($targetFile);
                $i = 1;
                while (file_exists($targetFile)) {
                    $targetFile = $fileParts['dirname'] . '/' . $fileParts['filename'] . "_$i." . $fileParts['extension'];
                    $i++;
                }
                move_uploaded_file($_FILES[$fileName]["tmp_name"], $targetFile);
                return substr($targetFile, strlen(Path::appRoot())+1);
            }
        } else {
            return false;
        }
    }

    public static function fileInd($fileName, $uploadFolder = false, $ind) {
        if (isset($_FILES[$fileName])) {
            global $config;
            if (false === $uploadFolder) {
                $uploadFolder = 'public/uploads/';
            }
            $targetFolder = Path::appRoot('/' . trim($uploadFolder, '/') . '/');
            if (!is_dir($targetFolder)) {
                if (!mkdir($targetFolder)) {
                    if (self::isReporting()){
                        echo "<p>Upload file - unable to create folder $targetFolder";
                    }
                    return false;
                }
            }
            if ($_FILES[$fileName]["error"][$ind] > 0) {
                if (self::isReporting()){
                    echo "<p>Upload file - file upload error";
                }
                return false;
            } else {
                $targetFile = $targetFolder . '/' . $_FILES[$fileName]["name"][$ind];
                $fileParts = pathinfo($targetFile);
                $i = 1;
                while (file_exists($targetFile)) {
                    $targetFile = $fileParts['dirname'] . '/' . $fileParts['filename'] . "_$i." . $fileParts['extension'];
                    $i++;
                }
                move_uploaded_file($_FILES[$fileName]["tmp_name"][$ind], $targetFile);
                return substr($targetFile, strlen(Path::appRoot())+1);
            }
        } else {
            if (self::isReporting()){
                echo "<p>Upload file - file not set";
            }
            return false;
        }
    }

    public static function cleanFilename($filename) {
        $reserved = preg_quote('\/:*?"<>|', '/');
        return preg_replace("/([\\x00-\\x20\\x7f-\\xff{$reserved}])/e", "_", $filename);
    }

}

?>