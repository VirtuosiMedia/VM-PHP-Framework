<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm_Klass class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm
 */
namespace Tests\Vm;

class KlassTest extends \Tests\Test {

	protected function setUp(){
		$this->fixture = new \Vm\Klass();
	}
	
	protected function testSetGetOptions(){
		$this->fixture->setOptions(array('option1'=>'opt1', 'option2'=>'opt2'));
		$options = $this->fixture->getOptions();
		return $this->assertEqual($options['option1'], 'opt1');
	}
	
	protected function testSetNewDefaultOptions(){
		$this->fixture->setOptions(
			array('option1'=>'opt1', 'option2'=>'opt2'), 
			array('option3'=>'opt3', 'option4'=>'opt4')
		);
		$options = $this->fixture->getOptions();
		return $this->assertEqual($options['option3'], 'opt3');
	}	
}