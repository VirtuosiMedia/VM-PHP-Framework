<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Upper class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\Upper
 */
namespace Tests\Vm\Validate;

use Vm\Validate\Upper;

class UpperTest extends \Tests\Test {

	protected function testLowerCaseDoesNotValidate(){
		$this->fixture = new Upper('alllowercase');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Upper('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'Please enter a only uppercase letters');
	}
	
	protected function testCustomError(){
		$this->fixture = new Upper('JohnDoe14', 'Do not do that!');
		return $this->assertEqual($this->fixture->getError(), 'Do not do that!');
	}

	protected function testUpperCaseValidates(){
		$this->fixture = new Upper('ALLUPPERCASE');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNumbersDoNotValidate(){
		$this->fixture = new Upper('alllowercase1');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testCharactersDoNotValidate(){
		$this->fixture = new Upper('ALLUPPERCASE!');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testSpacesDoNotValidate(){
		$this->fixture = new Upper('ALL UPPERCASE');
		return $this->assertFalse($this->fixture->validates());
	}	
}