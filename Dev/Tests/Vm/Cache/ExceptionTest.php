<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm_Cache_Exception class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Cache
 */
namespace Tests\Vm\Cache;

class ExceptionTest extends \Tests\Test {
	
	protected function testVmCacheException(){
		try {
			throw new \Vm\Cache\Exception('This is a test exception');
		} catch (Exception $e) {}
		$exception = ($e) ? $e->getMessage() : NULL;	
		return $this->assertEqual($exception, 'This is a test exception');
	}	
}