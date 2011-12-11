<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Date\Ymd class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Date
 * @uses Vm\Validate\Date\Ymd
 */
namespace Tests\Vm\Validate\Date;

use Vm\Validate\Date\Ymd;

class YmdTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new Ymd('Not a real date');
		return $this->assertEqual($this->fixture->getError(), 'Please enter a valid date in Y/M/D format');
	}
	
	protected function testCustomError(){
		$this->fixture = new Ymd('Not a real date', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testDateSlashSeparatorValid(){
		$this->fixture = new Ymd('1976/07/04');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testDateHyphenSeparatorValid(){
		$this->fixture = new Ymd('1976-07-04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateDotSeparatorValid(){
		$this->fixture = new Ymd('1976.07.04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateSpaceSeparatorValid(){
		$this->fixture = new Ymd('1976 07 04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateNoSeparatorInvalid(){
		$this->fixture = new Vm_Validate_Date_Mdy('19760704');
		return $this->assertFalse($this->fixture->validates());
	}	
	
	protected function testFourDigitYearValid(){
		$this->fixture = new Ymd('2076/07/04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testTwoDigitYearValid(){
		$this->fixture = new Ymd('76/07/04');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testMonthComesSecond(){
		$this->fixture = new Ymd('76/13/04');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testDaysOfMonthCap(){
		$this->fixture = new Ymd('76/07/32');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testPre1900Fails(){
		$this->fixture = new Ymd('1899/07/04');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testPost2099Fails(){
		$this->fixture = new Ymd('2100/07/04');
		return $this->assertFalse($this->fixture->validates());
	}	
}