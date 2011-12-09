<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the API Docs for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model\Docs
 * @uses Vm\File;
 * @uses Vm\Version;
 */
namespace Suite\Model\Docs;

use \Vm\File;
use \Vm\Version;

class Code extends \Vm\Model {
	
	protected $params;
	protected $settings;
	
	/**
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's 
	 * 		value as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */	
	function __construct($params, $settings){
		$this->params = $params;
		$this->settings = $settings;
		$this->compileData();		
	}
	
	protected function compileData(){
		$fileParts = explode('\\', str_replace('/', '', str_replace('.', '', $this->params['f'])));
		$fileName = array_pop($fileParts).'.php';
		$filePath = '../Includes/'.implode('/', $fileParts);
		
		$file = new File($fileName, $filePath);
		$code = ($file->exists()) ? htmlentities($file->read()) : FALSE;
		$this->setData('code', $code);
		
		$version = new Version();
		$this->setData('version', $version->get('version'));
		$this->setData('copyright', $version->get('copyright'));		
	}
}