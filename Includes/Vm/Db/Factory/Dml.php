<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description An abstract database class factory, meant to be extended by table-specific database classes. This 
 * 		factory class acts as a wrapper for a specific database driver.
 * @uses Vm\Db\MySql\Dml
 * @namespace Vm\Db\Factory
 */
namespace Vm\Db\Factory;

abstract class Dml {

	protected $driver;
	protected $driverType;
	
	/**
	 * @param object $db - The PDO database connection object
	 * @param string $table - The name of the table
	 * @param array $fields - The names of each field in the table
	 * @param string $schema - The schema
	 * @param $prefix - optional - A prefix for the table
	 * @return The object for chaining 
	 */
	public function __construct(PDO $db, $driverType, $table, array $fields, $schema, $prefix = NULL){
		$driverType = strtolower($driverType);
		switch ($driverType){
			case 'mysql':
				$driverName = '\Vm\Db\MySql\Dml';
				break;
			default:
				throw new Vm_Db_Exception('Database driver type "'.$driverType.'" is not supported.');
		}
		
		$this->driver = new $driverName($db, $table, $fields, $schema, $prefix);
		return $this;
	}
	
	/**
	 * @param string $key - The table column name to retrieve 
	 * @return mixed - The value of the key, if the key exists, FALSE otherwise
	 */
	public function __get($key){
		return $this->driver->$key;		
	}
	
	/**
	 * @param string $key - The table column name to be assigned a value
	 * @param $value - The value to assign to the key
	 */
	public function __set($key, $value){
		$this->driver->$key = $value;
	}

	/**
	 * @return string - The database driver type, i.e. 'mysql', 'oracle', etc.
	 */
	public function getDriverType(){
		return $this->driverType;
	}
	
	/**
	 * @param string $alias - Sets the alias for the main table. Optional for query build.
	 * @return The object for chaining  
	 */
	public function alias($alias) {
		$this->driver->alias($alias);
		return $this;
	}
	
	/**
	 * @var array $selectList - Used for determining which database fields should be selected. Fields from joins may be 
	 * 		included, but must include either the full table name or alias as a prefix. 
	 * @return The object for chaining
	 */
	public function selectList(array $selectList) {
	 	$this->driver->selectList($selectList);
		return $this;
	}
	
	/**
	 * @param string $quotable - Escapes an input variable for use in an SQL query. Returns the escaped string. Optional 
	 * 		for query build.
	 * @return The escaped input
	 */
	public function quote($quotable) {
		return $this->driver->quote($quotable);
	}

	/**
	 * Joins will be added to the join array and processed in their array order and use the ON syntax 
	 * 	rather than USING. Optional for query build.
	 * @param string $joinType -The type of join to be performed. Acceptable values are 'left', 'right', 'inner', and 
	 * 		'full'
	 * @param string $table - The name of the table to be joined with the current table
	 * @param string $column - The column(s) to be compared to the value
	 * @param string $operator - The operator to be used in the comparison
	 * @param string $value - The value to which $column is compared
	 * @param string $tableAlias - optional - The alias of the joined table. If not set, alias defaults to the table 
	 * 		name
	 * @return The object for chaining
	 */
	public function addJoin($joinType, $table, $column, $operator, $value, $tableAlias=NULL){
		$this->driver->addJoin($joinType, $table, $column, $operator, $value, $tableAlias);
		return $this;
	}

	/**
	 * Creates a where clause.  Optional for query build.
	 * @param string $column - The column(s) to be compared to the value
	 * @param string $operator - The operator to be used in the comparison. 
	 * @param mixed $value - The value to which $column is compared - If multiple values are entered as an array, they 
	 * 		will be wrapped in parentheses, else use a string
	 * @param string (optional) $antecedent - The operator preceding the WHERE clause. Acceptable values are 'AND' and 
	 * 		'OR' 
	 * @param string (optional) $paren - Adds a paren to the WHERE clause - 'open', 'close', 'both'
	 * @param boolean $caseSensitive - optional - Whether or not the clause should be case sensitive, defaults TRUE. 
	 * 		If FALSE, will use UTF8_GENERAL_CI
	 * @param boolean $bind - optional - Whether or not $value should be a bound parameter, defaults TRUE. Use FALSE 
	 * 		when $value is a subquery
	 * @return The object for chaining
	 */
	public function where($column, $operator, $value, $antecedent=NULL, $paren=NULL, $caseSensitive = TRUE, $bind = TRUE){
		$this->driver->where($column, $operator, $value, $antecedent, $paren, $caseSensitive, $bind);
		return $this;		
	}

