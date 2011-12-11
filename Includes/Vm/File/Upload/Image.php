<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A basic file upload class for single or multiple commonly used image files types. 
 * @namespace Vm\File\Upload
 */
namespace Vm\File\Upload;

class Image extends \Vm\File\Upload {

	/**
	 * @param string $fieldName - The name of the field name for the file array
	 * @param string $uploadDir - optional - The directory the file should be upload to, without the trailing
	 * 	slash, relative to the current directory. Defaults to the current directory
	 * @param array $options - optional - The options array, including all options from Vm\File\Upload
	 */	
	function __construct($fieldName, $uploadDir = NULL, $options = NULL){
		$defaultOptions = array(
			'maxWidth'=>400,											//The max image width in pixels, defaults to 400
			'maxHeight'=>400,	 										//The max image height in pixels, defaults to 400
			'minWidth'=>1,												//The min image width in pixels, defaults to 1
			'minHeight'=>1,		 										//The min image height in pixels, defaults to 1		
			'imageMaxSizeError'=>'Image Size Requirement Exceeded',		//The error to display if the image is too large
			'imageMinSizeError'=>'Image Size Requirement Not Met'		//The error to display if the image is too large
		);
		
		$allowedFileTypes = array('png', 'jpe', 'jpeg', 'jpg', 'gif', 'ico', 'bmp');
		parent::__construct($fieldName, $allowedFileTypes, $uploadDir);
		$this->setOptions($options, $defaultOptions);
	}

	/**
	 * @description Validates that the size of the image is within the maximum allowed 
	 * @param array $file - The files array
	 * @return boolean - TRUE if the image is the proper dimensions, FALSE if it is not an image or too big
	 */
	protected function validateSize($file){
		if (in_array($file['type'], $this->allowedMimeTypes)){
			$size = getimagesize($file['tmp_name']);
			if (($size[0] <= $this->options['maxWidth']) && ($size[1] <= $this->options['maxHeight'])){
				if (($size[0] >= $this->options['minWidth']) && ($size[1] >= $this->options['minHeight'])){
					return TRUE;		
				} else {
					$this->errors[$file['name']] = $this->options['imageMinSizeError'];
					return FALSE;
				}	
			} else {
				$this->errors[$file['name']] = $this->options['imageMaxSizeError'];
				return FALSE;
			}
		} else {
			$this->errors[$file['name']] = $this->options['fileTypeError'];
			return FALSE;			
		}
	}
	
	/**
	 * @description Uploads the file
	 * @return boolean - TRUE if the upload succeeded, FALSE otherwise
	 */
	public function upload(){
		$this->setAllowedMimeTypes();
		if ($this->options['multiple']){
			$this->resort();
			$i = 1;
			foreach ($this->fileArray as $file){
				if ($this->validateSize($file)){
					$this->validate($file, $i);
					$i++;
				}
			}
		} else {
			if ($this->validateSize($_FILES[$this->fieldName])){
				$this->validate($_FILES[$this->fieldName]);
			}
		}
		return (sizeof($this->errors) > 0) ? FALSE : TRUE;
	}	
}