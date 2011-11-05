<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Credit_Amex class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Credit_AmexTest extends Tests_Test {

	protected function testDefaultError(){
		$credit = new Vm_Validate_Credit_Amex('creditcard');
		return $this->assertEqual($credit->getError(), 'Please enter a valid American Express credit card number');
	}

	protected function testCustomError(){
		$credit = new Vm_Validate_Credit_Amex('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testAmexNumber34(){
		$credit = new Vm_Validate_Credit_Amex('340975483212975');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testAmexNumber37(){
		$credit = new Vm_Validate_Credit_Amex('370163483212975');
		return $this->assertTrue($credit->validates());
	}
	
	protected function testAmexNumber37TooLong(){
		$credit = new Vm_Validate_Credit_Amex('3701463483212975');
		return $this->assertFalse($credit->validates());
	}

	protected function testAmexNumber37TooShort(){
		$credit = new Vm_Validate_Credit_Amex('37014634212975');
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
		$credit = new Vm_Validate_Credit_Amex('6599255609871840');
		return $this->assertFalse($credit->validates());
	}
		
	protected function testMastercardNumber51(){
		$credit = new Vm_Validate_Credit_Amex('5199255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new Vm_Validate_Credit_Amex('4199255609871840');
		return $this->assertFalse($credit->validates());
	}
}
?>