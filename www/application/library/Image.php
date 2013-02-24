<?php

class Image
{

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
	static function upload( $name, $dirname ) {

		define( "MAX_SIZE", 1*1024*1024 ); // 1MB

		$arr = array();
		$arr['status'] = 1;
		$arr['msg'] = 'Sucess';

		//reads the name of the file the user submitted for uploading
		$image = $_FILES[$name]['name'];

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
		$filename = stripslashes( $_FILES[$name]['name'] );

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
		$size = filesize( $_FILES[$name]['tmp_name'] );

		//compare the size with the maxim size we defined and print error if bigger
		if( $size > MAX_SIZE ){
			$arr['status'] = 0;
			$arr['msg'] = 'You have exceeded the size limit!';
			return $arr;
		}

		//we will give an unique name, for example the time in unix time format
		$image_name = date( "YmdHis", time() ).'-'.$filename;

		//the new name will be containing the full path where will be stored (images folder)
		$newname = $dirname.DIRECTORY_SEPARATOR.$image_name;

        $i = 1;
        while (file_exists($newname)) {
            $fileParts = pathinfo($newname);
            $targetFileName = $fileParts['filename'] . "_$i." . $fileParts['extension'];
            $newname = $fileParts['dirname'] . DIRECTORY_SEPARATOR . $targetFileName;
            $i++;
        }

		$arr['img_name'] = $newname;

		$copied = copy( $_FILES[$name]['tmp_name'], $newname );


		if( !$copied ){
			$arr['status'] = 0;
			$arr['msg'] = 'Copy unsuccessfull!';
			return $arr;
		}

		return $arr;
	}

	/*
	 * PHP function to resize an image maintaining aspect ratio
	 * http://911-need-code-help.blogspot.com/2008/10/resize-images-using-phpgd-library.html
	 *
	 * Creates a resized (e.g. thumbnail, small, medium, large)
	 * version of an image file and saves it as another file
	 */
	static function createThumb($source_image_path, $thumbnail_image_path, $max_width=150, $max_height=150)
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
	    }

	    if ($source_gd_image === false) {
	        return false;
	    }

	    $source_aspect_ratio = $source_image_width / $source_image_height;
	    $thumbnail_aspect_ratio = $max_width / $max_height;

	    if ($source_image_width <= $max_width && $source_image_height <= $max_height) {
	        $thumbnail_image_width = $source_image_width;
	        $thumbnail_image_height = $source_image_height;
	    } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
	        $thumbnail_image_width = (int) ($max_height * $source_aspect_ratio);
	        $thumbnail_image_height = $max_height;
	    } else {
	        $thumbnail_image_width = $max_width;
	        $thumbnail_image_height = (int) ($max_width / $source_aspect_ratio);
	    }

	    $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);

	    imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
	    imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 90);
	    imagedestroy($source_gd_image);
	    imagedestroy($thumbnail_gd_image);

	    return true;
	}

	/*
	 * PHP function to crop and resize an image of fixed dimensions
	 */
	static function crop( $source_image_path, $end_width = 200, $end_height = 200, $end_image_path=null ){

		$image = imagecreatefromjpeg($source_image_path);

		$width = imagesx($image);
		$height = imagesy($image);

		$original_aspect = $width / $height;
		$end_aspect = $end_width / $end_height;

		if ( $original_aspect >= $end_aspect )
		{
		   // If image is wider than thumbnail (in aspect ratio sense)
		   $new_height = $end_height;
		   $new_width = $width / ($height / $end_height);
		}
		else
		{
		   // If the thumbnail is wider than the image
		   $new_width = $end_width;
		   $new_height = $height / ($width / $end_width);
		}

		$canvas_image = imagecreatetruecolor( $end_width, $end_height );

		// Resize and crop
		imagecopyresampled($canvas_image,
		                   $image,
		                   0 - ($new_width - $end_width) / 2, // Center the image horizontally
		                   0 - ($new_height - $end_height) / 2, // Center the image vertically
		                   0, 0,
		                   $new_width, $new_height,
		                   $width, $height);
		$output_image_path = empty($end_image_path) ? $source_image_path : $end_image_path;

		return imagejpeg($canvas_image, $output_image_path, 80);

	}
}

?>
