<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the install form for VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 * @namespace Suite\Model\Install
 * @uses Tools\Installer
 * @uses Vm\Folder
 * @uses Vm\Form
 */
namespace Suite\Model\Install;

class Sidebar extends \Vm\Model {
	
	protected $params;
	
	function __construct($params){
		$this->params = $params;
		$this->compileData();		
	}
	
	protected function compileData(){
		$environmentClass = NULL;
		$databaseClass = NULL;
		$adminClass = NULL;
		$appClass = NULL;
		
		$page = (isset($this->params['p'])) ? $this->params['p'] : NULL;
		
		switch ($page){
			case 'install-database':
				$databaseClass = 'active';
				break;
			case 'install-admin-user':
				$adminClass = 'active';
				break;
			case 'install-app-data':
				$appClass = 'active';
				break;
			default:
				$environmentClass = 'active';
				break;								
		}
		
		$this->setData('environmentClass', $environmentClass);
		$this->setData('databaseClass', $databaseClass);
		$this->setData('adminClass', $adminClass);
		$this->setData('appClass', $appClass);
	}	
}