<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The error page controller for the VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 */
class Suite_Controller_Error extends Vm_Controller {
	
	protected $params;
	protected $settings;
	
	/**
	 * 
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's value as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */
	function __construct(array $params, array $settings){
		$this->params = $params;
		$this->settings = $settings;
	}
	
	public function load(){
		$topNav = new Suite_Model_TopNav($this->params, $this->settings);
		$view = new Vm_View($this->defaultPath, $this->overridePath);
				
		$view->setViewspace('Header');
		$view->pageTitle = 'VM PHP Framework for PHP5';
		$view->scripts = array();
		$view->styles = array('Assets/Themes/'.$this->settings['suiteTheme'].'/Css/default.css');
		$view->loadTemplate('Header.php');
		
		$view->setViewspace('TopNav');
		$view->map($topNav->getViewData());
		$view->loadTemplate('TopNav.php');
		
		$view->setViewspace('Body');
		
		$version = new Vm_Version();
		$view->version = $version->get('version');
		$view->copyright = $version->get('copyright');		
		
		$view->loadTemplate('Error.php');
		$view->loadTemplate('Footer.php');

		$this->setView($view->render(array('Header', 'TopNav', 'Body')));
	}
}