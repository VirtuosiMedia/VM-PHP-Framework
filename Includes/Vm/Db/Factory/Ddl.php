<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description This factory class acts as a wrapper for a specific database driver.
 * @uses Vm\Db\MySql\Ddl
 * @namespace Vm\Db\Factory
 */
namespace Vm\Db\Factory;

class Ddl {

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
				$driverName = '\Vm\Db\MySql\Ddl';
				break;
			default:
				throw new \Vm\Db\Exception('Database driver type "'.$driverType.'" is not supported.');
		}
		
		$this->driver = new $driverName($db, $prefix);
		return $this;
	}

	/**
	 * @return string - The database driver type, i.e. 'mysql', 'oracle', etc.
	 */
	public function getDriverType(){
		return $this->driverType;
	}	
	
	/**
	 * @description Gets the database version and returns it.
	 * @return string - The database version
	 */
	public function getDbVersion() {
		return $this->driver->getDbVersion();
	}

	/**
	 * @description Gets the schema and returns it
	 * @return string - The schema list, if supported, else an error message
	 */
	public function getSchemaList() {
		return $this->driver->getSchemaList();
	}

	/**
	 * @description Sets the primary key
	 * @param string $primaryKey - The field name that is to be the primary key
	 * @return The object for chaining 
	 */
	public function setPrimaryKey($primaryKey) {
		$this->driver->setPrimaryKey($primaryKey);
		return $this;
	}

	/**
	 * @description Adds a SMALLINT datatype 
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addSmallInt($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addSmallInt($field, $nullState, $default, $comment);
		return $this;
	}
	
	/**
	 * @description Adds an INT datatype 
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addInt($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addInt($field, $nullState, $default, $comment);
		return $this;
	}
	
	/**
	 * @description Adds a BIGINT datatype 
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addBigInt($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addBigInt($field, $nullState, $default, $comment);
		return $this;
	}
	
	/**
	 * @description Adds a single precision datatype (in MySQL, this is a FLOAT)
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addReal($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addReal($field, $nullState, $default, $comment);
		return $this;
	}
	
	/**
	 * @description Adds a double precision datatype (in MS SQL, this is a FLOAT)
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addDouble($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addDouble($field, $nullState, $default, $comment);
		return $this;
	}

	/**
	 * @description Adds a fixed precision datatype of DECIMAL
	 * @param string $field - The name of the field to be created
	 * @param int $precision -The number of significant digits stored as values
	 * @param int $scale - The number of digits following the decimal
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addDecimal($field, $precision, $scale, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addDecimal($field, $precision, $scale, $nullState, $default, $comment);
		return $this;
	}				

	/**
	 * @description Adds a fixed precision datatype of NUMERIC
	 * @param string $field - The name of the field to be created
	 * @param int $precision -The number of significant digits stored as values
	 * @param int $scale - The number of digits following the decimal
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addNumeric($field, $precision, $scale, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addNumeric($field, $precision, $scale, $nullState, $default, $comment);
		return $this;
	}

	/**
	 * @description Adds a datatype of CHAR
	 * @param string $field - The name of the field to be created
	 * @param int $value - The length of the field
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addChar($field, $value, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addChar($field, $value, $nullState, $default, $comment);
		return $this;
	}

	/**
	 * @description Adds a datatype of VARCHAR
	 * @param string $field - The name of the field to be created
	 * @param int $value - The length of the field
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addVarchar($field, $value, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addVarchar($field, $value, $nullState, $default, $comment);
		return $this;
	}

	/**
	 * @description Adds a datatype of TEXT
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addText($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addText($field, $nullState, $default, $comment);
		return $this;
	}
	
	/**
	 * @description Adds a datatype of DATE (In MS SQL, DATETIME is used)
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addDate($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addDate($field, $nullState, $default, $comment);
		return $this;
	}	

	/**
	 * @description Adds a datatype of TIMESTAMP, which displays both date and time (In MS SQL, DATETIME is used)
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addTimestamp($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addTimestamp($field, $nullState, $default, $comment);
		return $this;
	}

	/**
	 * @description Adds a datatype of BLOB (Binary Large OBject)
	 * @param string $field - The name of the field to be created
	 * @param int $value - The length of the field - Not all DBMS's will use this variable
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 * @return The object for chaining 
	 */	
	public function addBlob($field, $value, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->driver->addBlob($field, $value, $nullState, $default, $comment);
		return $this;
	}

	/**
	 * @description Specifies the columns that are to be constrained as unique
	 * @param mixed $columns - The name of the unique columns as a string if there is only one, an array for multiples
	 * @return The object for chaining 
	 */	
	public function unique($columns) {
		$this->driver->unique($columns);
		return $this;
	}	
	
	/**
	 * @description Specifies the columns that are to be constrained as foreign keys
	 * @param mixed $columns - The name of the column that is a foreign key in string form or an array of column names
	 * @return The object for chaining 
	 */	
	public function foreignKey($columns) {
		$this->driver->foreignKey($columns);
		return $this;
	}	
	
	/**
	 * @description Compiles everything into an SQL query and executes it depending on the mode
	 * @param string $table_name - The name of the table to be created. The prefix will be prepended automatically
	 * @param string optional $comments - Table comments
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function createTable($tableName, $schema=NULL, $comments=NULL, $debug=FALSE) {
		return $this->driver->createTable($tableName, $schema, $comments, $debug);		 
	} 
	
	/**
	 * @description Drops a table
	 * @param string $tableName - The table name to be dropped
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function dropTable($tableName, $debug=FALSE) {
		return $this->driver->dropTable($tableName, $debug);
	}

	/**
	 * @description Renames a table
	 * @param string $tableName - The table name to be dropped
	 * @param string $newName - The new name of the table
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function renameTable($tableName, $newName, $debug=FALSE) {
		return $this->driver->renameTable($tableName, $newName, $debug);
	}
	
	/**
	 * @description Adds a column to a table
	 * @param string $tableName - The table name to which the column is added
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function addColumn($tableName, $debug=FALSE) {
		return $this->driver->addColumn($tableName, $debug);	
	}
	
	/**
	 * @description Drops a column from a table
	 * @param string $tableName - The table from which the column is dropped
	 * @param string $columnName - The column to be dropped
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function dropColumn($tableName, $columnName, $debug=FALSE) {
		return $this->driver->dropColumn($tableName, $columnName, $debug);	
	}

	/**
	 * @description Shows all the tables in the given database
	 * @param string $dbName - optional - The name of the database to fetch table data from, defaults to current connection
	 * @return array - The names of the tables in a BOTH array format
	 */
	public function showTables($dbName = NULL){
		return $this->driver->showTables($dbName);
	}
	
	/**
	 * @description Shows the columns and their info for the given table
	 * @param string $tableName - The name of the table
	 * @param string $columnName - optional - The name of a single column to return info for, defaults to all columns in table  
	 * @return array - Uses PDO::fetchAll() to return the column names and info
	 */
	public function showColumns($tableName, $columnName=NULL){
		return $this->driver->showColumns($tableName, $columnName);
	}
	
	/**
	 * @description Shows the table creation statement for the given table
	 * @param string $tableName - The table name
	 * @return string - The SQL CREATE TABLE statement for the given table
	 */
	public function showCreateTable($tableName){
		return $this->driver->showCreateTable($tableName);
	}

	/**
	 * @description Shows the indices for the given table
	 * @param string $tableName - The table name
	 * @return array - The SQL SHOW INDEX statement for the given table
	 */	
	public function showIndex($tableName){
		return $this->driver->showIndex($tableName);
	}

	/**
	 * @description Shows the metadata for the given table
	 * @param string $tableName - The table name
	 * @return array - The SQL SHOW TABLE STATUS statement for the given table
	 */	
	public function showTableStatus($tableName){
		return $this->driver->showTableStatus($tableName);	
	}
}