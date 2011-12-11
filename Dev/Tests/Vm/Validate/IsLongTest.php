<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsLong class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsLong
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsLong;

class IsLongTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsLong('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be an integer');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsLong('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new IsLong(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new IsLong('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsValid(){
		$this->fixture = new IsLong(12);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsLong(new \DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new IsLong(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsLong(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new IsLong(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new IsLong(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new IsLong(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}