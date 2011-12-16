<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The tools page controller for the VM PHP Framework Suite
 * @namespace Suite\Controller
 * @uses \Suite\Model
 * @uses \Vm\View
 */
namespace Suite\Controller;

use \Suite\Model;
use \Vm\View;

class Tools extends \Vm\Controller {
	
	protected $params;
	protected $settings;
	
	/**
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's value 
	 * 		as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */
	function __construct(array $params, array $settings){
		$this->params = $params;
		$this->settings = $settings;
	}
	
	public function load(){
		$topNav = new Model\TopNav($this->params, $this->settings);
		//$tools = new Model\Tools($this->params, $this->settings);
		$view = new View($this->defaultPath, $this->overridePath);
				
		$view->setViewspace('Header');
		$view->pageTitle = 'VM PHP Framework Tools';
		$view->scripts = array(
			'Assets/JavaScript/mootools.js', 
			'Assets/JavaScript/Classes/SimpleTabs.js',
			'Assets/JavaScript/Pages/tools.js'	
		);
		$view->styles = array('Assets/Themes/'.$this->settings['suiteTheme'].'/Css/default.css');
		$view->loadTemplate('Header.php');
		
		$view->setViewspace('TopNav');
		$view->map($topNav->getViewData());
		$view->loadTemplate('TopNav.php');

		$view->setViewspace('Body');
		
		$version = new \Vm\Version();
		$view->version = $version->get('version');
		$view->copyright = $version->get('copyright');

		//$view->map($tools->getViewData());
		$view->loadTemplate('Tools.php');
		$view->loadTemplate('Footer.php');

		$this->setView($view->render(array('Header', 'TopNav', 'Body')));
	}
}