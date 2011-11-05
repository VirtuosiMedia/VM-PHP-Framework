<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: A front controller for the VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 */
class Suite_Controller_Front extends Vm_Controller_Front {

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
		$controllerClass = (file_exists($controllerFile)) ? "Suite_Controller_$controller" : 'Suite_Controller_Error';

		$subController = new $controllerClass($this->params, $this->settings);
		$subController->setViewPath('Suite/View/Default/', $this->settings['overridePath']);
		$subController->load();
		$this->setView($subController->render());
	}
}