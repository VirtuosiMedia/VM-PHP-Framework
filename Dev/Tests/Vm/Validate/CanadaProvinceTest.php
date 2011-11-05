<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Validate_CanadaProvince class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_Validate_CanadaProvinceTest extends Tests_Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Vm_Validate_CanadaProvince('AB');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Vm_Validate_CanadaProvince('AB');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Vm_Validate_CanadaProvince('AL');
		return $this->assertEqual($this->fixture->getError(), 'Please enter a valid 2-letter Canadian province abbreviation');
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Vm_Validate_CanadaProvince('AL');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Vm_Validate_CanadaProvince('NY', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new Vm_Validate_CanadaProvince('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Vm_Validate_CanadaProvince(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testAlberta(){
		$this->fixture = new Vm_Validate_CanadaProvince('AB');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testBritishColumbia(){
		$this->fixture = new Vm_Validate_CanadaProvince('BC');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testManitoba(){
		$this->fixture = new Vm_Validate_CanadaProvince('MB');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNewBrunswick(){
		$this->fixture = new Vm_Validate_CanadaProvince('NB');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNewfoundland(){
		$this->fixture = new Vm_Validate_CanadaProvince('NL');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNorthwestTerritories(){
		$this->fixture = new Vm_Validate_CanadaProvince('NT');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNovaScotia(){
		$this->fixture = new Vm_Validate_CanadaProvince('NS');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNunavut(){
		$this->fixture = new Vm_Validate_CanadaProvince('NU');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testOntario(){
		$this->fixture = new Vm_Validate_CanadaProvince('ON');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testPrinceEdwardIsland(){
		$this->fixture = new Vm_Validate_CanadaProvince('PE');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testQuebec(){
		$this->fixture = new Vm_Validate_CanadaProvince('QC');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testSaskatchewan(){
		$this->fixture = new Vm_Validate_CanadaProvince('SK');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testYukon(){
		$this->fixture = new Vm_Validate_CanadaProvince('YT');
		return $this->assertTrue($this->fixture->validates());
	}	
}
?>