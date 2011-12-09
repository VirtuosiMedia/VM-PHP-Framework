<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A sub controller abstract class
 * @requirements PHP 5.2 or higher
 * @namespace Vm
 */
namespace Vm;

abstract class Controller {

	protected $params = array();
	protected $view = NULL;

	/**
	 * @description This method is meant to be overriden by the extending class and it should load the appropriate 
	 * 		models and views
	 */
	abstract public function load();
	
	/**
	 * @param string $defaultPath - 
	 * @param string $overridePath - optional - 
	 */
	public function setViewPath($defaultPath, $overridePath = NULL){
		$this->defaultPath = $defaultPath;
		$this->overridePath = $overridePath;
	}
	
	/**
	 * @param string $view - The final and complete view to be rendered as the page
	 */
	protected function setView($view){
		$this->view = $view;
	}
	
	/**
	 * @return string - The final and complete view to be rendered as the page
	 */
	public function render(){
		return $this->view;
	}
}