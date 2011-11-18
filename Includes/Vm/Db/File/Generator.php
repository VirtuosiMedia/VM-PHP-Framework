<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Generates a database file
 * @requires PHP 5.2 or higher
 * @extends Vm_Db_Ddl
 * @uses Vm_Db_Exception
 */
class Vm_Db_File_Generator extends Vm_Db_Ddl {
	
	/**
	 * @description Generates a database class file for the given table
	 * @param string $dbType - The type of database
	 * @param string $tableName - The name of the table
	 * @param string $dir - The relative directory path, omitting the trailing forward slash '/'
	 */
	public function generate($dbType, $tableName, $dir){
		$newtableName = str_replace('_', ' ', $tableName);
		$newtableName = str_replace(' ', '', ucwords($newtableName));
		$columns = $this->showColumns($tableName);
		$fields = array();
		foreach ($columns as $column){
			$fields[] = $column['Field'];
		}
		$fields = "'".implode("', '", $fields)."'";
		
		$fileName = $dir.'/'.$newtableName.'.php';
		$objectName = 'Db_'.$newtableName;

		switch(strtolower($dbType)){
			case 'mysql':
				$dbType = 'MySQL';
				$extension = 'Vm_Db_MySql_Dml';
				break;
			default:
				throw new Vm_Db_Exception('Database driver type "'.$driverType.'" is not supported.');
		}
		
		$file = <<<EOT
<?php
/**
 * @description The database class for the $tableName table. Uses a $dbType database. This class has been auto-generated.
 * @extends Vm_Db_Factory_Dml
 * @extends $extension
 */
class $objectName extends Vm_Db_Factory_Dml {
	
	/**
	 * @param object \$db - The PDO connection
	 * @param string \$prefix - optional - The prefix for the database 
	 * @return Returns the current object for chaining purposes
	 */
	function __construct(PDO \$db, \$prefix = NULL) {
		parent::__construct(\$db, '$dbType', '$tableName', array($fields), 'public', \$prefix);
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