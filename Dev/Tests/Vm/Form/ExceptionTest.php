<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm_Form_Exception class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Form
 */
namespace Tests\Vm\Form;

class ExceptionTest extends \Tests\Test {
	
	protected function testVmFormException(){
		try {
			throw new \Vm\Form\Exception('This is a test exception');
		} catch (Exception $e) {}
		$exception = ($e) ? $e->getMessage() : NULL;	
		return $this->assertEqual($exception, 'This is a test exception');
	}
}