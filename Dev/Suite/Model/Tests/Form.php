<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the test suite form for VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 */
class Suite_Model_Tests_Form extends Vm_Model {
	
	protected $params;
	protected $settings;
	
	/**
	 * 
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's value as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */	
	function __construct($params, $settings){
		$this->params = $params;
		$this->settings = $settings;
		$this->compileData();		
	}
	
	protected function compileData(){
		$testSuite = new Test_Render_Suite();
		$this->setData('testSuite', $testSuite->render());
	}
}