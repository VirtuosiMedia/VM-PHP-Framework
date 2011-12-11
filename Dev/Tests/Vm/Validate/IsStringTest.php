<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsString class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsString
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsString;

class IsStringTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsString(123456);
		return $this->assertEqual($this->fixture->getError(), 'Value must be a string');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsString(123456, 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testEmptyArrayIsInvalid(){
		$this->fixture = new IsString(array());
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testNumericArrayIsInvalid(){
		$this->fixture = new IsString(array(1, 2, 3, 4));
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNonNumericArrayIsInvalid(){
		$this->fixture = new IsString(array('a'=>'b', 'c'=>'d'));
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testStringIsValid(){
		$this->fixture = new IsString('string');
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testNumericStringIsValid(){
		$this->fixture = new IsString('123456789');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testIntegerIsInvalid(){
		$this->fixture = new IsString(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsString(new \DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new IsString(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testEmptyIsValid(){
		$this->fixture = new IsString('');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsString(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new IsString(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new IsString(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new IsString(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}