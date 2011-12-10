<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Ctype\PrintC class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Ctype
 * @uses Vm\Validate\Ctype\PrintC
 */
namespace Tests\Vm\Validate\Ctype;

use Vm\Validate\Ctype\PrintC;

class PrintCTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new PrintC("~!@#$%^&*()-+QW,>asdfsd wwse123.\/");
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new PrintC("~!@#$%^&*()-+QW,>asdfsdw wse123.\/");
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new PrintC("JohnDoe14\t\r\n");
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only printable characters');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new PrintC("JohnDoe14\t\r\n");
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new PrintC("JohnDoe14\t\r\n", 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testWhiteSpaceDoesNotCauseError(){
		$this->fixture = new PrintC(' 	   ');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testControlCharactersCauseError(){
		$this->fixture = new PrintC("\r\n\t");
		return $this->assertFalse($this->fixture->validates());
	}	
}