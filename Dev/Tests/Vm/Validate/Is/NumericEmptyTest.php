<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Is_NumericEmpty class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Is_NumericEmptyTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be numeric');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testEmptyArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(array());
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testNumericArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(array(1, 2, 3, 4));
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNonNumericArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(array('a'=>'b', 'c'=>'d'));
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testStringIsInvalid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNumericStringIsValid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty('123456789');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testIntegerIsValid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(12);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testEmptyIsInvalid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty('');
		return $this->assertFalse($this->fixture->validates());		
	}	
	
	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsValid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(3.14159265);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsValid(){
		$this->fixture = new Vm_Validate_Is_NumericEmpty(1e7);
		return $this->assertTrue($this->fixture->validates());		
	}	
}
?>