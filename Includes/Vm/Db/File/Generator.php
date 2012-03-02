<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Generates a database file
 * @extends Vm\Db\Factory\Ddl
 * @uses Vm\Db\Exception
 * @namespace Vm\Db\File
 */
namespace Vm\Db\File;

class Generator extends \Vm\Db\Factory\Ddl {
	
	/**
	 * @description Generates a database class file for the given table
	 * @param string $dbType - The type of database
	 * @param string $tableName - The name of the table
	 * @param string $dir - The relative directory path, omitting the trailing forward slash '/'
	 */
	public function generate($dbType, $tableName, $dir){
		$tableSingular = rtrim($tableName, 's');
		$newtableName = str_replace('_', ' ', $tableName);
		$newtableName = str_replace(' ', '', ucwords($newtableName));
		$columns = $this->showColumns($tableName);
		$fields = array();
		foreach ($columns as $column){
			$fields[] = $column['Field'];
		}
		$fields = "'".implode("', '", $fields)."'";
		
		$fileName = $dir.'/'.$newtableName.'.php';
		$objectName = $newtableName;

		switch(strtolower($dbType)){
			case 'mysql':
				$dbType = 'MySQL';
				$extension = 'Vm\Db\MySql\Dml';
				break;
			default:
				throw new \Vm\Db\Exception('Database driver type "'.$driverType.'" is not supported.');
		}
		
		$file = <<<EOT
<?php
/**
 * @description The database class for the $tableName table. Uses a $dbType database. This class has been auto-generated.
 * @extends Vm\Db\Factory\Dml
 * @extends $extension
 * @namespace Db
 */
namespace Db;
 
class $objectName extends \Vm\Db\Factory\Dml {
	
	/**
	 * @param object \$db - The PDO connection
	 * @param string \$prefix - optional - The prefix for the database 
	 * @return Returns the current object for chaining purposes
	 */
	function __construct(\PDO \$db, \$prefix = NULL) {
		parent::__construct(\$db, '$dbType', array('$tableName'=>'$tableSingular'), array($fields), 'public', \$prefix);
  	}
}
EOT;
		$fileHandler = fopen($fileName, 'w');
		fwrite($fileHandler, $file);
		fclose($fileHandler);
	}
	
	/**
	 * @description Generates a database class file for all tables in the database
	 * @param string $dbType - The type of database
	 * @param string $dir - The relative directory path, omitting the trailing forward slash '/'
	 */
	public function generateAll($dbType, $dir){
		$tables = $this->showTables();
		foreach ($tables as $tableName){
			$this->generate($dbType, $tableName[0], $dir);	
		}		
	}
}