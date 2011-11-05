<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Is_Integer class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Is_IntegerTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Is_Integer('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be an integer');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Is_Integer('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Integer(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Integer('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsValid(){
		$this->fixture = new Vm_Validate_Is_Integer(12);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Integer(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Integer(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Integer(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Integer(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Integer(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Integer(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}
?>