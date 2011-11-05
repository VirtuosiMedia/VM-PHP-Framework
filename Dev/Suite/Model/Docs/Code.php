<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the API Docs for VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 */
class Suite_Model_Docs_Code extends Vm_Model {
	
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
		$fileParts = explode('_', str_replace('/', '', str_replace('.', '', $this->params['f'])));
		$fileName = array_pop($fileParts).'.php';
		$filePath = '../Includes/'.implode('/', $fileParts);
		
		$file = new Vm_File($fileName, $filePath);
		$code = ($file->exists()) ? htmlentities($file->read()) : FALSE;
		$this->setData('code', $code);
		
		$version = new Vm_Version();
		$this->setData('version', $version->get('version'));
		$this->setData('copyright', $version->get('copyright'));		
	}
}