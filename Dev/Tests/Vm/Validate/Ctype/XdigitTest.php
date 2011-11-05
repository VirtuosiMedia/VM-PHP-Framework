<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Ctype_Xdigit class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Ctype_XdigitTest extends Tests_Test {

	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only hexidecimal digits');
	}	
	
	protected function testZeroToNineValid(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit(0123456789);
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testStringZeroToNineValid(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit('0123456789');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDecimalInvalid(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit('0.123456789');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testNegativeNumberInvalid(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit('-89');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testScientificNotationInvalid(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit('5.72�109');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testENotationInvalid(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit('6.0221415E23');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testHexLettersValid(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit('ABCDEFabcdef');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testNonHexLettersInvalid(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit('GHIJKLMNOPQRSTUVWXYZghijklmnopqrstuvwxyz');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testCharactersInvalid(){
		$this->fixture = new Vm_Validate_Ctype_Xdigit('!@#><,.');
		return $this->assertFalse($this->fixture->validates());
	}	
}
?>