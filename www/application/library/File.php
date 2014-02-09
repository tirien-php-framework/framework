<?php

class File {

    static function getExtension( $str ) {
        return end(explode('.',$str));
    }

    public static function upload( $fileName, $uploadFolder = null, $uploadFileName = null ) {
        if (isset($_FILES[$fileName])) {

            if ( is_array($_FILES[$fileName]["name"]) ) {
                
                $return = array();

                foreach ($_FILES[$fileName]["name"] as $key => $value) {

                    $file = array(
                        "name" => $_FILES[$fileName]["name"][$key],
                        "type" => $_FILES[$fileName]["type"][$key],
                        "tmp_name" => $_FILES[$fileName]["tmp_name"][$key],
                        "error" => $_FILES[$fileName]["error"][$key],
                        "size" => $_FILES[$fileName]["size"][$key]
                        );

                    $return[$key] = self::uploadSingle($file, $uploadFolder, $uploadFileName);

                }

                return $return;

            }
            else{
                return self::uploadSingle($_FILES[$fileName], $uploadFolder, $uploadFileName);
            }

        } else {
            return false;
        }
    }

    public static function uploadSingle( $file, $uploadFolder = null, $uploadFileName = null )
    {

        if ( empty($uploadFolder) ) {
            $uploadFolder = 'public'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
        }

        $targetFolder = Path::appRoot( trim($uploadFolder, '/') );

        if (!is_dir($targetFolder)) {
            if (!mkdir($targetFolder)) {
                return false;
            }
        }

        if ($file["error"] > 0) {
            return false;
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
            $targetFile = $targetFolder . DIRECTORY_SEPARATOR . $uploadFileName;

            $i = 1;
            while (file_exists($targetFile)) {
                $fileParts = pathinfo($targetFile);
                $uploadFileName = rtrim($fileParts['filename'], "_".($i-1)) . "_$i." . $fileParts['extension'];
                $targetFile = $fileParts['dirname'] . DIRECTORY_SEPARATOR . $uploadFileName;
                $i++;
            }

            move_uploaded_file($file["tmp_name"], $targetFile);
            return $uploadFolder. DIRECTORY_SEPARATOR . $uploadFileName;
            
        }

    }

    public static function sanitizeFilename($filename) {
        return preg_replace("[^\w\d\.\-_]", '', $filename);
    }

}

?>