<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The docs file page controller for the VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Controller\Docs
 * @uses Suite\Model
 * @uses Vm\View
 */
namespace Suite\Controller\Docs;

use \Suite\Model;
use \Vm\View;

class File extends \Vm\Controller {
	
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
		$tabs = new Model\Docs\Tabs($this->params, $this->settings);
		$tutorial = new Model\Docs\Tutorial($this->params, $this->settings);
		$api = new Model\Docs\Api($this->params, $this->settings);
		$code = new Model\Docs\Code($this->params, $this->settings);
		$fileName = str_replace('|', '-', str_replace('-', ' ', str_replace('---', '-|-', $this->params['f'])));
		
		$view = new View($this->defaultPath, $this->overridePath);
				
		$view->setViewspace('Header');
		$view->pageTitle = 'Docs - '.$fileName;
		$view->scripts = array(
			'Assets/JavaScript/Classes/Lighter/mootools-1.2.4.js', //This older version is because lighter.js hasn't been updated yet 
			'Assets/JavaScript/Classes/Lighter/mootools-more-1.2.5.js',
			'Assets/JavaScript/Classes/SimpleTabs.js',
			'Assets/JavaScript/Classes/Lighter/Fuel.js',
			'Assets/JavaScript/Classes/Lighter/Lighter.js', 
			'Assets/JavaScript/Pages/docs.js'
		);
		$view->styles = array('Assets/Themes/'.$this->settings['suiteTheme'].'/Css/default.css');
		$view->loadTemplate('Header.php');
		
		$view->setViewspace('TopNav');
		$view->map($topNav->getViewData());
		$view->loadTemplate('TopNav.php');
		
		$view->setViewspace('Body');
		$view->fileName = $fileName;
		$view->map($tabs->getViewData());
		$view->map($code->getViewData());
		$view->removeFilters(array('StripTags'));
		$view->map($api->getViewData());
		$view->map($tutorial->getViewData());
		$view->loadTemplate('Docs/File.php');
		$view->loadTemplate('Footer.php');
		
		$this->setView($view->render(array('Header', 'TopNav', 'Body')));
	}
}