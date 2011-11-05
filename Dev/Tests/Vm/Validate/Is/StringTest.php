<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Is_String class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Is_StringTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Is_String(123456);
		return $this->assertEqual($this->fixture->getError(), 'Value must be a string');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Is_String(123456, 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testEmptyArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(array());
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testNumericArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(array(1, 2, 3, 4));
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNonNumericArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(array('a'=>'b', 'c'=>'d'));
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testStringIsValid(){
		$this->fixture = new Vm_Validate_Is_String('string');
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testNumericStringIsValid(){
		$this->fixture = new Vm_Validate_Is_String('123456789');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testIntegerIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testEmptyIsValid(){
		$this->fixture = new Vm_Validate_Is_String('');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new Vm_Validate_Is_String(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}
?>