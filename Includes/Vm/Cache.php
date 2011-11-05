<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: A generic caching class
* @requires: PHP 5.2 or higher
*/
class Vm_Cache extends Vm_View {
	
	protected $cacheDir = NULL;

	/**
	 * @param string $fileName - The complete file path and file name of the file
	 * @param string $data - The data to be included in the file
	 */
	protected function cacheOps($fileName, $data){
		try {
			$file = fopen($fileName, 'w');
			fwrite($file, $data);
			fclose($file);
		} catch (Vm_Cache_Exception $e){
			throw new Vm_Cache_Exception("File could not be cached. Please ensure the cache directory is writable.");
		}
	}

	/**
	 * @param string $fileName - The complete file path and file name of the file
	 */
	protected function getOps($fileName){
		return file_get_contents($fileName);
	}

	/**
	 * @param string $fileName - The complete file path and file name of the file
	 */
	protected function getFileName($fileName){
		return $this->cacheDir.$fileName.'.php';
	}
	
	/**
	 * @param string $cacheDir - The directory path of the cache, relative or absolute
	 */
	public function __construct($cacheDir){
		$this->cacheDir = (preg_match('#./$#', $cacheDir)) ? $cacheDir : $cacheDir.'/';
	}
	
	/**
	 * Description: Creates a cached file if one does not already exist by the same name
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @param string $key (optional) - The key from which the data should be fetched. Defaults to fetching from the general view
	 */
	public function cache($fileName, $key = NULL) {
		$data = ($key) ? $this->getValue($key) : $this->view;
		$fileName = $this->getFileName($fileName);
		if (!file_exists($fileName)){
			$this->cacheOps($fileName, $data);
		} 
	}
	
	/**
	 * Description: Creates a cached file, overriding any previously cached files by the same name
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @param string $key (optional) - The key from which the data should be fetched. Defaults to fetching from the general view
	 */
	public function refreshCache($fileName, $key = NULL) {
		$data = ($key) ? $this->getValue($key) : $this->view;
		$fileName = $this->getFileName($fileName);
		$this->cacheOps($fileName, $data);
	}	

	/**
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @param int $expiry - In seconds, the allowable age of the cache
	 * @return boolean - TRUE if the cache file exists and is current, FALSE otherwise
	 */	
	public function isCurrent($fileName, $expiry) {
		$file = $this->getFileName($fileName);
		return ((file_exists($file)) && (filemtime($file) > (time() - $expiry))) ? TRUE : FALSE;
	}
	
	/**
	 * Description: Creates a cached file, overriding any previously cached files by the same name
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @param int $expiry - In seconds, the allowable age of the cache
	 * @return string - The contents of the cached file if it exists and is current, FALSE otherwise
	 */	
	public function get($fileName, $expiry) {
		$file = $this->getFileName($fileName);
		return ($this->isCurrent($fileName, $expiry)) ? $this->getOps($file) : FALSE;
	}
	
	/**
	 * Description: Creates a cached file, overriding any previously cached files by the same name and uses include to load the file
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @param int $expiry - In seconds, the allowable age of the cache
	 */	
	public function load($fileName, $expiry) {
		$file = $this->getFileName($fileName);
		if ($this->isCurrent($fileName, $expiry)) {
			include($file);
		} 
	}		
}
?>