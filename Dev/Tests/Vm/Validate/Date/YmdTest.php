<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Date_Ymd class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Date_YmdTest extends Tests_Test {
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_Date_Ymd('Not a real date');
		return $this->assertEqual($this->fixture->getError(), 'Please enter a valid date in Y/M/D format');
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_Date_Ymd('Not a real date', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testDateSlashSeparatorValid(){
		$this->fixture = new Vm_Validate_Date_Ymd('1976/07/04');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testDateHyphenSeparatorValid(){
		$this->fixture = new Vm_Validate_Date_Ymd('1976-07-04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateDotSeparatorValid(){
		$this->fixture = new Vm_Validate_Date_Ymd('1976.07.04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateSpaceSeparatorValid(){
		$this->fixture = new Vm_Validate_Date_Ymd('1976 07 04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateNoSeparatorInvalid(){
		$this->fixture = new Vm_Validate_Date_Mdy('19760704');
		return $this->assertFalse($this->fixture->validates());
	}	
	
	protected function testFourDigitYearValid(){
		$this->fixture = new Vm_Validate_Date_Ymd('2076/07/04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testTwoDigitYearValid(){
		$this->fixture = new Vm_Validate_Date_Ymd('76/07/04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testMonthComesSecond(){
		$this->fixture = new Vm_Validate_Date_Ymd('76/13/04');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testDaysOfMonthCap(){
		$this->fixture = new Vm_Validate_Date_Ymd('76/07/32');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testPre1900Fails(){
		$this->fixture = new Vm_Validate_Date_Ymd('1899/07/04');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testPost2099Fails(){
		$this->fixture = new Vm_Validate_Date_Ymd('2100/07/04');
		return $this->assertFalse($this->fixture->validates());
	}	
}
?>