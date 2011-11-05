<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Form_Exception class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Form_ExceptionTest extends Tests_Test {
	protected function testVmFormException(){
		try {
			throw new Vm_Form_Exception('This is a test exception');
		} catch (Exception $e) {}
		$exception = ($e) ? $e->getMessage() : NULL;	
		return $this->assertEqual($exception, 'This is a test exception');
	}
}
?>