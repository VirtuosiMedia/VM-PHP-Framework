<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description This factory class acts as a wrapper for a specific database driver.
 * @extends Vm\Db\Factory\Ddl
 * @uses Vm\Db\MySql\Ddl
 * @uses Vm\Db\MySql\ToXml
 * @namespace Vm\Db\Factory
 */
namespace Vm\Db\Factory;

class ToXml extends Ddl {

	protected $driver;
	protected $driverType;
		
	/**
	 * @param object $db - The PDO database connection object
	 * @param $driverType - The type of database driver: ie, mysql
	 * @param string $prefix - optional - A prefix to add to a created table
	 * @return The object for chaining 
	 */
	function __construct(\PDO $db, $driverType, $prefix = NULL) {
		$driverType = strtolower($driverType);
		switch ($driverType){
			case 'mysql':
				$driverName = '\Vm\Db\MySql\ToXml';
				break;
			default:
				throw new \Vm\Db\Exception('Database driver type "'.$driverType.'" is not supported.');
		}
		
		$this->driver = new $driverName($db, $prefix);
		return $this;
	}
	
	/**
	 * @description Creates an XML file representing the given table's structure
	 * @param mixed $tableNames - The names of the tables as a string or an array of strings
	 * @param string $fileName - optional - The name of the new file, complete with the relative path and with extension
	 * @param string $mode - optional - 'structure', 'data', or 'both', defaults to 'both'
	 * @return mixed - The xml file as a string only if $fileName is NULL, otherwise returns boolean on file creation
	 */
	public function render($tableNames, $fileName = NULL, $mode = 'both'){
		return $this->driver->render($tableNames, $fileName, $mode);
	}		
}