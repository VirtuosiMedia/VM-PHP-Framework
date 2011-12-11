<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Date\Mdy class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Date
 * @uses Vm\Validate\Date\Mdy
 */
namespace Tests\Vm\Validate\Date;

use Vm\Validate\Date\Mdy;

class MdyTest extends \Tests\Test {
	
	protected function testDefaultError(){
		$this->fixture = new Mdy('Not a real date');
		return $this->assertEqual($this->fixture->getError(), 'Please enter a valid date in M/D/Y format');
	}
	
	protected function testCustomError(){
		$this->fixture = new Mdy('Not a real date', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}

	protected function testDateSlashSeparatorValid(){
		$this->fixture = new Mdy('07/04/1976');
		return $this->assertTrue($this->fixture->validates());
	}	
	
	protected function testDateHyphenSeparatorValid(){
		$this->fixture = new Mdy('07-04-1976');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateDotSeparatorValid(){
		$this->fixture = new Mdy('07.04.1976');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testDateSpaceSeparatorValid(){
		$this->fixture = new Mdy('07 04 1976');
		return $this->assertTrue($this->fixture->validates());
	}
	
	protected function testDateNoSeparatorInvalid(){
		$this->fixture = new Mdy('07041976');
		return $this->assertFalse($this->fixture->validates());
	}	

	protected function testFourDigitYearValid(){
		$this->fixture = new Mdy('07/04/2076');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testTwoDigitYearValid(){
		$this->fixture = new Mdy('07/04/76');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testMonthComesFirst(){
		$this->fixture = new Mdy('13/04/76');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testDaysOfMonthCap(){
		$this->fixture = new Mdy('07/32/76');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testPre1900Fails(){
		$this->fixture = new Mdy('07/04/1899');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testPost2099Fails(){
		$this->fixture = new Mdy('07/04/2100');
		return $this->assertFalse($this->fixture->validates());
	}
}