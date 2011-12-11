<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsInt class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsInt
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsInt;

class IsIntTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsInt('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be an integer');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsInt('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new IsInt(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new IsInt('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsValid(){
		$this->fixture = new IsInt(12);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsInt(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new IsInt(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsInt(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new IsInt(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new IsInt(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new IsInt(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}