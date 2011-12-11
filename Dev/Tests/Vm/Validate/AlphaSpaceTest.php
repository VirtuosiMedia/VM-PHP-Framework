<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\AlphaSpace class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\AlphaSpace
 */
namespace Tests\Vm\Validate;

use Vm\Validate\AlphaSpace;

class AlphaSpaceTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new AlphaSpace('John Doe');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new AlphaSpace('JohnDoe');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new AlphaSpace('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'This field may only contain letters or spaces');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new AlphaSpace('JohnDoe14');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new AlphaSpace('JohnDoe14', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testNumbersCauseError(){
		$this->fixture = new AlphaSpace('123514');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new AlphaSpace('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}	
}