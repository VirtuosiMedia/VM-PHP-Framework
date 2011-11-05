<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Is_Numeric class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Is_NumericTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Is_Numeric('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be numeric');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Is_Numeric('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testEmptyArrayIsValid(){
		$this->fixture = new Vm_Validate_Is_Numeric(array());
		return $this->assertTrue($this->fixture->validates());		
	}
	
	protected function testNumericArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Numeric(array(1, 2, 3, 4));
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNonNumericArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Numeric(array('a'=>'b', 'c'=>'d'));
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testStringIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Numeric('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNumericStringIsValid(){
		$this->fixture = new Vm_Validate_Is_Numeric('123456789');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testIntegerIsValid(){
		$this->fixture = new Vm_Validate_Is_Numeric(12);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Numeric(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsValid(){
		$this->fixture = new Vm_Validate_Is_Numeric(null);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testEmptyIsValid(){
		$this->fixture = new Vm_Validate_Is_Numeric('');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Numeric(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	//This validates because the test only throws an error when a value exists. 
	protected function testBooleanFalseIsValid(){
		$this->fixture = new Vm_Validate_Is_Numeric(FALSE);
		return $this->assertTrue($this->fixture->validates());		
	}	

	protected function testFloatIsValid(){
		$this->fixture = new Vm_Validate_Is_Numeric(3.14159265);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsValid(){
		$this->fixture = new Vm_Validate_Is_Numeric(1e7);
		return $this->assertTrue($this->fixture->validates());		
	}	
}
?>