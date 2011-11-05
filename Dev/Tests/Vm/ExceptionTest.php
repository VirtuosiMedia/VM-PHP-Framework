<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Exception class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_ExceptionTest extends Tests_Test {
	
	protected function testVmException(){
		try {
			throw new Vm_Exception('This is a test exception');
		} catch (Exception $e) {}
		$exception = ($e) ? $e->getMessage() : NULL;	
		return $this->assertEqual($exception, 'This is a test exception');
	}
}
?>