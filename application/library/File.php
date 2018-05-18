<?php

class File {

    static function getExtension( $str ) {
        return end(explode('.',$str));
    }

    public static function upload( $file_key, $uploadFolder = null, $uploadFileName = null ) {
        if (isset($_FILES[$file_key])) {
            if ( is_array($_FILES[$file_key]["name"]) ) {
                $return = array();

                foreach ($_FILES[$file_key]["name"] as $key => $value) {

                    if ( empty($_FILES[$file_key]["name"][$key]) ) {
                        continue;
                    }

                    $file = array(
                        "name" => $_FILES[$file_key]["name"][$key],
                        "type" => $_FILES[$file_key]["type"][$key],
                        "tmp_name" => $_FILES[$file_key]["tmp_name"][$key],
                        "error" => $_FILES[$file_key]["error"][$key],
                        "size" => $_FILES[$file_key]["size"][$key]
                        );
                    $return[$key] = self::uploadSingle($file, $uploadFolder, $uploadFileName);

                }

                return $return;

            }
            else{

                if ( empty($_FILES[$file_key]["name"]) ) {
                    return array();
                }
              
                $return[0] = self::uploadSingle($_FILES[$file_key], $uploadFolder, $uploadFileName);
                return $return;
            }

        } else {
            return array();
        }
    }

    public static function uploadSingle( $file, $uploadFolder = null, $uploadFileName = null )
    {

        if ( empty($uploadFolder) ) {
            $uploadFolder = 'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
        }

        $uploadFolder = trim($uploadFolder, '\/');

        if (!is_dir($uploadFolder)) {
            if (!mkdir($uploadFolder)) {
                return null;
            }
        }

        if ($file["error"] > 0) {
            return null;
        } 
        else {

            if ( empty($uploadFileName) ) {
                $uploadFileName = $file["name"];
            }
            else if(strpos($uploadFileName, ".")===false){
                $tmpFileParts = pathinfo($file["name"]);
                $uploadFileName = $uploadFileName.".".$tmpFileParts['extension'];
            }

            $uploadFileName = self::sanitizeFilename($uploadFileName);
            $targetFile = $uploadFolder . DIRECTORY_SEPARATOR . $uploadFileName;

            $i = 1;
            while (file_exists($targetFile)) {
                $fileParts = pathinfo($targetFile);
                $uploadFileName = rtrim($fileParts['filename'], "_".($i-1)) . "_$i." . $fileParts['extension'];
                $targetFile = $fileParts['dirname'] . DIRECTORY_SEPARATOR . $uploadFileName;
                $i++;
            }

            move_uploaded_file($file["tmp_name"], $targetFile);
            return $uploadFolder. '/' . $uploadFileName;
            
        }

    }

    public static function sanitizeFilename($filename) {
        return preg_replace("[^\w\d\.\-_]", '', $filename);
    }

}

?>