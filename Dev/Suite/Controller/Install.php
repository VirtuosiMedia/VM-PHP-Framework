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
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */
	function __construct(array $settings){
		$this->params = $_GET;
		$this->settings = $settings;
	}
	
	public function load(){
		$sidebar = new Model\Install\Sidebar($this->params);
		$view = new View($this->defaultPath, $this->overridePath);
		
		$view->setViewspace('Header');
		$view->pageTitle = 'Install VM PHP Framework';
		$view->scripts = array(
			'Assets/JavaScript/mootools.js', 
			'Assets/JavaScript/Classes/InlineModal.js',
			'Assets/JavaScript/Classes/Notification.js',
			'Assets/JavaScript/Classes/Select.js',				
			'Assets/JavaScript/Pages/install.js'
		);
		$view->styles = array('Assets/Themes/'.$this->settings['suiteTheme'].'/Css/default.css');
		$view->loadTemplate('Header.php');
		
		$view->setViewspace('Body');
		
		$version = new Version();
		$view->version = $version->get('version');
		$view->copyright = $version->get('copyright');			
		
		$page = (isset($this->params['p'])) ? $this->params['p'] : NULL;
		
		switch ($page){
			case 'install-database':
				$install = new Model\Install\Database();
				$view->removeFilters(array('StripTags'));
				$view->map($sidebar->getViewData());
				$view->map($install->getViewData());
				$view->loadTemplate('Install/Database.php');
				break;
			case 'install-admin-user':
				$install = new Model\Install\Admin();
				$view->removeFilters(array('StripTags'));
				$view->map($sidebar->getViewData());
				$view->map($install->getViewData());
				$view->loadTemplate('Install/Admin.php');
				break;
			case 'install-app-data':
				$install = new Model\Install\App();
				$view->removeFilters(array('StripTags'));
				$view->map($sidebar->getViewData());
				$view->map($install->getViewData());
				$view->loadTemplate('Install/App.php');
				break;
			default:
				$environment = new Model\Install\Environment();
				$view->map($environment->getViewData());
				$view->map($sidebar->getViewData());
				$view->loadTemplate('Install/Environment.php');
		}
			
		$view->loadTemplate('Footer.php');

		$this->setView($view->render(array('Header', 'TopNav', 'Body')));
	}
}