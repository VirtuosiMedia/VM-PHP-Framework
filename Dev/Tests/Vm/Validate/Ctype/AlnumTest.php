<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Ctype_Alnum class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Ctype_AlnumTest extends Tests_Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Vm_Validate_Ctype_Alnum('JohnDoe45');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Vm_Validate_Ctype_Alnum('JohnDoe45');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Ctype_Alnum('JohnDoe14!');
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only letters or numbers');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Vm_Validate_Ctype_Alnum('JohnDoe14!');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Ctype_Alnum('JohnDoe14!4', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new Vm_Validate_Ctype_Alnum('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Vm_Validate_Ctype_Alnum(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}
}
?>