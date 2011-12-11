<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validator class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm
 */
namespace Tests\Vm;

class ValidatorTest extends \Tests\Test {
	
	function setUp(){
		$this->fixture = new Vm\Validator();
	}
	
	protected function testValidatesTrue(){
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testSetError(){
		$this->fixture->setError('This is an error');
		return $this->assertEqual($this->fixture->getError(), 'This is an error');
	}
	
	protected function testValidatesFalse(){
		$this->fixture->setError('This is an error');
		return $this->assertFalse($this->fixture->validates());
	}
}