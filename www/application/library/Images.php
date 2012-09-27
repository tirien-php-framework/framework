<?php

class Images {


/*
 * Upload Image
 * $param - input name
 *
*/

public $dirname = "/home/user/htdocs/public/images/";

function UploadImage($name){

	define("MAX_SIZE", 1000);
	
	$arr = array();
	$arr['status'] = 1;
	$arr['msg'] = 'Sucess';

    //This function reads the extension of the file. It is used to determine if the file  is an image by checking the extension.
     function getExtension($str) {
             $i = strrpos($str,".");
             if (!$i) { return ""; }
             $l = strlen($str) - $i;
             $ext = substr($str,$i+1,$l);
             return $ext;
     }


     //This variable is used as a flag. The value is initialized with 0 (meaning no error  found)
    //and it will be changed to 1 if an errro occures.
    //If the error occures the file will not be uploaded.
     $status = 1;
	//var_dump($name['name']); die();
    //reads the name of the file the user submitted for uploading
    $image = $_FILES[$name]['name'];

    if(!$image){
        $arr['status'] = 0;
        $arr['msg'] = 'False';
        return $arr;
    }

    //get the original name of the file from the clients machine
            $filename = stripslashes($_FILES[$name]['name']);
    //get the extension of the file in a lower case format
            $extension = getExtension($filename);
            $extension = strtolower($extension);

     if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
        $arr['status']  = 0;
        $msg = 'Wrong format!';
		return $arr;
     }
 

     //get the size of the image in bytes
     //$_FILES['image']['tmp_name'] is the temporary filename of the file
     //in which the uploaded file was stored on the server
     $size = filesize($_FILES[$name]['tmp_name']);
	 
     //compare the size with the maxim size we defined and print error if bigger
     if ($size > MAX_SIZE*1024){
        $arr['status'] = 0;
        $arr['msg'] = 'You have exceeded the size limit!';
        return $arr;		 
     }

     //we will give an unique name, for example the time in unix time format
     $image_name=time().'.'.$extension;
 
     //the new name will be containing the full path where will be stored (images folder)
     $newname=$this->dirname.$image_name;
	 
     $arr['img_name'] = $image_name;	 

     $copied = copy($_FILES[$name]['tmp_name'], $newname);

 
     if (!$copied) {
		$arr['status'] = 0;
        $arr['msg'] = 'Copy unsuccessfull!';
        return $arr;
     }
   
     return $arr;
}

}

?>
