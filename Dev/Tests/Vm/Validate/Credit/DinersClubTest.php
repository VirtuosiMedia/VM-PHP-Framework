<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_Credit_DinersClub class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_Credit_DinersClubTest extends Tests_Test {

	protected function testDefaultError(){
		$credit = new Vm_Validate_Credit_DinersClub('creditcard');
		return $this->assertEqual($credit->getError(), "Please enter a valid Diner's Club credit card number");
	}

	protected function testCustomError(){
		$credit = new Vm_Validate_Credit_DinersClub('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testAmexNumber34(){
		$credit = new Vm_Validate_Credit_DinersClub('340975483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testAmexNumber37(){
		$credit = new Vm_Validate_Credit_DinersClub('370163483212975');
		return $this->assertFalse($credit->validates());
	}

	protected function testDinersClubNumber300(){
		$credit = new Vm_Validate_Credit_DinersClub('30016348312975');
		return $this->assertTrue($credit->validates());
	}	

	protected function testDinersClubNumber305(){
		$credit = new Vm_Validate_Credit_DinersClub('30516348312975');
		return $this->assertTrue($credit->validates());
	}

	protected function testDinersClubNumber36(){
		$credit = new Vm_Validate_Credit_DinersClub('36516348312975');
		return $this->assertTrue($credit->validates());
	}	
	
	protected function testDinersClubNumber38(){
		$credit = new Vm_Validate_Credit_DinersClub('38516348312975');
		return $this->assertTrue($credit->validates());
	}		
	
	protected function testDiscoverNumber6011(){
		$credit = new Vm_Validate_Credit_DinersClub('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112(){
		$credit = new Vm_Validate_Credit_DinersClub('6011235609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113(){
		$credit = new Vm_Validate_Credit_DinersClub('6011335609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114(){
		$credit = new Vm_Validate_Credit_DinersClub('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622126(){
		$credit = new Vm_Validate_Credit_DinersClub('6221265609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925(){
		$credit = new Vm_Validate_Credit_DinersClub('6229255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber644(){
		$credit = new Vm_Validate_Credit_DinersClub('6449255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber649(){
		$credit = new Vm_Validate_Credit_DinersClub('6499255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber65(){
		$credit = new Vm_Validate_Credit_DinersClub('6599255609871840');
		return $this->assertFalse($credit->validates());
	}
		
	protected function testMastercardNumber51(){
		$credit = new Vm_Validate_Credit_DinersClub('5199255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new Vm_Validate_Credit_DinersClub('4199255609871840');
		return $this->assertFalse($credit->validates());
	}	
}
?>