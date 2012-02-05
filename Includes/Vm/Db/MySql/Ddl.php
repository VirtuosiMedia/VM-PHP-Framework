<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The Vm_Db_MySql_Ddl class is for performing data definition language (DDL) statements including 
 * 		creating and deleting tables, getting table schemas, a list of schemas, a full table list, a database version, 
 * 		adding and dropping columns, creating an XML representation of the table metadata, parsing an XML snippet of 
 * 		table metadata to create a table. Vm\Db\MySql\Ddl is for use with a MySQL PDO driver.
 * @requires A database connection script that uses the MySQL PDO extension
 * @namespace Vm\Db\MySql
 */
namespace Vm\Db\MySql;

class Ddl {

	protected $columns;
	protected $db;	
	protected $foreignKeys;
	protected $prefix;
	protected $uniques;
		
	/**
	 * @param object $db - A PDO database connection object
	 * @param string $prefix - optional - A prefix to add to a created table
	 */
	function __construct($db, $prefix = NULL) {
		$this->db = $db;
		$this->prefix = $prefix;
	}

	/**
	 * @description Protected function for checking the mode and either displaying the query for debugging or
	 * 		executing the query
	 * @param string $query - The query to be run
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	protected function executeQuery($query, $debug=FALSE) {
		if ($debug) {
			return $query;		
		} else {
			$result = $this->db->prepare($query);
			return $result->execute();
		}	
	}

	/**
	 * @description Gets the database version and returns it.
	 * @return string - The database version
	 */
	public function getDbVersion() {
		$result = $this->db->query('SELECT VERSION()');
		$version = $result->fetch();
		return $version['VERSION()'];
	}

	/**
	 * @description Gets the schema and returns it
	 * @return string - MySQL does not support schemas, so an error message is returned
	 */
	public function getSchemaList() {
		return 'MySQL does not support schemas.';
	}

	/**
	 * @description Sets the primary key
	 * @param string $primaryKey - The field name that is to be the primary key
	 */
	public function setPrimaryKey($primaryKey) {
		$this->columns[] = "$primaryKey INT NOT NULL PRIMARY KEY AUTO_INCREMENT";
	}

	/**
	 * @description The base function for data types, to be used by the specific datatype functions. It adds the compiled
	 * 		column to the columns array
	 * @param string $field - The name of the field to be created
	 * @param string $datatype - The datatype (including its values if it needs them)
	 * @param string $default - The default value of the field. Note: if the default is a numeric zero (0), it must be quoted
	 * 	like '0' when it is inputted as a parameter. All other numeric parameters do not need to be quoted.
	 * @param string $comment - The comment for the field
	 */
	protected function datatype($field, $datatype, $nullState=NULL, $default=NULL, $comment=NULL) {
		$nullState = (strtolower($nullState) == 'null') ? ' NULL' : ' NOT NULL';

		if ($default) {
			$default = ($default == 'CURRENT_TIMESTAMP') ? " DEFAULT $default" : " DEFAULT '$default'";
		} else if ($default == '0') {
			$default = " DEFAULT 0";
		} else {
			$default = NULL;
		}
		
		$comment = ($comment) ? " COMMENT '$comment'" : NULL;				
		$this->columns[] = "$field $datatype$nullState$default$comment";
	} 
	
