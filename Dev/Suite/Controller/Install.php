<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The install page controller for the VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Controller
 * @uses \Suite\Model
 * @uses \Vm\Version
 * @uses \Vm\View
 */
namespace Suite\Controller;

use \Suite\Model;
use \Vm\Version;
use \Vm\View;

class Install extends \Vm\Controller {
	
	protected $params;
	protected $settings;
	
	/**
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's 
	 * 		value as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */
	function __construct(array $params, array $settings){
		$this->params = $params;
		$this->settings = $settings;
	}
	
	public function load(){
		$topNav = new Model\TopNav($this->params, $this->settings);
		$environment = new Model\Install\Environment();
		$install = new Model\Install\Install();
		$view = new View($this->defaultPath, $this->overridePath);
		
		$view->setViewspace('Header');
		$view->pageTitle = 'Install VM PHP Framework';
		$view->scripts = array(
			'Assets/JavaScript/mootools.js', 
			'Assets/JavaScript/Classes/SimpleTabs.js', 
			'Assets/JavaScript/Pages/install.js'
		);
		$view->styles = array('Assets/Themes/'.$this->settings['suiteTheme'].'/Css/default.css');
		$view->loadTemplate('Header.php');
		
		$view->setViewspace('TopNav');
		$view->map($topNav->getViewData());
		$view->loadTemplate('TopNav.php');
		
		$view->setViewspace('Body');
		
		$version = new Version();
		$view->version = $version->get('version');
		$view->copyright = $version->get('copyright');			
		
		$view->map($environment->getViewData());
		$view->removeFilters(array('StripTags'));
		$view->map($install->getViewData());
		$view->loadTemplate('Install.php');
		$view->loadTemplate('Footer.php');

		$this->setView($view->render(array('Header', 'TopNav', 'Body')));
	}
}