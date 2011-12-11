<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsFloat class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsFloat
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsFloat;

class IsFloatTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsFloat('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be a float');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsFloat('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new IsFloat(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new IsFloat('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsInvalid(){
		$this->fixture = new IsFloat(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsFloat(new \DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new IsFloat(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsFloat(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new IsFloat(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsValid(){
		$this->fixture = new IsFloat(3.14159265);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsValid(){
		$this->fixture = new IsFloat(1e7);
		return $this->assertTrue($this->fixture->validates());		
	}	
}