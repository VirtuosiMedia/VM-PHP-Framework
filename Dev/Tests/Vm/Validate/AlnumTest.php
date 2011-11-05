<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @group: VM PHP Framework
* @subgroup: Validators
* @description: Tests the Vm_Validate_Alnum class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_AlnumTest extends Tests_Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Vm_Validate_Alnum('JohnDoe45');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testValidatesTrue1(){
		$this->fixture = new Vm_Validate_Alnum('JohnDoe45');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testErrorIsNull(){
		$this->fixture = new Vm_Validate_Alnum('JohnDoe45');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Alnum('JohnDoe14!');
		return $this->assertEqual($this->fixture->getError(), 'This field may only contain letters or numbers');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Vm_Validate_Alnum('JohnDoe14!');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Alnum('JohnDoe14!4', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new Vm_Validate_Alnum('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Vm_Validate_Alnum(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}
}
?>