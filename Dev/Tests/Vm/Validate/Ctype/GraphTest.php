<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Ctype_Graph class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Ctype_GraphTest extends Tests_Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Vm_Validate_Ctype_Graph("~!@#$%^&*()-+QW,>asdfsdwwse123.\/");
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Vm_Validate_Ctype_Graph("~!@#$%^&*()-+QW,>asdfsdwwse123.\/");
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Ctype_Graph("JohnDoe14\t\r\n");
		return $this->assertEqual($this->fixture->getError(), 'This field must contain only visibly printable characters');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Vm_Validate_Ctype_Graph("JohnDoe14\t\r\n");
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Ctype_Graph("JohnDoe14\t\r\n", 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Vm_Validate_Ctype_Graph(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}	
}
?>