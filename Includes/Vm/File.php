<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A basic file manipulation class
 * @namespace Vm
 */
namespace Vm;

class File {
	
	protected $dirPath;
	protected $file;
	protected $filename;
	
	/**
	 * @param string $filename - The filename including the extension, but without the directory path
	 * @param string $dirPath - optional - The directory path, without the trailing slash. Defaults to the
	 * 	current directory
	 */
	function __construct($filename, $dirPath = NULL){
		$this->filename = $filename;
		$this->dirPath = ($dirPath) ? $dirPath.'/' : './';
		$this->file = $this->dirPath.$this->filename;
	}
	
	/**
	 * @return boolean - TRUE if the file exists, FALSE otherwise
	 */
	public function exists(){
		return file_exists($this->file);
	}
	
	/**
	 * @description Creates a new file by the name given in the constructor
	 * @param boolean $overwrite - optional - TRUE if an existing file by the same name should be overwritten,
	 * 		defaults false
	 * @return boolean - TRUE if the file was created, FALSE if it was not or already existed and overwriting
	 * 		was not enabled
	 */
	public function create($overwrite = FALSE){
		if (($this->exists()) && (!$overwrite)){
			return FALSE;
		}
		$handle = fopen($this->file, 'w');
		fclose($handle);
		return $this->exists();
	}

	/**
	 * @description Reads the contents of the file
	 * @param int $bytes - optional - The number of bytes to be read (one character equals one byte), defaults
	 * 		to the contents of the entire file
	 * @return string - The file contents (in bytes, if specified, else the entire file) 
	 */
	public function read($bytes = NULL){
		if ($bytes){
			$handle = fopen($this->file, 'w');
			fread($handle, $bytes);
			fclose($handle);			
		} else {
			$data = file_get_contents($this->file);
		}
		return $data;
	}

	/**
	 * @description Writes to the file starting at the beginning of the file; overwrites any previous contents
	 * @param string $data - The data to be written to the file
	 */
	public function write($data){
		$handle = fopen($this->file, 'w');
		fwrite($handle, $data);
		fclose($handle);		
	}

	/**
	 * @description Appends to the file starting at the end of the file; does not overwrite any previous contents
	 * @param string $data - The data to be appended to the file
	 */	
	public function append($data){
		$handle = fopen($this->file, 'a');
		fwrite($handle, $data);
		fclose($handle);		
	}

	/**
	 * @description Deletes the file
	 * @return boolean - TRUE if the file was deleted or does not exist, FALSE otherwise
	 */
	public function delete(){
		if (file_exists($this->file)){
			unlink($this->file);
		}
		return (file_exists($this->file)) ? FALSE : TRUE;
	}
	
	/**
	 * @description Erases the file contents
	 */
	public function erase(){
		$this->create(TRUE);
	}
	
	/**
	 * @return int - The file permissions for the file as an octal value 
	 */
	public function getPermissions(){
		return substr(sprintf('%o', fileperms($this->file)), -4);
	}

	/**
	 * @description Sets the file's permissions
	 * @param $permissions - The file permissions in octal, ie 0755
	 * @param boolean - TRUE if the permissions were set successfully, FALSE otherwise
	 */
	public function setPermissions($permissions){
		$this->permissions = $this->getPermissions();
		chmod($this->file, $permissions);
		return ($this->getPermissions() == $permissions) ? TRUE : FALSE;
	}
	
	/**
	 * @description Reverts file permissions to their previous setting, ONLY to be used after the setPermissions method
	 * @return boolean - TRUE if the permissions were reverted successfully, FALSE otherwise
	 */
	public function revertPermissions(){
		return $this->setPermissions($this->permissions);
	}
	
	/**
	 * @description Injects the file into the document using require only if it exists
	 * @param boolean $once - optional - Uses require_once() if TRUE, require() if FALSE
	 */
	public function inject($once = TRUE){
		if ($this->exists()){
			if ($once){
				require_once($this->file);
			} else {
				require($this->file);
			}
		}
	}
}