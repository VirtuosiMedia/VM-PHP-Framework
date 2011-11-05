<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validator class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_ValidatorTest extends Tests_Test {
	
	function setUp(){
		$this->fixture = new Vm_Validator();
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
?>