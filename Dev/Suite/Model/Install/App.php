<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the admin user install form for VM PHP Framework Suite
 * @requirements: PHP 5.3 or higher
 * @namespace Suite\Model\Install
 * @uses Vm\Db\Connect
 * @uses Vm\Form
 */
namespace Suite\Model\Install;

use \Vm\Db\Connect;
use \Vm\Form;

class App extends \Vm\Model {
	
	protected $db;
	protected $form;
	
	function __construct(){
		$this->compileData();		
	}
	
	protected function compileData(){
		$this->createForm();
		$this->processForm();
	}
	
	protected function createForm(){
		$config = new \Suite\Config();
		$this->connectDb($config);
		
		$user = new \Ar\Users($this->db);
		
		$this->setData('user', $user);
	}
	
	protected function processForm(){

	}
	
	protected function connectDb($config){
		$connect = new Connect(
			$config->dbType, 
			$config->dbName, 
			$config->dbUsername, 
			$config->dbPassword, 
			$config->dbHost
		);
		$this->db = $connect->getDb();	
	}
	
}