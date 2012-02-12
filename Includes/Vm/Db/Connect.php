<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Checks for and retrieves a database connection
 * @requirements PHP 5.2 or higher
 * @namespace Vm\Db
 */
namespace Vm\Db;

class Connect {
	
	protected $db;
	protected $dbType;
	protected $dbName;
	protected $dbUsername;
	protected $dbPassword;
	protected $dbHost;
	protected $error;
	protected $status;
	
	/**
	 * @param string $dbType - The type of database: 'mysql', 'oracle', etc.
	 * @param string $dbName - The name of the database
	 * @param string $dbUsername - The username of the database
	 * @param string $dbPassword - The password for the database
	 * @param string $dbHost - The database host
	 */
	function __construct($dbType, $dbName, $dbUsername, $dbPassword, $dbHost){
		$this->dbType = strtolower($dbType);
		$this->dbName = $dbName;
		$this->dbUsername = $dbUsername;
		$this->dbPassword = $dbPassword;
		$this->dbHost = $dbHost;
		$this->checkConnection();
	}
		
	protected function checkConnection(){
		if (($this->dbType) && ($this->dbName || $this->dbUsername || $this->dbPassword || $this->dbHost)){
			try {
				switch($this->dbType) {
					case 'mysql':
						$this->db = new \PDO(
							'mysql:host='.$this->dbHost.';dbname='.$this->dbName, 
							$this->dbUsername, 
							$this->dbPassword
						);
						break;
				}
				$this->status = TRUE;
				$this->error = NULL;
			} catch (\PDOException $e) {
				$this->status = FALSE;
				$this->error = "Sorry, a database connection could not be established. Please check your access 
					information and try again.";
			}
		} else {
			$this->status = FALSE;
			$this->error = 'A database connection could not be established because not enough information was provided 
				to connect.';
		}
	}

	/**
	 * @description Indicates whether or not a database connection was made.
	 * @return boolean - TRUE if the DB connection is made, FALSE otherwise
	 */
	public function isConnected(){
		return $this->status;
	}
	
	/**
	 * @description Gets the error message returned by PDO if the connection fails.
	 * @return mixed - An error message if the DB connection fails, NULL if it is successful
	 */
	public function getError(){
		return $this->error;
	}
	
	/**
	 * @descsription Gets the database connection if it is valid
	 * @return \Vm\Db\PDO
	 */
	public function getDb(){
		return $this->db;
	}
}