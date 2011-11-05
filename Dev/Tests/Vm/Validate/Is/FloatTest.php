<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Is_Float class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Is_FloatTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Is_Float('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be a float');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Is_Float('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Float(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Float('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Float(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Float(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Float(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Float(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Float(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsValid(){
		$this->fixture = new Vm_Validate_Is_Float(3.14159265);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsValid(){
		$this->fixture = new Vm_Validate_Is_Float(1e7);
		return $this->assertTrue($this->fixture->validates());		
	}	
}
?>