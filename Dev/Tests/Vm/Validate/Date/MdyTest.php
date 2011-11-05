<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Date_Mdy class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Date_MdyTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Date_Mdy('Not a real date');
		return $this->assertEqual($this->fixture->getError(), 'Please enter a valid date in M/D/Y format');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Date_Mdy('Not a real date', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testDateSlashSeparatorValid(){
		$this->fixture = new Vm_Validate_Date_Mdy('07/04/1976');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testDateHyphenSeparatorValid(){
		$this->fixture = new Vm_Validate_Date_Mdy('07-04-1976');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateDotSeparatorValid(){
		$this->fixture = new Vm_Validate_Date_Mdy('07.04.1976');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateSpaceSeparatorValid(){
		$this->fixture = new Vm_Validate_Date_Mdy('07 04 1976');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testDateNoSeparatorInvalid(){
		$this->fixture = new Vm_Validate_Date_Mdy('07041976');
		return $this->assertFalse($this->fixture->validates());
	}	

	protected function testFourDigitYearValid(){
		$this->fixture = new Vm_Validate_Date_Mdy('07/04/2076');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testTwoDigitYearValid(){
		$this->fixture = new Vm_Validate_Date_Mdy('07/04/76');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testMonthComesFirst(){
		$this->fixture = new Vm_Validate_Date_Mdy('13/04/76');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testDaysOfMonthCap(){
		$this->fixture = new Vm_Validate_Date_Mdy('07/32/76');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testPre1900Fails(){
		$this->fixture = new Vm_Validate_Date_Mdy('07/04/1899');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testPost2099Fails(){
		$this->fixture = new Vm_Validate_Date_Mdy('07/04/2100');
		return $this->assertFalse($this->fixture->validates());
	}
}
?>