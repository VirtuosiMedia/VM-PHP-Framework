<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the docs page variables for VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 */
class Suite_Model_Docs_Tabs extends Vm_Model {
	
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
		$tutorialPath = 'Docs/Tutorials/'.implode('/', $fileParts).'.php';
		$filePath = '../Includes/'.implode('/', $fileParts).'.php';
			
		$tabs = array();
		if (file_exists($tutorialPath)){
			$tabs[] = array('class'=>'tab', 'hash'=>'#tutorial', 'name'=>'Tutorial');
		}
		
		if (file_exists($filePath)){
			$tabs[] = array('class'=>'tab', 'hash'=>'#api', 'name'=>'API');
			$tabs[] = array('class'=>'tab', 'hash'=>'#source', 'name'=>'Source');
		}		
		$tabs[0]['class'] = 'firstTab tab active';		
		
		$this->setData('tabs', $tabs);
	}
}