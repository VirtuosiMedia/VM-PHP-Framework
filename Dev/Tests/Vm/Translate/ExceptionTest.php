<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Translate\Exception class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Translate
 */
namespace Tests\Vm\Translate;

class ExceptionTest extends \Tests\Test {

	protected function testVmTranslateException(){
		try {
			throw new \Vm\Translate\Exception('This is a test exception');
		} catch (\Exception $e) {}
		$exception = ($e) ? $e->getMessage() : NULL;	
		return $this->assertEqual($exception, 'This is a test exception');
	}
}