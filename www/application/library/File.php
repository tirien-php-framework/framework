<?php
// TODO:ako ima vise fajlova mora da se hendla key 
class File {

    static function getExtension( $str ) {
        return end(explode('.',$str));
    }

    public static function upload($fileName, $uploadFolder = null) {
        if (isset($_FILES[$fileName])) {
            global $config;
            if ( empty($uploadFolder) ) {
                $uploadFolder = 'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
            }

            $targetFolder = Path::appRoot( trim($uploadFolder, '/') );

            if (!is_dir($targetFolder)) {
                if (!mkdir($targetFolder)) {
                    return false;
                }
            }

            if ($_FILES[$fileName]["error"] > 0) {
                return false;
            } 
            else {
                $targetFile = $targetFolder . DIRECTORY_SEPARATOR . self::sanitizeFilename($_FILES[$fileName]["name"]);

                $i = 1;
                while (file_exists($targetFile)) {
                    $fileParts = pathinfo($targetFile);
                    $targetFileName = $fileParts['filename'] . "_$i." . $fileParts['extension'];
                    $targetFile = $fileParts['dirname'] . DIRECTORY_SEPARATOR . $targetFileName;
                    $i++;
                }

                move_uploaded_file($_FILES[$fileName]["tmp_name"], $targetFile);
                return strstr( $targetFile, $uploadFolder) ;
            }
        } else {
            return false;
        }
    }

    public static function sanitizeFilename($filename) {
        return preg_replace("[^\w\d\.\-_]", '', $filename);
    }

}

?>