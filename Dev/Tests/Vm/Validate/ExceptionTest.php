<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Exception class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\Exception
 */
namespace Tests\Vm\Validate;

use Vm\Validate\Exception;

class ExceptionTest extends \Tests\Test {

	protected function testVmValidateException(){
		try {
			throw new Exception('This is a test exception');
		} catch (Exception $e) {}
		$exception = ($e) ? $e->getMessage() : NULL;	
		return $this->assertEqual($exception, 'This is a test exception');
	}	
}