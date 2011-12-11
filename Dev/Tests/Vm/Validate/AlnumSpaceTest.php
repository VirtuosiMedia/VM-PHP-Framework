<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @group: VM PHP Framework
 * @subgroup: Validators
 * @description: Tests the Vm\Validate\AlnumSpace class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\AlnumSpace
 */
namespace Tests\Vm\Validate;

use Vm\Validate\AlnumSpace;

class AlnumSpaceTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new AlnumSpace('John Doe 45');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new AlnumSpace('JohnDoe45');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new AlnumSpace('JohnDoe14!');
		return $this->assertEqual($this->fixture->getError(), 'This field may only contain letters, numbers, or spaces');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new AlnumSpace('JohnDoe14!');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new AlnumSpace('JohnDoe14!4', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testCharactersCauseError(){
		$this->fixture = new AlnumSpace('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}		
}