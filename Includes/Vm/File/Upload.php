<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A basic file upload class for single or multiple files
 * @namespace Vm\File
 */
namespace Vm\File;

class Upload extends \Vm\Klass {
	
	protected $allowedFileTypes;
	protected $allowedMimeTypes = array();
	protected $errors = array();
	protected $fieldName;
	protected $fileArray;
	protected $fileNames = array();
	protected $isValid = TRUE;	

	//List of MIME types from http://www.php.net/manual/en/function.mime-content-type.php#87856
	protected $mimeTypes = array(
		'txt' => 'text/plain',
		'htm' => 'text/html',
		'html' => 'text/html',
		'php' => 'application/x-php',
		'css' => 'text/css',
		'js' => 'application/javascript',
		'xjs' => 'application/x-javascript',
		'json' => 'application/json',
		'xml' => 'application/xml',
		'swf' => 'application/x-shockwave-flash',
		'flv' => 'video/x-flv',
		
		// images
		'png' => 'image/png',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'gif' => 'image/gif',
		'bmp' => 'image/bmp',
		'ico' => 'image/vnd.microsoft.icon',
		'tiff' => 'image/tiff',
		'tif' => 'image/tiff',
		'svg' => 'image/svg+xml',
		'svgz' => 'image/svg+xml',
		
		// archives
		'zip' => 'application/zip',
		'xzip'=> 'application/x-zip-compressed',
		'rar' => 'application/x-rar-compressed',
		'exe' => 'application/x-msdownload',
		'msi' => 'application/x-msdownload',
		'cab' => 'application/vnd.ms-cab-compressed',
		
		// audio/video
		'mp3' => 'audio/mpeg',
		'qt' => 'video/quicktime',
		'mov' => 'video/quicktime',
		
		// adobe
		'pdf' => 'application/pdf',
		'psd' => 'image/vnd.adobe.photoshop',
		'ai' => 'application/postscript',
		'eps' => 'application/postscript',
		'ps' => 'application/postscript',
		
		// ms office
		'doc' => 'application/msword',
		'rtf' => 'application/rtf',
		'xls' => 'application/vnd.ms-excel',
		'ppt' => 'application/vnd.ms-powerpoint',
		
		// open office
		'odt' => 'application/vnd.oasis.opendocument.text',
		'ods' => 'application/vnd.oasis.opendocument.spreadsheet'		
	);
	protected $uploadDir;

	
	/**
	 * @param string $fieldName - The name of the field name for the file array
	 * @param array $allowedFileTypes - The allowed file extensions, without the dot
	 * @param string $uploadDir - optional - The directory the file should be upload to, without the trailing
	 * 	slash, relative to the current directory. Defaults to the current directory
	 * @param array $options - optional - The options array
	 */
	function __construct($fieldName, array $allowedFileTypes, $uploadDir = NULL, $options = NULL){

		$defaultOptions = array(
			'maxFileSize'=>2000000,		//The max file size in bytes, defaults to 2 Megabytes
			'keepName'=>FALSE, 			//Whether or not the original file name should be kept - Note: any spaces in the file name will be replaced with hyphens
			'randomName'=>TRUE,			//Whether or not a random name should be generated
			'newName'=>NULL,			//The new name of the file, will not execute if keepName or randomName are TRUE
			'keepExtension'=>TRUE,		//Whether or not to keep the file extension
			'newExtension'=>NULL,		//The new file extension, will not execute if keepExtension is TRUE
			'multiple'=>FALSE,			//Whether or not an array of files will be uploaded from the same field, defaults FALSE
			'fileSizeError'=>'File Size Exceeded', 	//The error to show when the file size is too large
			'fileTypeError'=>'Invalid File Type',	//The error to show when the file type is incorrect
			'defaultFileError'=>'File Upload Error'	//The default error to show if any other type of error occurs
		);
		
		parent::__construct();
		$this->setOptions($options, $defaultOptions);

		$this->fieldName = $fieldName;
		$this->allowedFileTypes = $allowedFileTypes;	
		$this->uploadDir = ($uploadDir) ? $uploadDir.'/' : './';
	}

	/**
	 * @description Resorts the file array so that each file is contained in it's own array. Modified from:
	 * 		http://docs.php.net/manual/en/features.file-upload.multiple.php#53240
	 */
	protected function resort(){
		$files = $_FILES[$this->fieldName];
		$numFiles = count($files['name']);
	    $fileKeys = array_keys($files);
	
	    for ($i=0; $i<$numFiles; $i++) {
	        foreach ($fileKeys as $key) {
	            $this->fileArray[$i][$key] = $files[$key][$i];
	        }
	    }		
	}
	
