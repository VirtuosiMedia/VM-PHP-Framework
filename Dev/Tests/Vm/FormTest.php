<?php
/**
 * @author: Virtuosi Media Inc.
 * @license: MIT License
 * @group: VM PHP Framework
 * @subgroup: Form
 * @description: Tests the Vm\Form class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm
 */
namespace Tests\Vm;

use Vm\Form;

class FormTest extends \Tests\Test {

	protected function testFormNoAddedElements(){
		$form = new Form(array('action'=>'test.php'));
		$formOutput = <<<TESTOUTPUT
<form method="post" action="test.php"><input value="TRUE" type="hidden" id="" name="submitted" />\n</form>\n
TESTOUTPUT;
		return $this->assertEqual(htmlspecialchars($form->render()), htmlspecialchars($formOutput));
	}
	
	protected function testTextInputWithLabel(){
		$form = new Form(array('action'=>'test.php'));
		$form->text('testText', array(
			'label'=>array(
				'innerHtml'=>'Test Text'
		)));
		$formOutput = '<form method="post" action="test.php">'.
			"<label for=\"testText\">Test Text</label>\n".
			"<input type=\"text\" id=\"testText\" name=\"testText\" value=\"\" />\n".
			"<input value=\"TRUE\" type=\"hidden\" id=\"\" name=\"submitted\" />\n".
			"</form>\n";
		return $this->assertEqual(htmlspecialchars(htmlspecialchars($form->render())), htmlspecialchars(htmlspecialchars($formOutput)));
	}	
}