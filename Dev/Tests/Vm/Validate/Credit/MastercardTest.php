<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Credit_Mastercard class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Credit_MastercardTest extends Tests_Test {

	protected function testDefaultError(){
		$credit = new Vm_Validate_Credit_Mastercard('creditcard');
		return $this->assertEqual($credit->getError(), 'Please enter a valid Mastercard credit card number');
	}

	protected function testCustomError(){
		$credit = new Vm_Validate_Credit_Mastercard('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testAmexNumber34(){
		$credit = new Vm_Validate_Credit_Mastercard('340975483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testAmexNumber37(){
		$credit = new Vm_Validate_Credit_Mastercard('370163483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber6011(){
		$credit = new Vm_Validate_Credit_Amex('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112(){
		$credit = new Vm_Validate_Credit_Amex('6011235609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113(){
		$credit = new Vm_Validate_Credit_Amex('6011335609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114(){
		$credit = new Vm_Validate_Credit_Amex('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622126(){
		$credit = new Vm_Validate_Credit_Amex('6221265609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925(){
		$credit = new Vm_Validate_Credit_Amex('6229255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber644(){
		$credit = new Vm_Validate_Credit_Amex('6449255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber649(){
		$credit = new Vm_Validate_Credit_Amex('6499255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber65(){
		$credit = new Vm_Validate_Credit_Mastercard('6599255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber50Invalid(){
		$credit = new Vm_Validate_Credit_Mastercard('5099255609871840');
		return $this->assertFalse($credit->validates());
	}	
	
	protected function testMastercardNumber51(){
		$credit = new Vm_Validate_Credit_Mastercard('5199255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testMastercardNumber51TooLong(){
		$credit = new Vm_Validate_Credit_Mastercard('51992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber51TooShort(){
		$credit = new Vm_Validate_Credit_Mastercard('519925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber55(){
		$credit = new Vm_Validate_Credit_Mastercard('5599255609871840');
		return $this->assertTrue($credit->validates());
	}

	protected function testMastercardNumber55TooLong(){
		$credit = new Vm_Validate_Credit_Mastercard('55992556098718401');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber55TooShort(){
		$credit = new Vm_Validate_Credit_Mastercard('559925560987184');
		return $this->assertFalse($credit->validates());
	}

	protected function testMastercardNumber56Invalid(){
		$credit = new Vm_Validate_Credit_Mastercard('5699255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new Vm_Validate_Credit_Mastercard('4199255609871840');
		return $this->assertFalse($credit->validates());
	}	
}
?>