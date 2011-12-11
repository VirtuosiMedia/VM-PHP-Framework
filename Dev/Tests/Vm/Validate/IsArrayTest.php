<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsArray class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsArray
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsArray;

class IsArrayTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsArray('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be an array');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsArray('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsValid(){
		$this->fixture = new IsArray(array());
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new IsArray('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsInvalid(){
		$this->fixture = new IsArray(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsArray(new \DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new IsArray(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsArray(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new IsArray(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testNumericIsInvalid(){
		$this->fixture = new IsArray(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}	
}