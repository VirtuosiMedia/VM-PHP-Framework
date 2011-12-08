<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the test suite form for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 */
class Suite_Model_Tests_Form extends Vm_Model {
	
	protected $form;
	protected $params;
	protected $settings;
	protected $testSuite;
	
	/**
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's value 
	 * 		as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 * @param Test_Suite $testSuite - The Test_Suite object
	 */
	function __construct($params, $settings, Test_Suite $testSuite){
		$this->params = $params;
		$this->settings = $settings;
		$this->testSuite = $testSuite;
		$this->compileData();		
	}
	
	protected function compileData(){
		$this->form = new Test_SelectionForm($this->testSuite);
		$this->setData('testForm', $this->form->render());
	}

	/**
	 * @decription Gets the test suite form object.
	 * @return Returns the test suite form object.
	 */
	public function getForm(){
		return $this->form;
	}
}