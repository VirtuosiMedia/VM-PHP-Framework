<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Ctype_Space class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Ctype_SpaceTest extends Tests_Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Vm_Validate_Ctype_Space("     \t \r\n\t");
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Vm_Validate_Ctype_Space("     \t ");
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Ctype_Space("JohnDoe14\t\r\n");
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only whitespace characters');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Vm_Validate_Ctype_Space("JohnDoe14\t\r\n");
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Ctype_Space("JohnDoe14\t\r\n", 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testWhiteSpaceDoNotCauseError(){
		$this->fixture = new Vm_Validate_Ctype_Space(' 	   ');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testControlCharactersDoNotCauseError(){
		$this->fixture = new Vm_Validate_Ctype_Space("\r\n\t");
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testPunctuationCausesError(){
		$this->fixture = new Vm_Validate_Ctype_Space("!@#$~%^&*()_+<>?,./");
		return $this->assertFalse($this->fixture->validates());
	}	
}
?>