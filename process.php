<?php
//error_reporting(E_ERROR | E_PARSE);
class process{

	protected $message;
	protected $newFileName;
	protected $css_array = array(); // master array to hold all values

	public function upload(){
			
		// If no errors, run some security checks, move file and set message
		if($_FILES['css_file']['error'] == 0){
		
			//Check MIME type. Not using $_FILES['xxx']['type'] because it can't be trusted.
			$mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $_FILES['css_file']['tmp_name']);

			//Storing all the user uploads outside the web root
			$destination = '/var/www/pauldessert.com/parse-css-upload/';
			$this->newFileName = md5(uniqid(rand(), true)) . $_FILES['css_file']['name'];
			
			//Some severs are having an issue detecting the MIME type of CSS files. This is a quick and dirt hack
			if($mimeType == "text/plain" || "text/x-asm"){
				
				//Move the file to it's destination. Also renaming the file and setting for a little extra security
				$result = move_uploaded_file($_FILES['css_file']['tmp_name'], $destination . $this->newFileName);
				
				if($result){
					$message = "The file was uploaded!";
				} else {
					$message = "Sorry, there was a problem uploading the file.";
				}

			} else {
				$message = "Sorry, that file type is not allowed.";
			}

		} else {
			//If there is an error, capture it...
			switch($_FILES['css_file']['error']){
				case 2: 
					$message = "The file was uploaded successfully.";
					break;
				case 4: 
					$message = "The file is too big to upload.";
					break;
			}	
		}

		return $message;
		
	}

	public function parse(){
		
		$file = file_get_contents('/var/www/pauldessert.com/parse-css-upload/' . $this->newFileName);
		$element = explode('}', $file);
		$element = explode('}', $file);
		
		foreach ($element as $element) {
			
			// get the name of the CSS element
			$a_name = explode('{', $element);
			$name = trim($a_name[0]);
			
			// get all the key:value pair styles
			$a_styles = explode(';', $element);
			
			// remove element name from first property element
			$a_styles[0] = str_replace($name . '{', '', $a_styles[0]);
			
			// loop through each style and split apart the key from the value
			$count = count($a_styles);
			for ($a=0; $a<=$count; $a++) {
				if (trim($a_styles[$a]) != '') {
					$a_key_value = explode(':', $a_styles[$a]);
					// build the master css array
					$this->css_array[$name][trim($a_key_value[0])] = trim($a_key_value[1]);
				}
			}               
		}
		
		//json encode and write a new file
		file_put_contents('/var/www/pauldessert.com/parse-css-upload/' . $this->newFileName . ".json", json_encode($this->css_array));
		
		return $this->css_array;
	}
	
	public function findElements($element){
		$allElements = array();

		foreach($this->css_array as $value){
			foreach($value as $key => $value){
				if($key == $element){
					$allElements[] = $value;
				}
			}
		}
		return $allElements;
	}
}


?>

