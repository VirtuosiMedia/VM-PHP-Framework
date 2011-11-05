<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Generates a database file
* Requirements: PHP 5.2 or higher
*/
class Vm_Db_File_Generator extends DbOperations {
	
	/**
	 * Generates a database class file for the given table
	 * @param string $tableName - The name of the table
	 * @param string $dir - The relative directory path, omitting the trailing forward slash '/'
	 */
	public function generate($tableName, $dir){
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
		
		$file = <<<EOT
<?php
class $objectName extends DBObject {
	function __construct(\$db, \$prefix = NULL) {
		parent::__construct(\$db, '$tableName', array($fields), 'public', \$prefix);
  	}
}
?>
EOT;
		$fileHandler = fopen($fileName, 'w');
		fwrite($fileHandler, $file);
		fclose($fileHandler);
	}
	
	/**
	 * Generates a database class file for all tables in the database
	 * @param string $dir - The relative directory path, omitting the trailing forward slash '/'
	 */
	public function generateAll($dir){
		$tables = $this->showTables();
		foreach ($tables as $tableName){
			$this->generate($tableName[0], $dir);	
		}		
	}
}
?>