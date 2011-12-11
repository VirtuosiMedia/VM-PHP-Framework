<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\IsBool class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\IsBool
 */
namespace Tests\Vm\Validate;

use Vm\Validate\IsBool;

class IsBoolTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new IsBool('String');
		return $this->assertEqual($this->fixture->getError(), 'Value must be boolean');
	}
	
	protected function testCustomError(){
		$this->fixture = new IsBool('String', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testArrayIsInvalid(){
		$this->fixture = new IsBool(array());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testStringIsInvalid(){
		$this->fixture = new IsBool('string');
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testIntegerIsInvalid(){
		$this->fixture = new IsBool(12);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testObjectIsInvalid(){
		$this->fixture = new IsBool(new DateTime());
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testNullIsInvalid(){
		$this->fixture = new IsBool(null);
		return $this->assertFalse($this->fixture->validates());		
	}

	protected function testBooleanTrueIsValid(){
		$this->fixture = new IsBool(TRUE);
		return $this->assertTrue($this->fixture->validates());		
	}
	
	protected function testBooleanFalseIsValid(){
		$this->fixture = new IsBool(FALSE);
		return $this->assertTrue($this->fixture->validates());		
	}	

	protected function testNumericIsInvalid(){
		$this->fixture = new IsBool(3.14159265);
		return $this->assertFalse($this->fixture->validates());		
	}	
}