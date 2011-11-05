<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Credit class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_CreditTest extends Tests_Test {

	protected function testDefaultError(){
		$credit = new Vm_Validate_Credit('creditcard');
		return $this->assertEqual($credit->getError(), 'Please enter a valid credit card number');
	}

	protected function testCustomError(){
		$credit = new Vm_Validate_Credit('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testAmexNumber34(){
		$credit = new Vm_Validate_Credit('340975483212975');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testAmexNumber37(){
		$credit = new Vm_Validate_Credit('370163483212975');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testAmexNumber37TooLong(){
		$credit = new Vm_Validate_Credit('3701463483212975');
		return $this->assertFalse($credit->validates());
	}

	protected function testAmexNumber37TooShort(){
		$credit = new Vm_Validate_Credit('37014634212975');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber6011(){
		$credit = new Vm_Validate_Credit('6011435609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber6011TooLong(){
		$credit = new Vm_Validate_Credit('60114356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber6011TooShort(){
		$credit = new Vm_Validate_Credit('601143560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112(){
		$credit = new Vm_Validate_Credit('6011235609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber60112TooLong(){
		$credit = new Vm_Validate_Credit('60112356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112TooShort(){
		$credit = new Vm_Validate_Credit('60124560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113(){
		$credit = new Vm_Validate_Credit('6011335609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber60113TooLong(){
		$credit = new Vm_Validate_Credit('60113356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113TooShort(){
		$credit = new Vm_Validate_Credit('601134560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114(){
		$credit = new Vm_Validate_Credit('6011435609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber60114TooLong(){
		$credit = new Vm_Validate_Credit('60114356098718407');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114TooShort(){
		$credit = new Vm_Validate_Credit('601144560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622125Invalid(){
		$credit = new Vm_Validate_Credit('6221255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testDiscoverNumber622126(){
		$credit = new Vm_Validate_Credit('6221265609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber622126TooLong(){
		$credit = new Vm_Validate_Credit('62212656098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622126TooShort(){
		$credit = new Vm_Validate_Credit('622126560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925(){
		$credit = new Vm_Validate_Credit('6229255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber622925TooLong(){
		$credit = new Vm_Validate_Credit('62292556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925TooShort(){
		$credit = new Vm_Validate_Credit('622925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622926Invalid(){
		$credit = new Vm_Validate_Credit('6229265609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber643Invalid(){
		$credit = new Vm_Validate_Credit('6439255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testDiscoverNumber644(){
		$credit = new Vm_Validate_Credit('6449255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber644TooLong(){
		$credit = new Vm_Validate_Credit('64492556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber644TooShort(){
		$credit = new Vm_Validate_Credit('644925560987184');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber649(){
		$credit = new Vm_Validate_Credit('6499255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber649TooLong(){
		$credit = new Vm_Validate_Credit('64992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber649TooShort(){
		$credit = new Vm_Validate_Credit('649925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber65(){
		$credit = new Vm_Validate_Credit('6599255609871840');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testDiscoverNumber65TooLong(){
		$credit = new Vm_Validate_Credit('65992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber65TooShort(){
		$credit = new Vm_Validate_Credit('659925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber50Invalid(){
		$credit = new Vm_Validate_Credit('5099255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testMastercardNumber51(){
		$credit = new Vm_Validate_Credit('5199255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testMastercardNumber51TooLong(){
		$credit = new Vm_Validate_Credit('51992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber51TooShort(){
		$credit = new Vm_Validate_Credit('519925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber55(){
		$credit = new Vm_Validate_Credit('5599255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testMastercardNumber55TooLong(){
		$credit = new Vm_Validate_Credit('55992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber55TooShort(){
		$credit = new Vm_Validate_Credit('559925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber56Invalid(){
		$credit = new Vm_Validate_Credit('5699255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new Vm_Validate_Credit('4199255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testVisaNumber4TooLong(){
		$credit = new Vm_Validate_Credit('41992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4TooShort(){
		$credit = new Vm_Validate_Credit('419925560987184');
		return $this->assertFalse($credit->validates());
	}	
}
?>