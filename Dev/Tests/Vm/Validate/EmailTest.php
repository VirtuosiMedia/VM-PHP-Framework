<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Email class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_EmailTest extends Tests_Test {
	
	protected function testSimpleEmailValidates(){
		$this->fixture = new Vm_Validate_Email('contact@virtuosimedia.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDotEmailValidates(){
		$this->fixture = new Vm_Validate_Email('john.doe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testHyphenEmailValidates(){
		$this->fixture = new Vm_Validate_Email('john-doe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testNumberEmailValidates(){
		$this->fixture = new Vm_Validate_Email('johndoe1@name.com');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testUnderscoreEmailValidates(){
		$this->fixture = new Vm_Validate_Email('john_doe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testPercentEmailValidates(){
		$this->fixture = new Vm_Validate_Email('john%doe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testCamelCaseEmailValidates(){
		$this->fixture = new Vm_Validate_Email('johnDoe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testSubdomainEmailValidates(){
		$this->fixture = new Vm_Validate_Email('johndoe@email.name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testHyphenDomainEmailValidates(){
		$this->fixture = new Vm_Validate_Email('johndoe@email-name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testUkDomainEmailValidates(){
		$this->fixture = new Vm_Validate_Email('johndoe@email.name.co.uk');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testMobileDomainEmailValidates(){
		$this->fixture = new Vm_Validate_Email('johndoe@email.mobi');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testUnderscoreDomainEmailValidatesFalse(){
		$this->fixture = new Vm_Validate_Email('johndoe@email_name.com');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testSpaceEmailValidatesFalse(){
		$this->fixture = new Vm_Validate_Email('john doe@name.com');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testCharactersEmailValidatesFalse(){
		$this->fixture = new Vm_Validate_Email('johndoe#!)*@name.com');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testNoAtSymbolEmailValidatesFalse(){
		$this->fixture = new Vm_Validate_Email('johndoename.com');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testNoLocalEmailValidatesFalse(){
		$this->fixture = new Vm_Validate_Email('@name.com');
		return $this->assertFalse($this->fixture->validates());
	}	
	
	protected function testNoHostEmailValidatesFalse(){
		$this->fixture = new Vm_Validate_Email('johndoe@.com');
		return $this->assertFalse($this->fixture->validates());
	}	

	protected function testNoDomainExtensionEmailValidatesFalse(){
		$this->fixture = new Vm_Validate_Email('johndoe@name');
		return $this->assertFalse($this->fixture->validates());
	}	
}
?>