	/**
	 * @description Adds a SMALLINT datatype 
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addSmallInt($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, 'SMALLINT', $nullState, $default, $comment);
	}
	
	/**
	 * @description Adds an INT datatype 
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addInt($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, 'INT', $nullState, $default, $comment);
	}
	
	/**
	 * @description Adds a BIGINT datatype 
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addBigInt($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, 'BIGINT', $nullState, $default, $comment);
	}
	
	/**
	 * @description Adds a single precision datatype (in MySQL, this is a FLOAT)
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addReal($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, 'FLOAT', $nullState, $default, $comment);
	}
	
	/**
	 * @description Adds a double precision datatype (in MS SQL, this is a FLOAT)
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addDouble($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, 'DOUBLE', $nullState, $default, $comment);
	}

	/**
	 * @description Adds a fixed precision datatype of DECIMAL
	 * @param string $field - The name of the field to be created
	 * @param int $precision -The number of significant digits stored as values
	 * @param int $scale - The number of digits following the decimal
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addDecimal($field, $precision, $scale, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, "DECIMAL($precision, $scale)", $nullState, $default, $comment);
	}				

	/**
	 * @description Adds a fixed precision datatype of NUMERIC
	 * @param string $field - The name of the field to be created
	 * @param int $precision -The number of significant digits stored as values
	 * @param int $scale - The number of digits following the decimal
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addNumeric($field, $precision, $scale, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, "NUMERIC($precision, $scale)", $nullState, $default, $comment);
	}

	/**
	 * @description Adds a datatype of CHAR
	 * @param string $field - The name of the field to be created
	 * @param int $value - The length of the field
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addChar($field, $value, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, "CHAR($value)", $nullState, $default, $comment);
	}

	/**
	 * @description Adds a datatype of VARCHAR
	 * @param string $field - The name of the field to be created
	 * @param int $value - The length of the field
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addVarchar($field, $value, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, "VARCHAR($value)", $nullState, $default, $comment);
	}

	/**
	 * @description Adds a datatype of TEXT
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addText($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, "TEXT", $nullState, $default, $comment);
	}
	
	/**
	 * @description Adds a datatype of DATE (In MS SQL, DATETIME is used)
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addDate($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, "DATE", $nullState, $default, $comment);
	}	

	/**
	 * @description Adds a datatype of TIMESTAMP, which displays both date and time (In MS SQL, DATETIME is used)
	 * @param string $field - The name of the field to be created
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addTimestamp($field, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, "TIMESTAMP", $nullState, $default, $comment);
	}

	/**
	 * @description Adds a datatype of BLOB (Binary Large OBject)
	 * @param string $field - The name of the field to be created
	 * @param int $value - The length of the field - Not all DBMS's will use this variable
	 * @param string $nullState - optional - 'NULL' or 'NOT NULL'
	 * @param string $default - optional - The default value of the field
	 * @param string $comment - optional - The comment for the field
	 */	
	public function addBlob($field, $value, $nullState=NULL, $default=NULL, $comment=NULL) {
		$this->datatype($field, "BLOB($value)", $nullState, $default, $comment);
	}

	/**
	 * @description Specifies the columns that are to be constrained as unique
	 * @param mixed $columns - The name of the unique columns as a string if there is only one, an array for
	 * 		multiples
	 */	
	public function unique($columns) {
		$columns = (is_array($columns)) ? implode(', ', $columns) : $columns; 
		$this->uniques = "UNIQUE ($columns)";
	}	
	
	/**
	 * @description Specifies the columns that are to be constrained as foreign keys
	 * @param mixed $columns - The name of the column that is a foreign key in string form or an array of column names
	 */	
	public function foreignKey($columns) {
		$columns = (is_array($columns)) ? implode(', ', $columns) : $columns;
		$this->foreignKeys = "INDEX ($columns)";
	}	
	
