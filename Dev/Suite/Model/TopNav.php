<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the top nav menu for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model
 */
namespace Suite\Model;

class TopNav extends \Vm\Model {
	
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
		$this->compileMenu();		
	}
	
	protected function compileMenu(){
		$menu = array();
		$activePage = isset($this->params['p']) ? ucfirst($this->params['p']) : 'Index';
		$menu[] = array('page'=>'', 'href'=>' href="index.php"', 'id'=>' id="logo"', 'class'=>NULL);
		
		if ($this->settings['installed']){
			$menu[] = array(
				'page'=>$this->settings['appShortName'], 
				'href'=>' href="../index.php"', 
				'id'=>NULL, 'class'=>NULL
			);
		}
		
		$aboutClass = ($activePage == 'About') ? ' class="active"' : NULL;
		$menu[] = array('page'=>'About', 'href'=>' href="index.php?p=about"', 'id'=>NULL, 'class'=>$aboutClass);
		
		$docsClass = ($activePage == 'Docs') ? ' class="active"' : NULL;
		$menu[] = array('page'=>'Docs', 'href'=>' href="index.php?p=docs"', 'id'=>NULL, 'class'=>$docsClass);

		if (!$this->settings['installed']){
			$installClass = ($activePage == 'Install') ? ' class="active"' : NULL;
			$menu[] = array(
				'page'=>'Install', 
				'href'=>' href="index.php?p=install"', 
				'id'=>NULL, 
				'class'=>$installClass
			);			
		}
		
		$securityClass = ($activePage == 'Security') ? ' class="active"' : NULL;
		$menu[] = array(
			'page'=>'Security', 
			'href'=>' href="index.php?p=security"', 
			'id'=>NULL, 
			'class'=>$securityClass
		);

		$testsClass = ($activePage == 'Tests') ? ' class="active"' : NULL;
		$menu[] = array('page'=>'Tests', 'href'=>' href="index.php?p=tests"', 'id'=>NULL, 'class'=>$testsClass);

		$toolsClass = ($activePage == 'Tools') ? ' class="active"' : NULL;
		$menu[] = array('page'=>'Tools', 'href'=>' href="index.php?p=tools"', 'id'=>NULL, 'class'=>$toolsClass);

		$this->setData('menu', $menu);
	}
}