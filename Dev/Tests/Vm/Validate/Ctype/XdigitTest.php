<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Ctype\Xdigit class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Ctype
 * @uses Vm\Validate\Ctype\Xdigit
 */
namespace Tests\Vm\Validate\Ctype;

use Vm\Validate\Ctype\Xdigit;

class XdigitTest extends \Tests\Test {

	protected function testDefaultError(){
		$this->fixture = new Xdigit('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only hexidecimal digits');
	}	
	
	protected function testZeroToNineValid(){
		$this->fixture = new Xdigit(0123456789);
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testStringZeroToNineValid(){
		$this->fixture = new Xdigit('0123456789');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDecimalInvalid(){
		$this->fixture = new Xdigit('0.123456789');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testNegativeNumberInvalid(){
		$this->fixture = new Xdigit('-89');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testScientificNotationInvalid(){
		$this->fixture = new Xdigit('5.72�109');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testENotationInvalid(){
		$this->fixture = new Xdigit('6.0221415E23');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testHexLettersValid(){
		$this->fixture = new Xdigit('ABCDEFabcdef');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testNonHexLettersInvalid(){
		$this->fixture = new Xdigit('GHIJKLMNOPQRSTUVWXYZghijklmnopqrstuvwxyz');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testCharactersInvalid(){
		$this->fixture = new Xdigit('!@#><,.');
		return $this->assertFalse($this->fixture->validates());
	}	
}