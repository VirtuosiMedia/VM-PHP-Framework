<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A unit test that tests if a file or directory does not exist, fails if it does exist.
*/
class Tests_Test_Assert_ResourceDoesNotExist extends Tests_Test_Assert {
	
	/**
	 * Tests if a file or directory does not exist, fails if it does exist.
	 * @param string $resourceName - Path to the file or directory.
	 */
	function __construct($resourceName){
		$this->result = (file_exists($resourceName)) ? FALSE : TRUE;
		if (!$this->result){
			$this->error = "'$resourceName' exists, contrary to what was asserted.";
		}		
	}	
}
?>