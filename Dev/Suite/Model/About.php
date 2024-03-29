<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the about page variables for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model
 * @uses Vm\Version
 */
namespace Suite\Model;

use Vm\Version;

class About extends \Vm\Model {
	
	protected $params;
	protected $settings;
	
	/**
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's value 
	 * 		as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */	
	function __construct($params, $settings){
		$this->params = $params;
		$this->settings = $settings;
		$this->compileData();		
	}
	
	protected function compileData(){
		$version = new Version();
		$this->setData('version', $version->get('version'));
		$this->setData('copyright', $version->get('copyright'));
	}
}