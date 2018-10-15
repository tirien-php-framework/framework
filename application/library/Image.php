<?php

if (is_file(__DIR__ . '/../../vendor/autoload.php') && is_readable(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} else {
    // Fallback to legacy autoloader
    require_once __DIR__ . '/../../autoload.php';
}

\Cloudinary::config(array(
    "cloud_name" => "",
    "api_key" => "",
    "api_secret" => ""
));

class Image
{
    const DEFAULT_JPG_QUALITY = 90;
    const MAX_SIZE = 10485760; // 10MiB

    static function getExtension($str)
    {
        $tmp = explode('.', $str);
        return end($tmp);
    }

    /**
     * Upload image
     *
     * @param $name
     * @param $dir
     *
     * @return array
     */
    static function upload($name, $dirname, $key = null)
    {
        $arr = array();
        $arr['status'] = 1;
        $arr['message'] = 'Success';
        $arr['cloudinary'] = false;

        $tmpImagePath = $key !== null ? $_FILES[$name]['tmp_name'][$key] : $_FILES[$name]['tmp_name'];

        if (!$tmpImagePath) {
            $arr['status'] = 0;
            $arr['message'] = 'Image error';
            return $arr;
        }

        $originalImageName = $key !== null ? stripslashes($_FILES[$name]['name'][$key]) : stripslashes($_FILES[$name]['name']);

        //get the extension of the file in a lower case format
        $extension = self::getExtension($originalImageName);
        $extension = strtolower($extension);

        if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif") && ($extension != "svg")) {
            $arr['status'] = 0;
            $arr['message'] = 'Wrong image format';
            return $arr;
        }

        $size = filesize($tmpImagePath);

        //compare the size with the maxim size we defined and print error if bigger
        if ($size > self::MAX_SIZE) {
            $arr['status'] = 0;
            $arr['message'] = 'You have exceeded image size limit';
            return $arr;
        }

        if (!is_dir($dirname)) {
            if (!mkdir($dirname)) {
                $arr['status'] = 0;
                $arr['message'] = 'Error making image directory';
                return $arr;
            }
        }

        $newlImageName = strtolower(str_replace(" ", "-", $originalImageName));
        $newlImagePath = $dirname . "/" . $newlImageName;

        $i = 1;

        while (file_exists($newlImagePath)) {
            $pathInfo = pathinfo($newlImagePath);
            $newlImageName = rtrim($pathInfo['filename'], "_" . ($i - 1)) . "_$i." . $pathInfo['extension'];
            $newlImagePath = $pathInfo['dirname'] . "/" . $newlImageName;
            $i++;
        }

        $arr['path'] = $newlImagePath;
        $arr['uri'] = preg_replace('/^public\//i', '', $newlImagePath);
        $arr['filename'] = $newlImageName;

        // upload to Cloudinary
        $upload = \Cloudinary\Uploader::upload($tmpImagePath, array("progressive" => true, "quality" => "auto", "fetch_format" => "auto", "public_id" => $_SERVER['HTTP_HOST'] . "/" . $arr['uri']));

        // check if Cloudinary upload is successful
        if (is_array($upload)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $upload['url']);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $returnData = curl_exec($ch);

            $copied = file_put_contents($newlImagePath, $returnData);
            $arr['cloudinary'] = true;
        } else {
            $copied = copy($tmpImagePath, $newlImagePath);
            self::progresive($newlImagePath);
        }

        if (!$copied) {
            $arr['status'] = 0;
            $arr['message'] = 'Image copy error';
        }

        return $arr;
    }

    /*
     * PHP function to resize an image maintaining aspect ratio
     */
    static function fit($source_image_path, $max_width, $max_height, $output_image_path = null)
    {
        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);

        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
            case IMAGETYPE_BMP:
                $source_gd_image = imagecreatefromwbmp($source_image_path);
                break;
        }

        if ($source_gd_image === false) {
            return false;
        }

        $source_aspect_ratio = $source_image_width / $source_image_height;
        $fit_aspect_ratio = $max_width / $max_height;

        if ($source_image_width <= $max_width && $source_image_height <= $max_height) {
            $fit_image_width = $source_image_width;
            $fit_image_height = $source_image_height;
        } elseif ($fit_aspect_ratio > $source_aspect_ratio) {
            $fit_image_width = (int)($max_height * $source_aspect_ratio);
            $fit_image_height = $max_height;
        } else {
            $fit_image_width = $max_width;
            $fit_image_height = (int)($max_width / $source_aspect_ratio);
        }

        $fit_gd_image = imagecreatetruecolor($fit_image_width, $fit_image_height);

        if ($source_image_type == IMAGETYPE_PNG) {
            imagealphablending($fit_gd_image, false);
            imagesavealpha($fit_gd_image, true);
        }

        imagecopyresampled($fit_gd_image, $source_gd_image, 0, 0, 0, 0, $fit_image_width, $fit_image_height, $source_image_width, $source_image_height);

        $output_image_path = empty($output_image_path) ? $source_image_path : $output_image_path;

        imageinterlace($fit_gd_image, true); //PROGRESIVE

        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $save_image = imagegif($fit_gd_image, $output_image_path);
                break;
            case IMAGETYPE_JPEG:
                $save_image = imagejpeg($fit_gd_image, $output_image_path, self::DEFAULT_JPG_QUALITY);
                break;
            case IMAGETYPE_PNG:
                $save_image = imagepng($fit_gd_image, $output_image_path);
                break;
            case IMAGETYPE_BMP :
                $save_image = image2wbmp($fit_gd_image, $output_image_path);
                break;
        }

        imagedestroy($source_gd_image);
        imagedestroy($fit_gd_image);

        return $save_image;
    }

    /*
     * PHP function to crop and resize an image of fixed dimensions
     */
    static function fill($source_image_path, $output_width, $output_height, $output_image_path = null)
    {

        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);

        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($source_image_path);
                break;
            case IMAGETYPE_BMP:
                $image = imagecreatefromwbmp($source_image_path);
                break;
        }

        if ($image === false) {
            return false;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $original_aspect = $width / $height;
        $output_aspect = $output_width / $output_height;

        if ($original_aspect >= $output_aspect) {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $output_height;
            $new_width = $width / ($height / $output_height);
        } else {
            // If the thumbnail is wider than the image
            $new_width = $output_width;
            $new_height = $height / ($width / $output_width);
        }

        $canvas_image = imagecreatetruecolor($output_width, $output_height);

        if ($source_image_type == IMAGETYPE_PNG) {
            imagealphablending($canvas_image, false);
            imagesavealpha($canvas_image, true);
        }

        // Resize and crop
        imagecopyresampled($canvas_image,
            $image,
            0 - ($new_width - $output_width) / 2, // Center the image horizontally
            0 - ($new_height - $output_height) / 2, // Center the image vertically
            0, 0,
            $new_width, $new_height,
            $width, $height);
        $output_image_path = empty($output_image_path) ? $source_image_path : $output_image_path;

        imageinterlace($canvas_image, true); //PROGRESIVE

        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $save_image = imagegif($canvas_image, $output_image_path);
                break;
            case IMAGETYPE_JPEG:
                $save_image = imagejpeg($canvas_image, $output_image_path, self::DEFAULT_JPG_QUALITY);
                break;
            case IMAGETYPE_PNG:
                $save_image = imagepng($canvas_image, $output_image_path);
                break;
            case IMAGETYPE_BMP :
                $save_image = image2wbmp($canvas_image, $output_image_path);
                break;
        }

        imagedestroy($canvas_image);
        imagedestroy($image);

        return $save_image;
    }

    /*
     * PHP function to convert image to grayscale
     */
    static function grayScale($path, $output_path = null)
    {

        if (empty($output_path)) {
            $output_path = $path;
        }

        $tmp = explode('.', $path);
        $pom = strtolower(end($tmp));

        if ($pom == 'gif') {
            $image = imagecreatefromgif($path);
        } else if ($pom == 'png') {
            $image = imagecreatefrompng($path);
        } else if ($pom == 'bmp') {
            $image = imagecreatefromwbmp($path);
        } else if ($pom == 'jpg' || $pom == 'jpeg') {
            $image = imagecreatefromjpeg($path);
        } else {
            trigger_error("Format not supported", E_USER_ERROR);
            return false;
        }

        $filter = imagefilter($image, IMG_FILTER_GRAYSCALE);

        imageinterlace($image, true); //PROGRESIVE        

        if ($filter) {
            if ($pom == 'gif') {
                $save_image = imagegif($image, $output_path);
            } else if ($pom == 'png') {
                $save_image = imagepng($image, $output_path);
            } else if ($pom == 'bmp') {
                $save_image = imagewbmp($image, $output_path);
            } else if ($pom == 'jpg' || $pom == 'jpeg') {
                $save_image = imagejpeg($image, $output_path, self::DEFAULT_JPG_QUALITY);
            } else {
                trigger_error("Error saving extension", E_USER_ERROR);
                return false;
            }

            imagedestroy($image);

            return $save_image;

        } else {

            return false;
        }
    }

    static function progresive($path, $output_path = null)
    {
        if (empty($output_path)) {
            $output_path = $path;
        }

        $tmp = explode('.', $path);
        $pom = strtolower(end($tmp));

        if ($pom == 'gif') {
            $image = imagecreatefromgif($path);
        } else if ($pom == 'png') {
            $image = imagecreatefrompng($path);

            imagealphablending($image, false);
            imagesavealpha($image, true);
        } else if ($pom == 'bmp') {
            $image = imagecreatefromwbmp($path);
        } else if ($pom == 'jpg' || $pom == 'jpeg') {
            $image = imagecreatefromjpeg($path);
        } else {
            trigger_error("Format not supported", E_USER_ERROR);
            return false;
        }

        $exif = exif_read_data($path);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
                default:
                    $image = $image;
            }
        }

        imageinterlace($image, true); //PROGRESIVE

        if ($pom == 'gif') {
            $save_image = imagegif($image, $output_path);
        } else if ($pom == 'png') {
            $save_image = imagepng($image, $output_path);
        } else if ($pom == 'bmp') {
            $save_image = imagewbmp($image, $output_path);
        } else if ($pom == 'jpg' || $pom == 'jpeg') {
            $save_image = imagejpeg($image, $output_path, self::DEFAULT_JPG_QUALITY);
        } else {
            trigger_error("Error saving extension", E_USER_ERROR);
            return false;
        }

        imagedestroy($image);

        return $save_image;
    }
}

?>
