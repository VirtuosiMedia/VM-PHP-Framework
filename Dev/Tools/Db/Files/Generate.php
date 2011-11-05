<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Generates database files
* Requirements: PHP 5.2 or higher
*/
class Tools_Db_Files_Generate {
	
	protected $db;
	protected $dbType;
	protected $filesClass;
	protected $filesMessage;
	protected $numTables;
	protected $status;
	
	function __construct(){
		$this->connect();
		if ($this->status){
			$this->generateFiles();
		} 	
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
	
	protected function generateFiles(){
		require_once('includes/Vm/Db/MySQL/DbOperations.php');
		
		$folder = new Vm_Folder('includes/Db');
		$folder->emptyDir('includes/Db');
		
		$files = new Vm_Db_File_Generator($this->db);
		$files->generateAll('includes/Db');	
	}
}
?>