<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A rewrite ruleset to replace the page parameter with a clean URL
* Requirements: PHP 5.2 or higher
*/
class Vm_Rewrite_Page extends Vm_Rewrite {
	
	/**
	 * Replaces index.php and the 'p' parameter with the value of the 'p' parameter and appends 
	 * 	the remaining query string if it exists 
	 * Example: http://www.example.com/index.php?p=about-us&pn=1 becomes http://www.example.com/about-us?pn=1
	 * @param string $url - The full URL
	 */
	function __construct($url){
		$url = explode('?', $url);
		$base = preg_replace('#index.php#', '', $url[0]);
		$qString = explode('&', $url[1]);
		$numParams = sizeof($qString);
		for ($i = 0; $i < $numParams; $i++){
			if (preg_match('#^p=(.)#', $qString[$i])){
				$qString[$i] = preg_replace('#p=#', '', $qString[$i]);
				$base .= $qString[$i];
				unset($qString[$i]);
				break; 
			}
		}
		$qString = (sizeof($qString) > 0) ? '?'.implode('&', $qString) : NULL;
		parent::__construct($base.$qString);
	}
	
}
?>