<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The namespaces page controller for the VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Controller\Docs
 * @uses Suite\Model
 * @uses Vm\View
 */
namespace Suite\Controller\Docs;

use \Suite\Model;
use \Vm\View;

class Namespaces extends \Vm\Controller {
	
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
		$breadcrumb = new Model\Docs\Breadcrumb($this->params, $this->settings);
		$namespaces = new Model\Docs\Namespaces($this->params, $this->settings);
		$view = new View($this->defaultPath, $this->overridePath);
				
		$view->setViewspace('Header');
		$view->pageTitle = 'Namespace - '.strip_tags($this->params['n']);
		$view->scripts = array(
			'Assets/JavaScript/Classes/SimpleTabs.js',
			'Assets/JavaScript/Pages/docs.js'
		);
		$view->styles = array('Assets/Themes/'.$this->settings['suiteTheme'].'/Css/default.css');
		$view->loadTemplate('Header.php');
		
		$view->setViewspace('TopNav');
		$view->map($topNav->getViewData());
		$view->loadTemplate('TopNav.php');
		
		$view->setViewspace('Body');
		$view->removeFilters(array('StripTags'));
		$view->map($breadcrumb->getViewData());
		$view->map($namespaces->getViewData());
		$view->loadTemplate('Docs/Namespaces.php');
		$view->loadTemplate('Footer.php');
		
		$this->setView($view->render(array('Header', 'TopNav', 'Body')));
	}
}