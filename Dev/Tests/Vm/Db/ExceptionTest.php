<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm_Db_Exception class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Db
 */
namespace Tests\Vm\Db;

class ExceptionTest extends \Tests\Test {

	protected function testVmDbException(){
		try {
			throw new \Vm\Db\Exception('This is a test exception');
		} catch (\Exception $e) {}
		$exception = ($e) ? $e->getMessage() : NULL;	
		return $this->assertEqual($exception, 'This is a test exception');
	}
	
}