<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_AlphaSpace class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_AlphaSpaceTest extends Tests_Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Vm_Validate_AlphaSpace('John Doe');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Vm_Validate_AlphaSpace('JohnDoe');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_AlphaSpace('JohnDoe14');
		return $this->assertEqual($this->fixture->getError(), 'This field may only contain letters or spaces');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Vm_Validate_AlphaSpace('JohnDoe14');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_AlphaSpace('JohnDoe14', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testNumbersCauseError(){
		$this->fixture = new Vm_Validate_AlphaSpace('123514');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new Vm_Validate_AlphaSpace('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}	
}
?>