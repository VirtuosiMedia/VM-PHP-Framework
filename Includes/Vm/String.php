<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A basic string manipulation class
* Requirements: PHP 5.2 or higher
*/
class Vm_String {
	
	protected $string;
	
	/**
	 * @param string $string - optional - The string to manipulate
	 */
	function __construct($string = NULL){
		$this->string = ($string) ? $string : NULL;
	}
	
	/**
	 * Truncates the string and adds an ellipsis (...) if the string is longer than length 
	 * @param int $length - The maximum number of characters the string can contain before an ellipsis is added 
	 * @param string $string - optional - The string to manipulate, defaults to the string given in constructor
	 * @return string - The string with an ellipsis if it is longer than length, else the unaltered string
	 */
	public function ellipsis($length, $string = NULL){
		$string = ($string) ? $string : $this->string;
		return (strlen($string) > $length) ? substr($string, 0, $length)."&#8230;" : $string;
	}
}
?>