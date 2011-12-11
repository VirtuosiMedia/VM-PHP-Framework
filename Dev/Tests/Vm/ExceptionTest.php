<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm_Exception class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm
 */
namespace Tests\Vm;

class ExceptionTest extends \Tests\Test {
	
	protected function testVmException(){
		try {
			throw new \Vm\Exception('This is a test exception');
		} catch (\Exception $e) {}
		$exception = ($e) ? $e->getMessage() : NULL;	
		return $this->assertEqual($exception, 'This is a test exception');
	}
}