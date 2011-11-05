<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: A front controller abstract class
 * @requirements: PHP 5.2 or higher
 */
abstract class Vm_Controller_Front {

	protected $params = array();
	protected $view = NULL;

	protected function parseUrlParams(){
		if (strlen($_SERVER['QUERY_STRING']) > 0){
			$params = explode('&', $_SERVER['QUERY_STRING']);
			foreach ($params as $param){
				list($key, $value) = explode('=', $param);
				$this->params[urldecode($key)] = urldecode($value);
			}
		} 		
	}

	/**
	 * This method is meant to be overriden by the extending class and it should load the appropriate subcontrollers
	 */
	abstract protected function loadControllers();
	
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