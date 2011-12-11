<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Credit class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\Alnum
 */
namespace Tests\Vm\Validate;

use Vm\Validate\Credit;

class CreditTest extends \Tests\Test {

	protected function testDefaultError(){
		$credit = new Credit('creditcard');
		return $this->assertEqual($credit->getError(), 'Please enter a valid credit card number');
	}

	protected function testCustomError(){
		$credit = new Credit('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testAmexNumber34(){
		$credit = new Credit('340975483212975');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testAmexNumber37(){
		$credit = new Credit('370163483212975');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testAmexNumber37TooLong(){
		$credit = new Credit('3701463483212975');
		return $this->assertFalse($credit->validates());
	}

	protected function testAmexNumber37TooShort(){
		$credit = new Credit('37014634212975');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber6011(){
		$credit = new Credit('6011435609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber6011TooLong(){
		$credit = new Credit('60114356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber6011TooShort(){
		$credit = new Credit('601143560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112(){
		$credit = new Credit('6011235609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber60112TooLong(){
		$credit = new Credit('60112356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112TooShort(){
		$credit = new Credit('60124560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113(){
		$credit = new Credit('6011335609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber60113TooLong(){
		$credit = new Credit('60113356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113TooShort(){
		$credit = new Credit('601134560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114(){
		$credit = new Credit('6011435609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber60114TooLong(){
		$credit = new Credit('60114356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114TooShort(){
		$credit = new Credit('601144560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622125Invalid(){
		$credit = new Credit('6221255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testDiscoverNumber622126(){
		$credit = new Credit('6221265609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber622126TooLong(){
		$credit = new Credit('62212656098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622126TooShort(){
		$credit = new Credit('622126560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925(){
		$credit = new Credit('6229255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber622925TooLong(){
		$credit = new Credit('62292556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925TooShort(){
		$credit = new Credit('622925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622926Invalid(){
		$credit = new Credit('6229265609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber643Invalid(){
		$credit = new Credit('6439255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testDiscoverNumber644(){
		$credit = new Credit('6449255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber644TooLong(){
		$credit = new Credit('64492556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber644TooShort(){
		$credit = new Credit('644925560987184');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber649(){
		$credit = new Credit('6499255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber649TooLong(){
		$credit = new Credit('64992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber649TooShort(){
		$credit = new Credit('649925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber65(){
		$credit = new Credit('6599255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber65TooLong(){
		$credit = new Credit('65992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber65TooShort(){
		$credit = new Credit('659925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber50Invalid(){
		$credit = new Credit('5099255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testMastercardNumber51(){
		$credit = new Credit('5199255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testMastercardNumber51TooLong(){
		$credit = new Credit('51992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber51TooShort(){
		$credit = new Credit('519925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber55(){
		$credit = new Credit('5599255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testMastercardNumber55TooLong(){
		$credit = new Credit('55992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber55TooShort(){
		$credit = new Credit('559925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber56Invalid(){
		$credit = new Credit('5699255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new Credit('4199255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testVisaNumber4TooLong(){
		$credit = new Credit('41992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4TooShort(){
		$credit = new Credit('419925560987184');
		return $this->assertFalse($credit->validates());
	}	
}