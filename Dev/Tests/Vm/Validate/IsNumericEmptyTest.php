<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsNumericEmpty class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsNumericEmpty
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsNumericEmpty;

class IsNumericEmptyTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsNumericEmpty('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be numeric');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsNumericEmpty('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testEmptyArrayIsInvalid(){
		$this->fixture = new IsNumericEmpty(array());
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testNumericArrayIsInvalid(){
		$this->fixture = new IsNumericEmpty(array(1, 2, 3, 4));
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNonNumericArrayIsInvalid(){
		$this->fixture = new IsNumericEmpty(array('a'=>'b', 'c'=>'d'));
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testStringIsInvalid(){
		$this->fixture = new IsNumericEmpty('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNumericStringIsValid(){
		$this->fixture = new IsNumericEmpty('123456789');
		return $this->assertTrue($this->fixture->validates());		
	}	
	
	protected function testIntegerIsValid(){
		$this->fixture = new IsNumericEmpty(12);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsNumericEmpty(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new IsNumericEmpty(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testEmptyIsInvalid(){
		$this->fixture = new IsNumericEmpty('');
		return $this->assertFalse($this->fixture->validates());		
	}	
	
	protected function testBooleanTrueIsInvalid(){
		$this->fixture = new IsNumericEmpty(TRUE);
		return $this->assertFalse($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsInvalid(){
		$this->fixture = new IsNumericEmpty(FALSE);
		return $this->assertFalse($this->fixture->validates());		
	}	

	protected function testFloatIsValid(){
		$this->fixture = new IsNumericEmpty(3.14159265);
		return $this->assertTrue($this->fixture->validates());		
	}

	protected function testFloatScientificNotationIsValid(){
		$this->fixture = new IsNumericEmpty(1e7);
		return $this->assertTrue($this->fixture->validates());		
	}	
}