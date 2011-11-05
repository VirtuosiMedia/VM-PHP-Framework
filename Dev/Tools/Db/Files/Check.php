<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Checks for database files
* Requirements: PHP 5.2 or higher
*/
class Tools_Db_Files_Check {
	
	protected $db;
	protected $dbType;
	protected $filesClass;
	protected $filesMessage;
	protected $numTables;
	protected $status;
	
	function __construct(){
		$this->connect();
		if ($this->status){
			$this->getTables();
		} else {
			$this->numTables = 0;
		}
		$this->getFiles();	
	}
	
	protected function connect(){
		$version = new Version();
		$configClass = $version->get('package').'_Config';
		$config = new $configClass();
		
		try {
			switch($config->dbType) {
				case 'mysql':
					$this->db = new PDO('mysql:host='.$config->host.';dbname='.$config->dbName, $config->username, $config->password);
					$this->dbType = 'MySql';
					$this->status = TRUE;
					break;
				default:
					$this->status = FALSE;
					break;
			}		
		} catch (PDOException $e) {
			$this->status = FALSE;
		}		
	}
	
	protected function getTables(){
		require_once('includes/Vm/Db/'.$this->dbType.'/DbOperations.php');
		$dbOp = new DbOperations($this->db);
		$this->numTables = sizeof($dbOp->showTables()); 
	}
	
	protected function getFiles(){
		$folder = new Vm_Folder('includes/Db');
		$files = $folder->getFiles();
		$numFiles = sizeof($files);
		
		if (($numFiles == 0)||($this->numTables == 0)){
			$this->filesClass = 'fail';
			$this->filesMessage = 'Database files have not yet been generated';
		} else if ($numFiles != $this->numTables){
			$this->filesClass = 'warning';
			$this->filesMessage = 'Database files need to be regenerated';			
		} else {
			$this->filesClass = 'pass';
			$this->filesMessage = 'Database files have been generated';			
		}
	}
	
	public function getFilesClass(){
		return $this->filesClass;
	}
	
	public function getFilesMessage(){
		return $this->filesMessage;
	}	
}
?>