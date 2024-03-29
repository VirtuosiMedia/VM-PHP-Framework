<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Credit\Discover class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Validate\Credit
 * @uses Vm\Validate\Credit\Discover
 */
namespace Tests\Vm\Validate\Credit;

use Vm\Validate\Credit\Discover;

class DiscoverTest extends \Tests\Test {

	protected function testDefaultError(){
		$credit = new Discover('creditcard');
		return $this->assertEqual($credit->getError(), 'Please enter a valid Discover credit card number');
	}

	protected function testCustomError(){
		$credit = new Discover('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testAmexNumber34(){
		$credit = new Discover('340975483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testAmexNumber37(){
		$credit = new Discover('370163483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber6011(){
		$credit = new Discover('6011435609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber6011TooLong(){
		$credit = new Discover('60114356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber6011TooShort(){
		$credit = new Discover('601143560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112(){
		$credit = new Discover('6011235609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber60112TooLong(){
		$credit = new Discover('60112356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112TooShort(){
		$credit = new Discover('60124560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113(){
		$credit = new Discover('6011335609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber60113TooLong(){
		$credit = new Discover('60113356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113TooShort(){
		$credit = new Discover('601134560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114(){
		$credit = new Discover('6011435609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber60114TooLong(){
		$credit = new Discover('60114356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114TooShort(){
		$credit = new Discover('601144560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622125Invalid(){
		$credit = new Discover('6221255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testDiscoverNumber622126(){
		$credit = new Discover('6221265609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber622126TooLong(){
		$credit = new Discover('62212656098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622126TooShort(){
		$credit = new Discover('622126560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925(){
		$credit = new Discover('6229255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber622925TooLong(){
		$credit = new Discover('62292556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925TooShort(){
		$credit = new Discover('622925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622926Invalid(){
		$credit = new Discover('6229265609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber643Invalid(){
		$credit = new Discover('6439255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testDiscoverNumber644(){
		$credit = new Discover('6449255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber644TooLong(){
		$credit = new Discover('64492556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber644TooShort(){
		$credit = new Discover('644925560987184');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber649(){
		$credit = new Discover('6499255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber649TooLong(){
		$credit = new Discover('64992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber649TooShort(){
		$credit = new Discover('649925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber65(){
		$credit = new Discover('6599255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber65TooLong(){
		$credit = new Discover('65992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber65TooShort(){
		$credit = new Discover('659925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber51(){
		$credit = new Discover('5199255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber55(){
		$credit = new Discover('5599255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new Discover('4199255609871840');
		return $this->assertFalse($credit->validates());
	}	
}