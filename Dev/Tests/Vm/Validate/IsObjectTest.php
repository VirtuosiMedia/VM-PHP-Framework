<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsObject class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsObject
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsObject;

class IsObjectTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsObject('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be an object');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsObject('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testEmptyArrayIsInvalid(){
		$this->fixture = new IsObject(array());
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testNumericArrayIsInvalid(){
		$this->fixture = new IsObject(array(1, 2, 3, 4));
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNonNumericArrayIsInvalid(){
		$this->fixture = new IsObject(array('a'=>'b', 'c'=>'d'));
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testStringIsInvalid(){
		$this->fixture = new IsObject('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNumericStringIsInvalid(){
		$this->fixture = new IsObject('123456789');
		return $this->assertFalse($this->fixture->validates());		
	}	
	
	protected function testIntegerIsInvalid(){
		$this->fixture = new IsObject(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsValid(){
		$this->fixture = new IsObject(new DateTime());
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new IsObject(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testEmptyIsInvalid(){
		$this->fixture = new IsObject('');
		return $this->assertFalse($this->fixture->validates());		
	}	
	
	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsObject(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new IsObject(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsInvalid(){
		$this->fixture = new IsObject(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsInvalid(){
		$this->fixture = new IsObject(1e7);
		return $this->assertFalse($this->fixture->validates());		
	}	
}