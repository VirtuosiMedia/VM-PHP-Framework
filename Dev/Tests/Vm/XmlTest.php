<?php 
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Xml class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm
 */
namespace Tests\Vm;

class XmlTest extends \Tests\Test {
	
	function setUp(){
		$this->fixture = new \Vm\Xml();
	}
	
	protected function testCreateTag(){
		$tag = $this->fixture->createTag('div', array('class'=>'testTag', 'innerHtml'=>'Testing'));
		return $this->assertEqual($tag, '<div class="testTag">Testing</div>');
	}
	
	protected function testCreateTagSelfClosing(){
		$tag = $this->fixture->createTag('input', array('type'=>'text', 'innerHtml'=>'Testing'), TRUE);
		return $this->assertEqual($tag, '<input type="text" />');
	}
	
	protected function testStartTag(){
		$tag = $this->fixture->startTag('div', array('class'=>'testTag', 'innerHtml'=>'Testing'));
		return $this->assertEqual($tag, '<div class="testTag">');
	}
	
	protected function testEndTag(){
		return $this->assertEqual($this->fixture->endTag('div'), '</div>');
	}
	
}