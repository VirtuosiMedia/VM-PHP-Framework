<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Ctype\Space class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Ctype
 * @uses Vm\Validate\Ctype\Space
 */
namespace Tests\Vm\Validate\Ctype;

use Vm\Validate\Ctype\Space;

class SpaceTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Space("     \t \r\n\t");
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Space("     \t ");
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Space("JohnDoe14\t\r\n");
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only whitespace characters');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Space("JohnDoe14\t\r\n");
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Space("JohnDoe14\t\r\n", 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testWhiteSpaceDoNotCauseError(){
		$this->fixture = new Space(' 	   ');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testControlCharactersDoNotCauseError(){
		$this->fixture = new Space("\r\n\t");
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testPunctuationCausesError(){
		$this->fixture = new Space("!@#$~%^&*()_+<>?,./");
		return $this->assertFalse($this->fixture->validates());
	}	
}