	/**
	 * @param array $groupBy - The fields by which the result set should be grouped. Optional for query build.
	 * @return The object for chaining
	 */
	public function groupBy(array $groupBy) {
	 	$this->driver->groupBy($groupBy);
		return $this;
	}

	/**
	 * Creates a HAVING clause.  Optional for query build. MUST be used in conjunction with the GROUP BY clause
	 * @param string $column - The column(s) to be compared to the value. Note: The column is not a bound parameter in 
	 * 		this clause
	 * @param string $operator - The operator to be used in the comparison
	 * @param string $value - The value to which $column is compared
	 * @param string $antecedent - optional - The operator preceding the HAVING clause. Acceptable values are 'AND' and 
	 * 		'OR'
	 * @param string $function - optional - The SQL function to apply to the column
	 * @return The object for chaining 
	 */
	public function having($column, $operator, $value, $antecedent=NULL, $function=NULL) {
		$this->driver->having($column, $operator, $value, $antecedent, $function);
		return $this;
	}

	/**
	 * Orders the query.  Optional for query build
	 * @param string $field - The field to sort by
	 * @param string $sort (optional) - The sort type. Acceptable values are ASC and DESC
	 * @param boolean $caseSensitive - optional - Whether or not the ordering should be case sensitive, defaults TRUE. 
	 * 		If FALSE, will use UTF8_GENERAL_CI
	 * @return The object for chaining
	 */
	public function orderBy($field, $sort=NULL, $caseSensitive=TRUE){
		$this->driver->orderBy($field, $sort, $caseSensitive);
		return $this;
	}
	
	/**
	 * @param int $limit - The limit of the result set.  Optional for query build.
	 * @return The object for chaining
	 */
	public function limit($limit) {
		$this->driver->limit($limit);
		return $this;
	}
	
	/**
	 * @param int $offset - The offset of the result set.  Optional for query build.
	 * @return The object for chaining
	 */
	public function offset($offset) {
		$this->driver->offset($offset);
		return $this;
	}

	/**
	 * @param array $columns - The columns to be searched
	 * @param array $terms - The terms to be searched for as an array of strings. Note: Wildcard characters may be 
	 * 		included (%, _)
	 * @param array $excludedTerms - optional - The terms to be excluded as an array of strings. Note: Wildcard 
	 * 		characters may be included (%, _)
	 * @param string $type - optional - 'any' to match any of the terms and 'all' to match all of the terms, 
	 * 		defaults to 'all'
	 * @param boolean $ranked - optional - Whether or not to rank the results, defaults TRUE
	 * @param boolean $caseSensitive - optional - Whether or not to the search should be case sensitive, defaults FALSE.
	 * @return The object for chaining
	 */
	public function search(array $columns, array $terms, array $excludedTerms = array(), $type = 'all', $ranked = TRUE, $caseSensitive = FALSE){
		$this->driver->search($columns, $terms, $excludedTerms, $type, $ranked, $caseSensitive);
		return $this;		
	}
		
	/**
	 * Clears all class variables by setting them to NULL, allow class instance reuse
	 * @param boolean $clearBound - optional - Whether or not the bound variables should be cleared, defaults TRUE
	 */
	public function clear($clearBound = TRUE) {
 		$this->driver->clear($clearBound);
	} 

