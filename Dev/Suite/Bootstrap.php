<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The bootsrap class for VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 * @namespace Suite
 * @uses Vm\Db\Connect
 */
namespace Suite;

use \Vm\Db\Connect;

class Bootstrap {
	
	protected $config;
	protected $db;
	protected $dbType;
	protected $path;
	protected $siteSettings;
	
	/**
	 * @description Bootstraps the autoload function, loads suite settings, and connects to the database.
	 */
	function __construct(){
		$this->loadConfigFile();
		$this->connectDb();
		$this->loadSiteSettings();
		$this->iniateSession();
	}

	/**
	 * @description Loads the config file if it exists, else redirects to the install file
	 */
	protected function loadConfigFile(){
		try {
			$this->config = new \Suite\Config();
		} catch (Exception $e) {
			Vm_Url::redirect('install.php');
		}		
	}

	/**
	 * @decription Gets the suite config settings
	 * @return obj - The Suite\Config object
	 */
	public function getConfig(){
		return $this->config;
	}	
	
	/**
	 * @description Establishes a database connection based on the config file
	 * @return unknown_type
	 */
	protected function connectDb(){
		$c = $this->config;
		$db = new Cielo_Connect($c->dbType, $c->dbName, $c->username, $c->password, $c->host, $c->port, $c->charset);
		$this->db = $db->getDbConnection();
		$this->dbType = $c->dbType;
		require_once($this->path.'includes/Vm/Db/'.$c->dbType.'/DbObject.php');
	}

	/**
	 * @description Gets the database connection
	 * @return obj - A PDO object
	 */
	public function getDbConnection(){
		return $this->db;
	}
	
	/**
	 * @description Gets the database type
	 * @return string - The type (brand) of database being used
	 */
	public function getDbType(){
		return $this->dbType;
	}
}