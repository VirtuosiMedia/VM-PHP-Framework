<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Alpha class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\Alpha
 */
namespace Tests\Vm\Validate;

use Vm\Validate\Alpha;

class AlphaTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Alpha('JohnDoe');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Alpha('JohnDoe');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Alpha('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'This field may only contain letters');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Alpha('JohnDoe14');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Alpha('JohnDoe14', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testNumbersCauseError(){
		$this->fixture = new Alpha('123514');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new Alpha('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Alpha(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}	
}