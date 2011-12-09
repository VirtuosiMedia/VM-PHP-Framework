<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The about page controller for the VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Controller
 * @uses \Suite\Model
 * @uses \Vm\View
 */
namespace Suite\Controller;

use \Suite\Model;
use \Vm\View;

class About extends \Vm\Controller {
	
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
		$about = new Model\About($this->params, $this->settings);
		$news = new Model\About\News($this->params, $this->settings);
		$view = new View($this->defaultPath, $this->overridePath);
				
		$view->setViewspace('Header');
		$view->pageTitle = 'About VM PHP Framework';
		$view->scripts = array(
			'Assets/JavaScript/mootools.js', 
			'Assets/JavaScript/Classes/SimpleTabs.js', 
			'Assets/JavaScript/Pages/about.js'
		);
		$view->styles = array('Assets/Themes/'.$this->settings['suiteTheme'].'/Css/default.css');
		$view->loadTemplate('Header.php');
		
		$view->setViewspace('TopNav');
		$view->map($topNav->getViewData());
		$view->loadTemplate('TopNav.php');
		
		$view->setViewspace('Body');
		$view->logoUrl = 'Assets/Themes/'.$this->settings['suiteTheme'].'/Images/medium-logo.png';
		$view->map($about->getViewData());
		$view->map($news->getViewData());
		$view->loadTemplate('About.php');
		$view->loadTemplate('Footer.php');

		$this->setView($view->render(array('Header', 'TopNav', 'Body')));
	}
}