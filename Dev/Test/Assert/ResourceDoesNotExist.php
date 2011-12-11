<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit test that tests if a file or directory does not exist, fails if it does exist.
 * @namespace Test\Assert
 */
namespace Test\Assert;

class ResourceDoesNotExist extends \Test\Assert {
	
	/**
	 * @description Tests if a file or directory does not exist, fails if it does exist.
	 * @param string $resourceName - Path to the file or directory.
	 */
	function __construct($resourceName){
		$this->result = (file_exists($resourceName)) ? FALSE : TRUE;
		if (!$this->result){
			$this->error = "'$resourceName' exists, contrary to what was asserted.";
		}		
	}	
}