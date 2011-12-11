<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Email class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\Email
 */
namespace Tests\Vm\Validate;

use Vm\Validate\Email;

class EmailTest extends \Tests\Test {
	
	protected function testSimpleEmailValidates(){
		$this->fixture = new Email('contact@virtuosimedia.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDotEmailValidates(){
		$this->fixture = new Email('john.doe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testHyphenEmailValidates(){
		$this->fixture = new Email('john-doe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testNumberEmailValidates(){
		$this->fixture = new Email('johndoe1@name.com');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testUnderscoreEmailValidates(){
		$this->fixture = new Email('john_doe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testPercentEmailValidates(){
		$this->fixture = new Email('john%doe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testCamelCaseEmailValidates(){
		$this->fixture = new Email('johnDoe@name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testSubdomainEmailValidates(){
		$this->fixture = new Email('johndoe@email.name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testHyphenDomainEmailValidates(){
		$this->fixture = new Email('johndoe@email-name.com');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testUkDomainEmailValidates(){
		$this->fixture = new Email('johndoe@email.name.co.uk');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testMobileDomainEmailValidates(){
		$this->fixture = new Email('johndoe@email.mobi');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testUnderscoreDomainEmailValidatesFalse(){
		$this->fixture = new Email('johndoe@email_name.com');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testSpaceEmailValidatesFalse(){
		$this->fixture = new Email('john doe@name.com');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testCharactersEmailValidatesFalse(){
		$this->fixture = new Email('johndoe#!)*@name.com');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testNoAtSymbolEmailValidatesFalse(){
		$this->fixture = new Email('johndoename.com');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testNoLocalEmailValidatesFalse(){
		$this->fixture = new Email('@name.com');
		return $this->assertFalse($this->fixture->validates());
	}	
	
	protected function testNoHostEmailValidatesFalse(){
		$this->fixture = new Email('johndoe@.com');
		return $this->assertFalse($this->fixture->validates());
	}	

	protected function testNoDomainExtensionEmailValidatesFalse(){
		$this->fixture = new Email('johndoe@name');
		return $this->assertFalse($this->fixture->validates());
	}	
}