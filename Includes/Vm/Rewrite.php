<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A generic base class for rewriting URLs, meant to be extended by rewrite rule classes
* Requirements: PHP 5.2 or higher
*/
class Vm_Rewrite {
	
	protected $url;
	
	/**
	 * @param string $url - The URL to be stored
	 */
	function __construct($url){
		$this->url = $url;
	}
	
	/**
	 * Gets the altered URL
	 * @return string - The URL
	 */
	public function getUrl(){
		return $this->url;
	}
}
?>