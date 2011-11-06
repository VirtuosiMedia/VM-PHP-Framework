<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A unit test that tests if a file or directory exists, fails if it does not exist.
*/
class Test_Assert_ResourceExists extends Test_Assert {
	
	/**
	 * Tests if a file or directory exists, fails if it does not exist.
	 * @param string $resourceName - Path to the file or directory.
	 */
	function __construct($resourceName){
		$this->result = file_exists($resourceName);
		if (!$this->result){
			$this->error = "'$resourceName' does not exist, contrary to what was asserted.";
		}		
	}	
}
?>