	/**
	 * Compiles the given data into a select query and returns a result set based on the query
	 * @param string (optional) $mode - 
	 * If set to 'single', returns a single result set which may be accessed through magic methods.
	 * 
	 * Example: $user->name or $user->{'name'} 
	 *
	 * If set to 'assoc', will return the result set as an associative array, which can be accessed through 
	 * a foreach loop
	 *
	 * Example:	foreach ($user->select("assoc") as $row) {
	 *				echo "ID = ".$row['userId']."\t";
	 *				echo "Type = ".$row['firstName']."\t";
	 *				echo "Parent = ".$row['lastName']."<br>";
	 *			}
	 *
	 * If set to 'num', will return the result set as a numerical array
	 *
	 * If set to 'obj', will return an anonymous object with property names that correspond to the column 
	 * names returned in your result set
	 *
	 * If set to 'lazy', will return a combination of 'both' and 'obj', creating the object variable names 
	 * as they are accessed
	 *
	 * If set to 'subquery', will wrap the query in parantheses and return it for use in a subquery without 
	 * 		executing it.
	 * 	WARNING: Bound parameters are not used for subqueries 
	 *
	 * If set to "count", will return the number of rows
	 *
	 * Example: $number = $user->select("count");
	 *
	 * If set to "union", will add the query to the unions array. Note: you must reuse the object to use union.
	 * 
	 * Example:
	 * 
	 * $user = new Db_User($db);
	 * 
	 * //First Query
	 * $user->where('lastName', '=', 'Jones');
	 * $user->select('union');
	 * 
	 * //Second Query
	 * $user->where('lastName', '=', 'Smith');
	 * $user->select('union');
	 * 
	 * //Get union results (orderBy and limit are optional)
	 * $user->orderBy('lastName', 'ASC');
	 * $user->orderBy('firstName', 'ASC');
	 * $user->limit(25);
	 * $users = $user->select('assoc');
	 *
	 * If set to "debug", prints the compiled query
	 *
	 * Example: $user->select->("debug"); 
	 *
	 * If left unset, the default return set is 'both', which returns the combination of both an associative array
	 * and a numerical array
	 * @param string $selectType - optional - 'DISTINCT' or 'ALL'. Note: $selectType is ignored for the first select 
	 * 		query in a set of unions
	 * @return mixed - The query result set
	 */
	public function select($mode=NULL, $selectType=NULL) {
		return $this->driver->select($mode, $selectType);
	} 
	
	/**
	 * The insert function inserts records into the database. Magic methods are used to insert
	 * 	values into each field. Fields that are not assigned a value will not be included in the compiled query.
	 * Example usage: 
	 * 		$users = new Db_Users($db);
	 *		$users->username = 'jDoe';
	 *		$users->firstName = 'John';
	 *		$users->{'lastName'} = 'Doe'; //An alternate syntax
	 *		$users->{'age'} = 37;
	 *		$users->insert();
	 * @param string $mode - optional - 
	 * 	If set to 'debug', returns the compiled SQL query 
	 * @return mixed - The last insert id if the query is successful, the compiled query if mode is set to debug, 
	 * 	FALSE otherwise.
	 */
	public function insert($mode=NULL) {
		return $this->driver->insert($mode);
	} 

	/**
	 * Updates a database field with values obtained from magic methods representing the field names. 
	 * Notes: Multiple table updates are currently not supported, nor are ordering or limiting result sets due to
	 * 	DBMS syntax inconsistencies
	 * @param string $mode - optional - If set to 'debug', returns the compiled SQL query
	 * @return int - The number of affected rows 
	 */
	public function update($mode=NULL) {
		return $this->driver->update($mode);
	} 
	
	/**
	 * The delete function deletes all rows that meet the conditions specified in the where clause
	 * 	and returns the number of affected rows
	 * @param string $mode - optional - Acceptable value is 'debug', which prints the compiled query
	 */
	public function delete($mode=NULL) {
		return $this->driver->delete($mode);
	}

	/**
	 * Description: Deletes all rows in the table, returns the number of affected rows.
	 * @return int - The number of affected rows
	 */
	public function deleteAll(){
		return $this->driver->deleteAll();
	}	
}