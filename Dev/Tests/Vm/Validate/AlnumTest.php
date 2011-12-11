<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @group VM PHP Framework
 * @subgroup Validators
 * @description Tests the Vm\Validate\Alnum class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\Alnum
 */
namespace Tests\Vm\Validate;

use Vm\Validate\Alnum;

class AlnumTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Alnum('JohnDoe45');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testValidatesTrue1(){
		$this->fixture = new Alnum('JohnDoe45');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testErrorIsNull(){
		$this->fixture = new Alnum('JohnDoe45');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Alnum('JohnDoe14!');
		return $this->assertEqual($this->fixture->getError(), 'This field may only contain letters or numbers');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Alnum('JohnDoe14!');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Alnum('JohnDoe14!4', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new Alnum('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Alnum(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}
}