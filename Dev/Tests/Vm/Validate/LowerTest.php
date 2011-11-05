<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Lower class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_LowerTest extends Tests_Test {

	protected function testLowerCaseValidates(){
		$lower = new Vm_Validate_Lower('alllowercase');
		return $this->assertTrue($lower->validates());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Lower('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'Please enter a only lowercase letters');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Lower('JohnDoe14', 'Do not do that!');
		return $this->assertEqual($this->fixture->getError(), 'Do not do that!');
	}

	protected function testUpperCaseDoesNotValidate(){
		$lower = new Vm_Validate_Lower('allLowercase');
		return $this->assertFalse($lower->validates());
	}

	protected function testNumbersDoNotValidate(){
		$lower = new Vm_Validate_Lower('alllowercase1');
		return $this->assertFalse($lower->validates());
	}

	protected function testCharactersDoNotValidate(){
		$lower = new Vm_Validate_Lower('alllowercase!');
		return $this->assertFalse($lower->validates());
	}

	protected function testSpacesDoNotValidate(){
		$lower = new Vm_Validate_Lower('all lowercase');
		return $this->assertFalse($lower->validates());
	}	
}
?>