<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Cache_Exception class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Cache_ExceptionTest extends Tests_Test {
	protected function testVmCacheException(){
		try {
			throw new Vm_Cache_Exception('This is a test exception');
		} catch (Exception $e) {}
		$exception = ($e) ? $e->getMessage() : NULL;	
		return $this->assertEqual($exception, 'This is a test exception');
	}
	
}
?>