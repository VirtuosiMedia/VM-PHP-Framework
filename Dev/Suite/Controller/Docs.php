<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The docs page controller for the VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @uses \Suite\Model
 * @uses \Vm\View
 */
namespace Suite\Controller;

use \Suite\Model;
use \Vm\View;

class Docs extends \Vm\Controller {
	
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
		if ((isset($this->params['t']))&&(isset($this->params['a']))){
			$subController = new Docs\Tutorial($this->params, $this->settings);
			$subController->setViewPath('Suite/View/Default/', $this->settings['overridePath']);
			$subController->load();
			$this->setView($subController->render());
		} else if (isset($this->params['f'])){
			$subController = new Docs\File($this->params, $this->settings);
			$subController->setViewPath('Suite/View/Default/', $this->settings['overridePath']);
			$subController->load();
			$this->setView($subController->render());
		} else if (isset($this->params['n'])){
			$subController = new Docs\Namespaces($this->params, $this->settings);
			$subController->setViewPath('Suite/View/Default/', $this->settings['overridePath']);
			$subController->load();
			$this->setView($subController->render());					
		} else {
			$topNav = new Model\TopNav($this->params, $this->settings);
			$main = new Model\Docs($this->params, $this->settings);
			$view = new View($this->defaultPath, $this->overridePath);
					
			$view->setViewspace('Header');
			$view->pageTitle = 'Docs for VM PHP Framework';
			$view->scripts = array(
				'Assets/JavaScript/mootools.js',
				'Assets/JavaScript/mootools-more.js',
				'Assets/JavaScript/Classes/SimpleTabs.js', 
				'Assets/JavaScript/Pages/docs.js'
			);
			$view->styles = array('Assets/Themes/'.$this->settings['suiteTheme'].'/Css/default.css');
			$view->loadTemplate('Header.php');
			
			$view->setViewspace('TopNav');
			$view->map($topNav->getViewData());
			$view->loadTemplate('TopNav.php');
			
			$view->setViewspace('Body');
			$view->map($main->getViewData());
			$view->loadTemplate('Docs.php');
			$view->loadTemplate('Footer.php');
			
			$this->setView($view->render(array('Header', 'TopNav', 'Body')));
		}
	}
}