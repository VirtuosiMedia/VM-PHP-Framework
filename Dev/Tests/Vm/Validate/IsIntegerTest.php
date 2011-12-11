<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsInteger class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsArray
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsInteger;

class IsIntegerTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsInteger('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be an integer');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsInteger('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new IsInteger(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new IsInteger('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsValid(){
		$this->fixture = new IsInteger(12);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsInteger(new \DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new IsInteger(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsInteger(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new IsInteger(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new IsInteger(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new IsInteger(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}