<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Is_Object class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Is_ObjectTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Is_Object('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be an object');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Is_Object('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testEmptyArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object(array());
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testNumericArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object(array(1, 2, 3, 4));
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNonNumericArrayIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object(array('a'=>'b', 'c'=>'d'));
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testStringIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNumericStringIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object('123456789');
		return $this->assertFalse($this->fixture->validates());		
	}	
	
	protected function testIntegerIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsValid(){
		$this->fixture = new Vm_Validate_Is_Object(new DateTime());
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testEmptyIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object('');
		return $this->assertFalse($this->fixture->validates());		
	}	
	
	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new Vm_Validate_Is_Object(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}
?>