<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the docs page breadcrumb for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model\Docs
 */
namespace Suite\Model\Docs;

class Breadcrumb extends \Vm\Model {
	
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
		if ((isset($this->params['t'])) && (isset($this->params['a']))){
			$this->tutorialBreadcrumb();
		} else if (isset($this->params['f'])){
			$this->fileBreadcrumb();
		} else if (isset($this->params['n'])){
			$this->namespaceBreadcrumb();			
		} else {
			$this->setData('breadcrumbExists', FALSE);
		}
	}
	
	/**
	 * @description Creates the breadcrumb links for a tutorial page
	 */
	protected function tutorialBreadcrumb(){
		$appNameHash = strtolower(strip_tags($this->params['a']));
		$appName = ucfirst($appNameHash);
		
		$links = array(
			$appName=>htmlspecialchars('index.php?p=docs#'.$appNameHash)		
		);
		
		$title = str_replace('|', '-', str_replace('-', ' ', str_replace('---', '-|-', $this->params['t'])));
		
		$this->setData('breadcrumbExists', TRUE);
		$this->setData('breadcrumbLinks', $links);
		$this->setData('breadcrumbTitle', strip_tags($title));
	}
	
	/**
	 * @description Creates the breadcrumb links for an API docs page
	 */
	protected function fileBreadcrumb(){
		$pieces = explode('\\', strip_tags($this->params['f']));
		$className = array_pop($pieces);
		$links = array();
		$compiledNamespace = '';

		foreach ($pieces as $namespace){
			$compiledNamespace .= '\\'.$namespace;
			$compiledNamespace = ltrim($compiledNamespace, '\\');
			$links[$namespace] = htmlspecialchars('index.php?p=docs&n='.$compiledNamespace);
		}
	
		$this->setData('breadcrumbExists', TRUE);
		$this->setData('breadcrumbLinks', $links);
		$this->setData('breadcrumbTitle', $className);
	}

	/**
	 * @description Creates the breadcrumb links for a namespace docs page
	 */
	protected function namespaceBreadcrumb(){
		$pieces = explode('\\', strip_tags($this->params['n']));
		$namespaceName = array_pop($pieces);
		$links = array();
		$compiledNamespace = '';
	
		foreach ($pieces as $namespace){
			$compiledNamespace .= '\\'.$namespace;
			$compiledNamespace = ltrim($compiledNamespace, '\\');
			$links[$namespace] = htmlspecialchars('index.php?p=docs&n='.$compiledNamespace);
		}
	
		$this->setData('breadcrumbExists', TRUE);
		$this->setData('breadcrumbLinks', $links);
		$this->setData('breadcrumbTitle', $namespaceName);
	}	
}