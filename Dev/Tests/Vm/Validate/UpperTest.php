<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Upper class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_UpperTest extends Tests_Test {

	protected function testLowerCaseDoesNotValidate(){
		$this->fixture = new Vm_Validate_Upper('alllowercase');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Upper('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'Please enter a only uppercase letters');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Upper('JohnDoe14', 'Do not do that!');
		return $this->assertEqual($this->fixture->getError(), 'Do not do that!');
	}

	protected function testUpperCaseValidates(){
		$this->fixture = new Vm_Validate_Upper('ALLUPPERCASE');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNumbersDoNotValidate(){
		$this->fixture = new Vm_Validate_Upper('alllowercase1');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testCharactersDoNotValidate(){
		$this->fixture = new Vm_Validate_Upper('ALLUPPERCASE!');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testSpacesDoNotValidate(){
		$this->fixture = new Vm_Validate_Upper('ALL UPPERCASE');
		return $this->assertFalse($this->fixture->validates());
	}	
}
?>