<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Is_Bool class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Is_BoolTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Is_Bool('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be boolean');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Is_Bool('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Bool(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Bool('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Bool(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Bool(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Bool(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsValid(){
		$this->fixture = new Vm_Validate_Is_Bool(TRUE);
		return $this->assertTrue($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsValid(){
		$this->fixture = new Vm_Validate_Is_Bool(FALSE);
		return $this->assertTrue($this->fixture->validates());		
	}	

	protected function testNumericIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Bool(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}	
}
?>