	/**
	 * @description Validates if the file is the proper type, size, and if it has been uploaded
	 * @param array $file - The files array for the current file
	 * @param integer $count - optional - If multiple files are uploaded, this is the count
	 */
	protected function validate($file, $count = NULL){
		$path = pathinfo($file['name']);
		if (is_uploaded_file($file['tmp_name'])){
			if ((in_array($file['type'], $this->allowedMimeTypes)) && (in_array($path['extension'], $this->allowedFileTypes))){
				if (($file['size'] > 0) && ($file['size'] < $this->options['maxFileSize'])){
					if ($file['error'] == 0){
						$transfer = $this->transfer($file, $count);	
					}
				} else {
					$this->errors[$file['name']] = $this->options['fileSizeError'];
				}
			} else {
				$this->errors[$file['name']] = $this->options['fileTypeError'].' '.$file['type'];
			}
		} else {
			$this->errors[$file['name']] = $this->options['defaultFileError'];
		}			
	}

	/**
	 * @description Generates a name according to the set options 
	 * @param array $file - The files array for the current file
	 * @param integer $count - optional - If multiple files are uploaded, this is the count
	 * @return string - The file name, with extension
	 */
	protected function generateName($file, $count = NULL){
		$path = pathinfo($file['name']);
		$ext = ((!$this->options['keepExtension']) && ($this->options['newExtension'])) ? $this->options['newExtension'] : $path['extension']; 
		if ($this->options['keepName']){
			$name = str_replace(' ', '-', trim($file['name']));
		} else if ((!$this->options['randomName']) && ($this->options['newName'])){
			$name = $this->options['newName'].$count.".$ext";
		} else {
			$name = (string) md5(date('c').microtime().$file['name']).".$ext";
		}
		return $name;
	}
	
	/**
	 * @description Transfers the file to the its new directory
	 * @param array $file - The files array for the current file
	 * @param integer $count - optional - If multiple files are uploaded, this is the count
	 */
	protected function transfer($file, $count = NULL){
		$name = $this->generateName($file, $count);
		$transfer = move_uploaded_file($file['tmp_name'], $this->uploadDir.$name);
		if (!$transfer){
			$this->errors[$file['name']] = 'File upload failed';
		} else {
			$this->fileNames[] = $this->uploadDir.$name;
		}
	}

	/**
	 * @description Sets the allowed MIME types according to the file extensions given. Note: If there is not an equivalent
	 * 	MIME type for the file in the mimeTypes array, the file will not be uploaded. Use the appendMimeTypes()
	 * 	method to add additional MIME types to the array
	 */
	protected function setAllowedMimeTypes(){
		foreach ($this->allowedFileTypes as $extension){
			$this->allowedMimeTypes[] = $this->mimeTypes[$extension];
		}
	}

	/**
	 * @description Uploads the file
	 * @return mixed - TRUE if the upload succeeded, FALSE otherwise
	 */
	public function upload(){
		$this->setAllowedMimeTypes();
		if ($this->options['multiple']){
			$this->resort();
			$i = 1;
			foreach ($this->fileArray as $file){
				$this->validate($file, $i);
				$i++;
			}
		} else {
			$this->validate($_FILES[$this->fieldName]);
		}
		return (sizeof($this->errors) > 0) ? FALSE : TRUE;
	}
	
	/**
	 * @return array - An array of the available MIME types, with the file extension as the key, the MIME 
	 * 	type as the value
	 */
	public function getMimeTypesList(){
		return $this->mimeTypes;
	}

	/**
	 * @description Appends new MIME types to the mimeTypes array
	 * @param array $newList - An array of the available MIME types, with the file extension as the key, 
	 * 	the MIME type as the value
	 */
	public function appendMimeTypesList(array $newList){
		foreach ($newList as $extension=>$type){
			$this->mimeTypes[$extension] = $type;
		}
	}
	
	/**
	 * @return array - The file upload errors, if they exist
	 */
	public function getErrors(){
		return $this->errors;
	}
	
	/**
	 * @return array - An array of the uploaded files, with the directory path included
	 */
	public function getUploadedFiles(){
		return $this->fileNames;
	}
}