<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Credit\Amex class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Credit
 * @uses Vm\Validate\Credit\Amex
 */
namespace Tests\Vm\Validate\Credit;

use Vm\Validate\Credit\Amex;

class AmexTest extends \Tests\Test {

	protected function testDefaultError(){
		$credit = new Amex('creditcard');
		return $this->assertEqual($credit->getError(), 'Please enter a valid American Express credit card number');
	}

	protected function testCustomError(){
		$credit = new Amex('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testAmexNumber34(){
		$credit = new Amex('340975483212975');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testAmexNumber37(){
		$credit = new Amex('370163483212975');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testAmexNumber37TooLong(){
		$credit = new Amex('3701463483212975');
		return $this->assertFalse($credit->validates());
	}

	protected function testAmexNumber37TooShort(){
		$credit = new Amex('37014634212975');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber6011(){
		$credit = new Amex('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112(){
		$credit = new Amex('6011235609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113(){
		$credit = new Amex('6011335609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114(){
		$credit = new Amex('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622126(){
		$credit = new Amex('6221265609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925(){
		$credit = new Amex('6229255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber644(){
		$credit = new Amex('6449255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber649(){
		$credit = new Amex('6499255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber65(){
		$credit = new Amex('6599255609871840');
		return $this->assertFalse($credit->validates());
	}
		
	protected function testMastercardNumber51(){
		$credit = new Amex('5199255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new Amex('4199255609871840');
		return $this->assertFalse($credit->validates());
	}
}