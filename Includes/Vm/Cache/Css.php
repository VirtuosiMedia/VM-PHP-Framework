<?php
/**
* @author Virtuosi Media Inc.
* @license MIT License
* @description A caching class that concatenates, minifies, and gzips passed in CSS files
* @extends Vm_Cache
* @uses Vm_External_CssMin
* @requires VM_External_CssMin and gzipping must be enabled
* @requires PHP 5.2 or higher
*/
class Vm_Cache_Css extends Vm_Cache {
	
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
			'header("Content-type: text/css"); ?>';
		}
		$data = $header.Vm_External_CssMin::minify($data);
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
	 * @param boolean $prependCss - Whether or not to prepend the string 'css-' to the file name, defaults TRUE
	 */
	public function getFileName($fileName, $prependCss = TRUE){
		return ($prependCss) ? $this->cacheDir.'css-'.$fileName.'.php' : $this->cacheDir.$fileName.'.php';
	}

	/**
	 * Description: Creates a cached file if one does not already exist by the same name
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @param string $key (optional) - The key from which the data should be fetched. Defaults to fetching from the general view
	 * @param string $expiresHeader - optional - The date for the expires header, in the following format: 
	 *	'Thu, 12 Feb 2009 05:00:00 GMT'	
	 * @param boolean $prependCss - optional - Whether or not to prepend the string 'css-' to the file name, defaults TRUE
	 * @param boolean $gzip - optional - Whether or not to gzip the file, defaults TRUE	
	 */
	public function cache($fileName, $key = NULL, $expiresHeader = NULL, $prependCss = TRUE, $gzip = TRUE) {
		$data = ($key) ? $this->getValue($key) : $this->view;
		$fileName = $this->getFileName($fileName, $prependCss);
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
	 * @param boolean $prependCss - optional - Whether or not to prepend the string 'css-' to the file name, defaults TRUE
	 * @param boolean $gzip - optional - Whether or not to gzip the file, defaults TRUE	
	 */
	public function refreshCache($fileName, $key = NULL, $expiresHeader = NULL, $prependCss = TRUE, $gzip = TRUE) {
		$data = ($key) ? $this->getValue($key) : $this->view;
		$fileName = $this->getFileName($fileName, $prependCss);
		$this->cacheOps($fileName, $data, $expiresHeader, $gzip);
	}

	/**
	 * Description: Creates an HTML script tag linking to the cached file
	 * @param string $fileName - The file name for the cached file, minus the file extension, which will be .php
	 * @return string - An HTML script tag linking to the cached file
	 */	
	public function getCssLink($fileName){
		$file = $this->getFileName($fileName);
		return '<link type="text/css" rel="stylesheet" href="'.$file.'" />';	
	}	
}
?>