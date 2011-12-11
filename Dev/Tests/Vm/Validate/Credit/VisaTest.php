<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Credit\Visa class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Validate\Credit
 * @uses Vm\Validate\Credit\Visa
 */
namespace Tests\Vm\Validate\Credit;

use Vm\Validate\Credit\Visa;

class VisaTest extends \Tests\Test {

	protected function testDefaultError(){
		$credit = new Visa('creditcard');
		return $this->assertEqual($credit->getError(), 'Please enter a valid Visa credit card number');
	}

	protected function testCustomError(){
		$credit = new Visa('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testAmexNumber34(){
		$credit = new Visa('340975483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testAmexNumber37(){
		$credit = new Visa('370163483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber6011(){
		$credit = new Visa('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112(){
		$credit = new Visa('6011235609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113(){
		$credit = new Visa('6011335609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114(){
		$credit = new Visa('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622126(){
		$credit = new Visa('6221265609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925(){
		$credit = new Visa('6229255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber644(){
		$credit = new Visa('6449255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber649(){
		$credit = new Visa('6499255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber65(){
		$credit = new Visa('6599255609871840');
		return $this->assertFalse($credit->validates());
	}
		
	protected function testMastercardNumber51(){
		$credit = new Visa('5199255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new Visa('4199255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testVisaNumber4TooLong(){
		$credit = new Visa('41992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4TooShort(){
		$credit = new Visa('419925560987184');
		return $this->assertFalse($credit->validates());
	}	
}