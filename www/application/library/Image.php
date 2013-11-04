<?php

class Image
{
	const DEFAULT_JPG_QUALITY = 80;
	const MAX_SIZE = 1048576; // 1MB

	static function getExtension( $str ) {
		return end(explode('.',$str));
	}



	/**
	 *
	 * Upload image
	 *
	 * @param $name
	 * @param $dir
	 *
	 * @return array
	 */
	static function upload( $name, $dirname, $key = null ) {

		$arr = array();
		$arr['status'] = 1;
		$arr['msg'] = 'Sucess';

		//reads the name of the file the user submitted for uploading
		$image = $key !== null ? $_FILES[$name]['name'][$key] : $_FILES[$name]['name'];
		
		if( !$image ){
			$arr['status'] = 0;
			$arr['msg'] = 'False';
			return $arr;
		}

        if (!is_dir($dirname)) {
            if (!mkdir($dirname)) {
				$arr['status'] = 0;
				$arr['msg'] = 'Error making directory';
				return $arr;
			}
        }

		//get the original name of the file from the clients machine

		$filename = $key !== null ? stripslashes($_FILES[$name]['name'][$key]) : stripslashes($_FILES[$name]['name']);

		//get the extension of the file in a lower case format
		$extension = self::getExtension( $filename );
		$extension = strtolower( $extension );

		if( ( $extension != "jpg" ) && ( $extension != "jpeg" ) && ( $extension != "png" ) && ( $extension != "gif" ) ){
			$arr['status'] = 0;
			$arr['msg'] = 'Wrong format!';
			return $arr;
		}


		//get the size of the image in bytes
		//$_FILES['image']['tmp_name'] is the temporary filename of the file
		//in which the uploaded file was stored on the server
		$size = $key !== null ? filesize($_FILES[$name]['tmp_name'][$key]) : filesize($_FILES[$name]['tmp_name']);

		//compare the size with the maxim size we defined and print error if bigger
		if( $size > self::MAX_SIZE ){
			$arr['status'] = 0;
			$arr['msg'] = 'You have exceeded the size limit!';
			return $arr;
		}

		//we will give an unique name, for example the time in unix time format
		$image_name = date( "YmdHis", time() ).'-'.$filename;

		//the new name will be containing the full path where will be stored (images folder)
		$newname = $dirname."/".$image_name;
		
        $i = 1;
        while (file_exists($newname)) {
            $fileParts = pathinfo($newname);
            $targetFileName = $fileParts['filename'] . "_$i." . $fileParts['extension'];
            $newname = $fileParts['dirname'] . "/" . $targetFileName;
            $i++;
        }

		$arr['img_uri'] = $newname;
		$arr['img_name'] = $image_name;

		$copied = $key !== null ? copy($_FILES[$name]['tmp_name'][$key], $newname) : copy($_FILES[$name]['tmp_name'], $newname);

		if( !$copied ){
			$arr['status'] = 0;
			$arr['msg'] = 'Copy unsuccessfull!';
			return $arr;
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
	        $fit_image_width = (int) ($max_height * $source_aspect_ratio);
	        $fit_image_height = $max_height;
	    } else {
	        $fit_image_width = $max_width;
	        $fit_image_height = (int) ($max_width / $source_aspect_ratio);
	    }

	    $fit_gd_image = imagecreatetruecolor($fit_image_width, $fit_image_height);

	    imagecopyresampled($fit_gd_image, $source_gd_image, 0, 0, 0, 0, $fit_image_width, $fit_image_height, $source_image_width, $source_image_height);

		$output_image_path = empty($output_image_path) ? $source_image_path : $output_image_path;

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
	static function fill( $source_image_path, $output_width, $output_height, $output_image_path = null ){

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

		if ( $original_aspect >= $output_aspect )
		{
		   // If image is wider than thumbnail (in aspect ratio sense)
		   $new_height = $output_height;
		   $new_width = $width / ($height / $output_height);
		}
		else
		{
		   // If the thumbnail is wider than the image
		   $new_width = $output_width;
		   $new_height = $height / ($width / $output_width);
		}

		$canvas_image = imagecreatetruecolor( $output_width, $output_height );

		// Resize and crop
		imagecopyresampled($canvas_image,
		                   $image,
		                   0 - ($new_width - $output_width) / 2, // Center the image horizontally
		                   0 - ($new_height - $output_height) / 2, // Center the image vertically
		                   0, 0,
		                   $new_width, $new_height,
		                   $width, $height);
		$output_image_path = empty($output_image_path) ? $source_image_path : $output_image_path;

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
   static function grayScale ($path, $output_path = null){

        if ( empty($output_path) ) {
            $output_path = $path;
        }

        $tmp = explode('.', $path);
        $pom = end($tmp);

        if ($pom == 'gif') {
            $image = imagecreatefromgif($path);
        }
        else if ($pom == 'png') {
            $image = imagecreatefrompng($path);
        }
        else if ($pom == 'bmp') {
            $image = imagecreatefromwbmp($path);
        }
        else if ($pom == 'jpg'  ||  $pom == 'jpeg') {
            $image = imagecreatefromjpeg($path);
        }
        else{   
            trigger_error("Format not supported", E_USER_ERROR);
            return false;           
        }
        
        $filter = imagefilter($image, IMG_FILTER_GRAYSCALE);

        if ($filter) {
            if ($pom == 'gif') {
                $save_image = imagegif($image, $output_path);
            }
            else if ($pom == 'png') {
                $save_image = imagepng($image, $output_path);
            }
            else if ($pom == 'bmp') {
                $save_image = imagewbmp($image, $output_path);
            }
            else if ($pom == 'jpg'  ||  $pom == 'jpeg') {
                $save_image = imagejpeg($image, $output_path, self::DEFAULT_JPG_QUALITY);
            }
            else{   
                trigger_error("Error saving extension", E_USER_ERROR);
                return false;           
            }
            
            imagedestroy($image);

            return $save_image;

        }else{

            return false;
        }
    }

}

?>
