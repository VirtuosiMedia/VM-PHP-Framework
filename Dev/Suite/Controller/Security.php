<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The security page controller for the VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 */
class Suite_Controller_Security extends Vm_Controller {
	
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
		$settings = new Suite_Model_Security_Settings($this->params, $this->settings);
		$permissions = new Suite_Model_Security_Permissions($this->params, $this->settings);
		$view = new Vm_View($this->defaultPath, $this->overridePath);
				
		$view->setViewspace('Header');
		$view->pageTitle = 'VM PHP Framework Security';
		$view->scripts = array('Assets/JavaScript/mootools.js', 'Assets/JavaScript/Classes/SimpleTabs.js', 'Assets/JavaScript/Pages/security.js');
		$view->styles = array('Assets/Themes/'.$this->settings['suiteTheme'].'/Css/default.css');
		$view->loadTemplate('Header.php');
		
		$view->setViewspace('TopNav');
		$view->map($topNav->getViewData());
		$view->loadTemplate('TopNav.php');
		
		$view->setViewspace('Body');
		
		$version = new Vm_Version();
		$view->version = $version->get('version');
		$view->copyright = $version->get('copyright');			
		
		$view->map($settings->getViewData());
		$view->map($permissions->getViewData());
		$view->loadTemplate('Security.php');
		$view->loadTemplate('Footer.php');

		$this->setView($view->render(array('Header', 'TopNav', 'Body')));
	}
}