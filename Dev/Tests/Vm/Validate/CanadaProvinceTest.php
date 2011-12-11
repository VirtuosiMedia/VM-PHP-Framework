<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\CanadaProvince class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm\Validate
 * @uses Vm\Validate\CanadaProvince
 */
namespace Tests\Vm\Validate;

use Vm\Validate\CanadaProvince;

class CanadaProvinceTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new CanadaProvince('AB');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new CanadaProvince('AB');
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new CanadaProvince('AL');
		return $this->assertEqual(
			$this->fixture->getError(), 
			'Please enter a valid 2-letter Canadian province abbreviation'
		);
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new CanadaProvince('AL');
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new CanadaProvince('NY', 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testCharactersCauseError(){
		$this->fixture = new CanadaProvince('!@#%><,`');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testWhiteSpaceCausesError(){
		$this->fixture = new CanadaProvince(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}

	protected function testAlberta(){
		$this->fixture = new CanadaProvince('AB');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testBritishColumbia(){
		$this->fixture = new CanadaProvince('BC');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testManitoba(){
		$this->fixture = new CanadaProvince('MB');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNewBrunswick(){
		$this->fixture = new CanadaProvince('NB');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNewfoundland(){
		$this->fixture = new CanadaProvince('NL');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNorthwestTerritories(){
		$this->fixture = new CanadaProvince('NT');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNovaScotia(){
		$this->fixture = new CanadaProvince('NS');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testNunavut(){
		$this->fixture = new CanadaProvince('NU');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testOntario(){
		$this->fixture = new CanadaProvince('ON');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testPrinceEdwardIsland(){
		$this->fixture = new CanadaProvince('PE');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testQuebec(){
		$this->fixture = new CanadaProvince('QC');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testSaskatchewan(){
		$this->fixture = new CanadaProvince('SK');
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testYukon(){
		$this->fixture = new CanadaProvince('YT');
		return $this->assertTrue($this->fixture->validates());
	}	
}