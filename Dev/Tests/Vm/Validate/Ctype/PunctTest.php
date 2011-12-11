<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Ctype\Punct class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Ctype
 * @uses Vm\Validate\Ctype\Punct
 */
namespace Tests\Vm\Validate\Ctype;

use Vm\Validate\Ctype\Punct;

class PunctTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Punct("~!@#$%^&*()-+.,?");
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Punct("~!@#$%^&*()-+.,?");
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Punct("JohnDoe14\t\r\n");
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only punctuation characters');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Punct("JohnDoe14\t\r\n");
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Punct("JohnDoe14\t\r\n", 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Punct(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testControlCharactersCauseError(){
		$this->fixture = new Punct("\r\n\t");
		return $this->assertFalse($this->fixture->validates());
	}	
}