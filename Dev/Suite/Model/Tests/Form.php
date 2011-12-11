<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the test suite form for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model\Tests
 * @uses Test\SelectionForm;
 */
namespace Suite\Model\Tests;

use \Test\SelectionForm;

class Form extends \Vm\Model {
	
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
	function __construct($params, $settings, \Test\Suite $testSuite){
		$this->params = $params;
		$this->settings = $settings;
		$this->testSuite = $testSuite;
		$this->compileData();		
	}
	
	protected function compileData(){
		$this->form = new SelectionForm($this->testSuite);
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