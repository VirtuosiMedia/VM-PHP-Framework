<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Is_Null class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Is_NullTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Is_Null('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be NULL');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Is_Null('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Null(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Null('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Null(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Null(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsValid(){
		$this->fixture = new Vm_Validate_Is_Null(null);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Null(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Null(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Null(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Null(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}
?>