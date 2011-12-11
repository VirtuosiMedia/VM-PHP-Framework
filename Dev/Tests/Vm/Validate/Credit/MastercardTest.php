<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Credit\Mastercard class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Validate\Credit
 * @uses Vm\Validate\Credit\Mastercard
 */
namespace Tests\Vm\Validate\Credit;

use Vm\Validate\Credit\Mastercard;

class MastercardTest extends \Tests\Test {

	protected function testDefaultError(){
		$credit = new Mastercard('creditcard');
		return $this->assertEqual($credit->getError(), 'Please enter a valid Mastercard credit card number');
	}

	protected function testCustomError(){
		$credit = new Mastercard('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testAmexNumber34(){
		$credit = new Mastercard('340975483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testAmexNumber37(){
		$credit = new Mastercard('370163483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber6011(){
		$credit = new Mastercard('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112(){
		$credit = new Mastercard('6011235609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113(){
		$credit = new Mastercard('6011335609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114(){
		$credit = new Mastercard('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622126(){
		$credit = new Mastercard('6221265609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925(){
		$credit = new Mastercard('6229255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber644(){
		$credit = new Mastercard('6449255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber649(){
		$credit = new Mastercard('6499255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber65(){
		$credit = new Mastercard('6599255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber50Invalid(){
		$credit = new Mastercard('5099255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testMastercardNumber51(){
		$credit = new Mastercard('5199255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testMastercardNumber51TooLong(){
		$credit = new Mastercard('51992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber51TooShort(){
		$credit = new Mastercard('519925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber55(){
		$credit = new Mastercard('5599255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testMastercardNumber55TooLong(){
		$credit = new Mastercard('55992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber55TooShort(){
		$credit = new Mastercard('559925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber56Invalid(){
		$credit = new Mastercard('5699255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new Mastercard('4199255609871840');
		return $this->assertFalse($credit->validates());
	}	
}