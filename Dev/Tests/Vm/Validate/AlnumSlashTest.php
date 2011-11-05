<?php
/**
 * @author: Virtuosi Media
 * @license: MIT License
 * @group: VM PHP Framework
 * @subgroup: Validators
 * @description: Tests the Vm_Validate_AlnumSlash class
 * Requirements: PHP 5.2 or higher
 */
class Tests_Vm_Validate_AlnumSlashTest extends Tests_Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Vm_Validate_AlnumSlash('JohnDoe45/');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testSlow(){
		sleep(1);
		return $this->assertTrue(FALSE);
	}	
	
	protected function testFail(){
		return $this->assertTrue(FALSE);
	}

	protected function testIncomplete(){
		return $this->incomplete();
	}
	
	protected function testSkip(){
		return $this->skip('This test had to be skipped for unknown reasons.');
	}	

	protected function testError(){
		$this->fixture = new Vm_Validate_AlnumSlash('JohnDoe45');
		trigger_error("This is a test error", E_USER_ERROR);
		return $this->skip('This will never be seen.');
	}

	protected function testException(){
		$this->fixture = new Vm_Validate_AlnumSlash('JohnDoe45');
		throw new Exception('Exception test');
		return $this->skip('This will never be seen.');
	}	
	
	protected function testErrorIsNull(){
		$this->fixture = new Vm_Validate_AlnumSlash('JohnDoe45');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_AlnumSlash('JohnDoe14!');
		return $this->assertEqual($this->fixture->getError(), 'This field may only contain letters, numbers, or forward slashes');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Vm_Validate_AlnumSlash('JohnDoe14!');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_AlnumSlash('JohnDoe14!4', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testCharactersCauseError(){
		$this->fixture = new Vm_Validate_AlnumSlash('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testBackslashCauseError(){
		$this->fixture = new Vm_Validate_AlnumSlash('JohnDoe45\\');
		return $this->assertFalse($this->fixture->validates());
	}	
	
	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Vm_Validate_AlnumSlash(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}			
}
?>