<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A caching class that concatenates, minifies, and gzips passed in JavaScript files
* Dependencies: VM_External_JsMin and gzipping must be enabled
* Requirements: PHP 5.2 or higher
*/
class Vm_Cache_JavaScript extends Vm_Cache {
	
	/**
	 * @param string $fileName - The complete file path and file name of the file
	 * @param string $data - The data to be included in the file
	 * @param string $expiresHeader - optional - The date for the expires header, in the following format: 
	 *	'Thu, 12 Feb 2009 05:00:00 GMT'	
	 * @param boolean $gzip - optional - Whether or not to gzip the file, defaults TRUE	
	 */
	protected function cacheOps($fileName, $data, $expiresHeader = NULL, $gzip = TRUE){
		$header = ($gzip) ? '<?php ob_start("ob_gzhandler"); ?>' : '<?php ob_start(); ?>';
		if ($expiresHeader){
			$header .= "\n".'<?php header("Expires: '.$expiresHeader.'");'."\n".
			'header("Content-type: text/javascript"); ?>';
		}
		$data = $header.Vm_External_JsMin::minify($data);
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
	 * @param boolean $prependJs - Whether or not to prepend the string 'js-' to the file name, defaults TRUE
	 */
	public function getFileName($fileName, $prependJs = TRUE){
		return ($prependJs) ? $this->cacheDir.'js-'.$fileName.'.php' : $this->cacheDir.$fileName.'.php';
	}

	/**
	 * Description: Creates a cached file if one does not already exist by the same name
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @param string $key (optional) - The key from which the data should be fetched. Defaults to fetching from the general view
	 * @param string $expiresHeader - optional - The date for the expires header, in the following format: 
	 *	'Thu, 12 Feb 2009 05:00:00 GMT'
	 * @param boolean $prependJs - optional - Whether or not to prepend the string 'js-' to the file name, defaults TRUE
	 * @param boolean $gzip - optional - Whether or not to gzip the file, defaults TRUE			
	 */
	public function cache($fileName, $key = NULL, $expiresHeader = NULL, $prependJs = TRUE, $gzip = TRUE) {
		$data = ($key) ? $this->getValue($key) : $this->view;
		$fileName = $this->getFileName($fileName, $prependJs);
		if (!file_exists($fileName)){
			$this->cacheOps($fileName, $data, $expiresHeader, $gzip);
		} 
	}
	
	/**
	 * Description: Creates a cached file, overriding any previously cached files by the same name
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @param string $key (optional) - The key from which the data should be fetched. Defaults to fetching from the general view
	 * @param string $expiresHeader - optional - The date for the expires header, in the following format: 
	 *	'Thu, 12 Feb 2009 05:00:00 GMT'	
	 * @param boolean $prependJs - optional - Whether or not to prepend the string 'js-' to the file name, defaults TRUE
	 * @param boolean $gzip - optional - Whether or not to gzip the file, defaults TRUE	
	 */
	public function refreshCache($fileName, $key = NULL, $expiresHeader = NULL, $prependJs = TRUE, $gzip = TRUE) {
		$data = ($key) ? $this->getValue($key) : $this->view;
		$fileName = $this->getFileName($fileName, $prependJs);
		$this->cacheOps($fileName, $data, $expiresHeader, $gzip);
	}

	/**
	 * Description: Creates an HTML script tag linking to the cached file
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @return string - An HTML script tag linking to the cached file
	 */	
	public function getJsLink($fileName){
		$file = $this->getFileName($fileName);
		return '<script type="text/javascript" src="'.$file.'"></script>';	
	}	
}
?>