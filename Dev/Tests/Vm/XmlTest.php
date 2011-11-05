<?php 
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_Xml class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_XmlTest extends Tests_Test {
	
	function setUp(){
		$this->fixture = new Vm_Xml();
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
?>