	/**
	 * @description Compiles everything into an SQL query and executes it depending on the mode
	 * @param string $table_name - The name of the table to be created. The prefix will be prepended automatically
	 * @param string optional $comments - Table comments
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function createTable($tableName, $schema=NULL, $comments=NULL, $debug=FALSE) {
		$table = $this->prefix.$tableName;
		$tableComments = ($comments) ? " COMMENT = '$comments'" : NULL;
		$query = "CREATE TABLE IF NOT EXISTS $table ( ".implode(", ", $this->columns);
		$query .= ($this->uniques) ? ', '.$this->uniques : NULL;
		$query .= ($this->foreignKeys) ? ', '.$this->foreignKeys : NULL;
		$query .= ")$tableComments";
		return $this->executeQuery($query, $debug);		 
	} 
	
	/**
	 * @description Drops a table
	 * @param string $tableName - The table name to be dropped
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function dropTable($tableName, $debug=FALSE) {
		$table = $this->prefix.$tableName;
		$query = "DROP TABLE $table";
		return $this->executeQuery($query, $debug);
	}

	/**
	 * @description Renames a table
	 * @param string $tableName - The table name to be dropped
	 * @param string $newName - The new name of the table
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function renameTable($tableName, $newName, $debug=FALSE) {
		$table = $this->prefix.$tableName;
		$newName = $this->prefix.$newName;
		$query = "ALTER TABLE $table RENAME TO $newName";
		return $this->executeQuery($query, $debug);
	}
	
	/**
	 * @description Adds a column to a table
	 * @param string $tableName - The table name to which the column is added
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function addColumn($tableName, $debug=FALSE) {
		$table = $this->prefix.$tableName;
		$query = "ALTER TABLE $table ADD COLUMN ";
		$query .= join(", ", $this->columns);
		return $this->executeQuery($query, $debug);	
	}
	
	/**
	 * @description Drops a column from a table
	 * @param string $tableName - The table from which the column is dropped
	 * @param string $columnName - The column to be dropped
	 * @param boolean $debug - Whether or not to return a debugging statement, defaults FALSE
	 * @return mixed - The debug statement if debug is set TRUE, else boolean depending on query success
	 */
	public function dropColumn($tableName, $columnName, $debug=FALSE) {
		$table = $this->prefix.$tableName;
		$query = "ALTER TABLE $table DROP COLUMN $columnName";
		return $this->executeQuery($query, $debug);	
	}

	/**
	 * @description Shows all the tables in the given database
	 * @param string $dbName - optional - The name of the database to fetch table data from, defaults to current connection
	 * @return array - The names of the tables in a BOTH array format
	 */
	public function showTables($dbName = NULL){
		$query = ($dbName) ? "SHOW TABLES FROM $dbName" : 'SHOW TABLES';
		$result = $this->db->prepare($query);
		$result->execute();
		return $result->fetchAll();
	}
	
	/**
	 * @description Shows the columns and their info for the given table
	 * @param string $tableName - The name of the table
	 * @param string $columnName - optional - The name of a single column to return info for, defaults to all columns in table  
	 * @return array - Uses PDO::fetchAll() to return the column names and info
	 */
	public function showColumns($tableName, $columnName=NULL){
		$table = $this->prefix.$tableName;
		$query = ($columnName) ? "SHOW FULL COLUMNS FROM $table LIKE '$columnName'" : "SHOW FULL COLUMNS FROM $table";
		$result = $this->db->prepare($query);
		$result->execute();
		return $result->fetchAll();
	}
	
	/**
	 * @description Shows the table creation statement for the given table
	 * @param string $tableName - The table name
	 * @return string - The SQL CREATE TABLE statement for the given table
	 */
	public function showCreateTable($tableName){
		$table = $this->prefix.$tableName;
		$query = "SHOW CREATE TABLE $tableName";
		$result = $this->db->prepare($query);
		$result->execute();
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row['Create Table'];
	}

	/**
	 * @description Shows the indices for the given table
	 * @param string $tableName - The table name
	 * @return array - The SQL SHOW INDEX statement for the given table
	 */	
	public function showIndex($tableName){
		$table = $this->prefix.$tableName;
		$query = "SHOW INDEX FROM $tableName";
		$result = $this->db->prepare($query);
		$result->execute();
		return $result->fetchAll();
	}

	/**
	 * @description Shows the metadata for the given table
	 * @param string $tableName - The table name
	 * @return array - The SQL SHOW TABLE STATUS statement for the given table
	 */	
	public function showTableStatus($tableName){
		$table = $this->prefix.$tableName;
		$query = "SHOW TABLE STATUS LIKE '$tableName'";
		$result = $this->db->prepare($query);
		$result->execute();
		return $result->fetchAll();		
	}
}