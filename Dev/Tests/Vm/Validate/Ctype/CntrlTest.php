<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Ctype\Cntrl class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Ctype
 * @uses Vm\Validate\Ctype\Cntrl
 */
namespace Tests\Vm\Validate\Ctype;

use Vm\Validate\Ctype\Cntrl;

class CntrlTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Cntrl("\t\r\n");
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Cntrl("\t\r\n");
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Cntrl('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only control characters');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Cntrl('JohnDoe14');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Cntrl('JohnDoe14', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testNumbersCauseError(){
		$this->fixture = new Cntrl('123514');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new Cntrl('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Cntrl(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}	
}