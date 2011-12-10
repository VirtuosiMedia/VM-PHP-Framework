<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Ctype\Lower class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Ctype
 * @uses Vm\Validate\Ctype\Lower
 */
namespace Tests\Vm\Validate\Ctype;

use Vm\Validate\Ctype\Lower;

class LowerTest extends \Tests\Test {

	protected function testLowerCaseValidates(){
		$lower = new Lower('alllowercase');
		return $this->assertTrue($lower->validates());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Lower('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only lowercase letters');
	}
	
	protected function testCustomError(){
		$this->fixture = new Lower('JohnDoe14', 'Do not do that!');
		return $this->assertEqual($this->fixture->getError(), 'Do not do that!');
	}

	protected function testUpperCaseDoesNotValidate(){
		$lower = new Lower('allLowercase');
		return $this->assertFalse($lower->validates());
	}

	protected function testNumbersDoNotValidate(){
		$lower = new Lower('alllowercase1');
		return $this->assertFalse($lower->validates());
	}

	protected function testCharactersDoNotValidate(){
		$lower = new Lower('alllowercase!');
		return $this->assertFalse($lower->validates());
	}

	protected function testSpacesDoNotValidate(){
		$lower = new Lower('all lowercase');
		return $this->assertFalse($lower->validates());
	}	
}