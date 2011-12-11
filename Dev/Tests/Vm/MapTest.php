<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm_Map class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm
 */
namespace Tests\Vm;

class MapTest extends \Tests\Test {

	protected function setUp(){
		$this->fixture = new \Vm\Map(array('Virtuosi'=>'Media', 'web'=>'development', 'PHP'=>'framework'));
	}
	
	protected function testGetKey(){
		return $this->assertEqual($this->fixture->getKey('development'), 'web');
	}
	
	protected function testGetValue(){
		return $this->assertEqual($this->fixture->getValue('Virtuosi'), 'Media');
	}

	protected function testSet(){
		$this->fixture->set('unit', 'testing');
		return $this->assertEqual($this->fixture->getValue('unit'), 'testing');
	}
	
	protected function testSetMapExists(){
		$this->fixture->setMap(array('Bonnie'=>'Clyde', 'Ness'=>'Capone'));
		return $this->assertEqual($this->fixture->getValue('Bonnie'), 'Clyde');
	}

	protected function testSetMapResets(){
		$this->fixture->setMap(array());
		return $this->assertNotEqual($this->fixture->getValue('Virtuosi'), 'Media');
	}

	protected function testClearWithKey(){
		$this->fixture->clear('PHP');
		return $this->assertNotEqual($this->fixture->getValue('PHP'), 'Framework');
	}

	protected function testClearAll(){
		$this->fixture->clear();
		return $this->assertNotEqual($this->fixture->getValue('PHP'), 'framework');
	}
}