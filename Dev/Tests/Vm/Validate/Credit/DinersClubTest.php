<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Credit\DinersClub class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Validate\Credit
 */
namespace Tests\Vm\Validate\Credit;

class DinersClubTest extends \Tests\Test {

	protected function testDefaultError(){
		$credit = new \Vm\Validate\Credit\DinersClub('creditcard');
		return $this->assertEqual($credit->getError(), "Please enter a valid Diner's Club credit card number");
	}

	protected function testCustomError(){
		$credit = new \Vm\Validate\Credit\DinersClub('creditcard', 'This is a custom error');
		return $this->assertEqual($credit->getError(), 'This is a custom error');
	}	
	
	protected function testDinersClubNumber34(){
		$credit = new \Vm\Validate\Credit\DinersClub('340975483212975');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDinersClubNumber37(){
		$credit = new \Vm\Validate\Credit\DinersClub('370163483212975');
		return $this->assertFalse($credit->validates());
	}

	protected function testDinersClubNumber300(){
		$credit = new \Vm\Validate\Credit\DinersClub('30016348312975');
		return $this->assertTrue($credit->validates());
	}	

	protected function testDinersClubNumber305(){
		$credit = new \Vm\Validate\Credit\DinersClub('30516348312975');
		return $this->assertTrue($credit->validates());
	}

	protected function testDinersClubNumber36(){
		$credit = new \Vm\Validate\Credit\DinersClub('36516348312975');
		return $this->assertTrue($credit->validates());
	}	
	
	protected function testDinersClubNumber38(){
		$credit = new  \Vm\Validate\Credit\DinersClub('38516348312975');
		return $this->assertTrue($credit->validates());
	}		
	
	protected function testDiscoverNumber6011(){
		$credit = new \Vm\Validate\Credit\DinersClub('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60112(){
		$credit = new \Vm\Validate\Credit\DinersClub('6011235609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60113(){
		$credit = new \Vm\Validate\Credit\DinersClub('6011335609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber60114(){
		$credit = new \Vm\Validate\Credit\DinersClub('6011435609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622126(){
		$credit = new \Vm\Validate\Credit\DinersClub('6221265609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testDiscoverNumber622925(){
		$credit = new \Vm\Validate\Credit\DinersClub('6229255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber644(){
		$credit = new \Vm\Validate\Credit\DinersClub('6449255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber649(){
		$credit = new \Vm\Validate\Credit\DinersClub('6499255609871840');
		return $this->assertFalse($credit->validates());
	}
	
	protected function testDiscoverNumber65(){
		$credit = new \Vm\Validate\Credit\DinersClub('6599255609871840');
		return $this->assertFalse($credit->validates());
	}
		
	protected function testMastercardNumber51(){
		$credit = new \Vm\Validate\Credit\DinersClub('5199255609871840');
		return $this->assertFalse($credit->validates());
	}

	protected function testVisaNumber4(){
		$credit = new \Vm\Validate\Credit\DinersClub('4199255609871840');
		return $this->assertFalse($credit->validates());
	}	
}