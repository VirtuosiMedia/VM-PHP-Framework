<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Is_Array class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Is_ArrayTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Is_Array('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be an array');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Is_Array('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsValid(){
		$this->fixture = new Vm_Validate_Is_Array(array());
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Array('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Array(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Array(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Array(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Array(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Array(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testNumericIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Array(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}	
}
?>