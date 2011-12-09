<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A front controller for the VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Controller
 */
namespace Suite\Controller;

class Front extends \Vm\Controller\Front {

	/**
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */
	function __construct(array $settings){
		$this->parseUrlParams();
		$this->settings = $settings;	
		$this->loadControllers();
	}
	
	protected function loadControllers(){
		$controller = isset($this->params['p']) ? ucfirst($this->params['p']) : 'Index';
		$controllerFile = "Suite/Controller/$controller.php";
		$controllerClass = (file_exists($controllerFile)) ? "\\Suite\\Controller\\$controller" : '\\Suite\Controller\Error';

		$subController = new $controllerClass($this->params, $this->settings);
		$subController->setViewPath('Suite/View/Default/', $this->settings['overridePath']);
		$subController->load();
		$this->setView($subController->render());
	}
}