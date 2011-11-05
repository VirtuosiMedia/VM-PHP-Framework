<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Alpha class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_AlphaTest extends Tests_Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Vm_Validate_Alpha('JohnDoe');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Vm_Validate_Alpha('JohnDoe');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Alpha('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'This field may only contain letters');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Vm_Validate_Alpha('JohnDoe14');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Alpha('JohnDoe14', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testNumbersCauseError(){
		$this->fixture = new Vm_Validate_Alpha('123514');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new Vm_Validate_Alpha('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Vm_Validate_Alpha(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}	
}
?>