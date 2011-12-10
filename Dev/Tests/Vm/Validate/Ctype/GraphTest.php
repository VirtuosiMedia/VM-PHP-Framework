<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Validate\Ctype\Graph class
 * @requirements PHP 5.3 or higher
 * @namespace Tests\Vm\Validate\Ctype
 * @uses Vm\Validate\Ctype\Graph
 */
namespace Tests\Vm\Validate\Ctype;

use Vm\Validate\Ctype\Graph;

class GraphTest extends \Tests\Test {
	
	protected function testValidatesTrue(){
		$this->fixture = new Graph("~!@#$%^&*()-+QW,>asdfsdwwse123.\/");
		return $this->assertTrue($this->fixture->validates());
	}

	protected function testErrorIsNull(){
		$this->fixture = new Graph("~!@#$%^&*()-+QW,>asdfsdwwse123.\/");
		return $this->assertNull($this->fixture->getError());
	}
	
	protected function testDefaultError(){
		$this->fixture = new Graph("JohnDoe14\t\r\n");
		return $this->assertEqual(
			$this->fixture->getError(), 
			'This field must contain only visibly printable characters'
		);
	}
	
	protected function testValidatesFalse(){
		$this->fixture = new Graph("JohnDoe14\t\r\n");
		return $this->assertFalse($this->fixture->validates());
	}
	
	protected function testCustomError(){
		$this->fixture = new Graph("JohnDoe14\t\r\n", 'What are you thinking?');
		return $this->assertEqual($this->fixture->getError(), 'What are you thinking?');
	}
	
	protected function testWhiteSpaceCausesError(){
		$this->fixture = new Graph(' 	   ');
		return $this->assertFalse($this->fixture->validates());
	}	
}