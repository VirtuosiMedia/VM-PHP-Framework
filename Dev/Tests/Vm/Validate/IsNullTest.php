<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsNull class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsNull
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsNull;

class IsNullTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsNull('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be NULL');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsNull('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new IsNull(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new IsNull('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsInvalid(){
		$this->fixture = new IsNull(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsNull(new \DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsValid(){
		$this->fixture = new IsNull(null);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsNull(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new IsNull(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new IsNull(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new IsNull(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}