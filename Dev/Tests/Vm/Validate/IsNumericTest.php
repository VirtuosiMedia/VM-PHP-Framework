<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsNumeric class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsNumeric
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsNumeric;

class IsNumericTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsNumeric('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be numeric');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsNumeric('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testEmptyArrayIsValid(){
		$this->fixture = new IsNumeric(array());
		return $this->assertTrue($this->fixture->validates());		
	}
	
	protected function testNumericArrayIsInvalid(){
		$this->fixture = new IsNumeric(array(1, 2, 3, 4));
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNonNumericArrayIsInvalid(){
		$this->fixture = new IsNumeric(array('a'=>'b', 'c'=>'d'));
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testStringIsInvalid(){
		$this->fixture = new IsNumeric('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNumericStringIsValid(){
		$this->fixture = new IsNumeric('123456789');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testIntegerIsValid(){
		$this->fixture = new IsNumeric(12);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsNumeric(new \DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsValid(){
		$this->fixture = new IsNumeric(null);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testEmptyIsValid(){
		$this->fixture = new IsNumeric('');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsNumeric(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	//This validates because the test only throws an error when a value exists. 
	protected function testBooleanFalseIsValid(){
		$this->fixture = new IsNumeric(FALSE);
		return $this->assertTrue($this->fixture->validates());		
	}	

	protected function testFloatIsValid(){
		$this->fixture = new IsNumeric(3.14159265);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsValid(){
		$this->fixture = new IsNumeric(1e7);
		return $this->assertTrue($this->fixture->validates());		
	}	
}