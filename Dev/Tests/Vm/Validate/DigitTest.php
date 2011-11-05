<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Digit class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_DigitTest extends Tests_Test {

	protected function testZeroToNineValid(){
		$this->fixture = new Vm_Validate_Digit(0123456789);
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testStringZeroToNineValid(){
		$this->fixture = new Vm_Validate_Digit('0123456789');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDecimalInvalid(){
		$this->fixture = new Vm_Validate_Digit('0.123456789');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testNegativeNumberInvalid(){
		$this->fixture = new Vm_Validate_Digit('-89');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testScientificNotationInvalid(){
		$this->fixture = new Vm_Validate_Digit('5.72�109');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testENotationInvalid(){
		$this->fixture = new Vm_Validate_Digit('6.0221415E23');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testLettersInvalid(){
		$this->fixture = new Vm_Validate_Digit('Twelve');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testCharactersInvalid(){
		$this->fixture = new Vm_Validate_Digit('!@#><,.');
		return $this->assertFalse($this->fixture->validates());
	}	
}
?>