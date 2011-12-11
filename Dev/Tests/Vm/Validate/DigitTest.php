<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Digit class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\Digit
 */
namespace Tests\Vm\Validate;

use Vm\Validate\Digit;

class DigitTest extends \Tests\Test {

	protected function testZeroToNineValid(){
		$this->fixture = new Digit(0123456789);
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testStringZeroToNineValid(){
		$this->fixture = new Digit('0123456789');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDecimalInvalid(){
		$this->fixture = new Digit('0.123456789');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testNegativeNumberInvalid(){
		$this->fixture = new Digit('-89');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testScientificNotationInvalid(){
		$this->fixture = new Digit('5.72�109');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testENotationInvalid(){
		$this->fixture = new Digit('6.0221415E23');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testLettersInvalid(){
		$this->fixture = new Digit('Twelve');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testCharactersInvalid(){
		$this->fixture = new Digit('!@#><,.');
		return $this->assertFalse($this->fixture->validates());
	}	
}