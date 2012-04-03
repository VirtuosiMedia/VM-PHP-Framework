<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The Vm_Db_Mysql_Dml class handles data manipulation language (DML) statements for a MySQL database. It 
 * 		is not to be used directly, but is intended to be wrapped by Vm_Db_Factory_Dml. This particular Vm_Db_Mysql_Dml 
 * 		is for use with a MySQL	PDO driver.
 * @requires A database connection script that uses the MySQL PDO extension
 * @namespace Vm\Db\MySql
 * @uses Vm\Db\Exception
 * @uses PDO
 */
namespace Vm\Db\MySql;

use \Vm\Db\Exception;
use \PDO;

class Dml {

	protected $alias = NULL;
	protected $boundValues = array();
	protected $db;
	protected $fields = array();
	protected $groupBy = NULL;
	protected $having = NULL;
	protected $joinedTables = array();
	protected $joins = NULL;
	protected $limit = NULL;
	protected $numBoundValues = 0;
	protected $offset = NULL;
	protected $operators = array(
		'=', '!=', '<>', '>', '<', '>=', '<=', '!<', '!>',
		'ALL', 'AND', 'ANY', 'BETWEEN', 'EXISTS', 'IN', 'LIKE', 'NOT', 'OR', 'IS NULL', 'UNIQUE' 
	);
	protected $orderBy = NULL;
	protected $patterns = array();
	protected $prefix;	
	protected $selectList = NULL;
	protected $table;
	protected $tablePrefix = NULL;
	protected $tableSingular;
	protected $unions = array();
	protected $valueStorage = array();
	protected $where = NULL;
		
	/**
	 * @param object $db - The PDO database connection object
	 * @param array $table - The plural (actual) name of the table as the key, the singular name as the value
	 * @param array $fields - The names of each field in the table
	 * @param string $schema - The schema
	 * @param $prefix - optional - A prefix for the table
	 * @return The object for chaining 
	 */
	function __construct(\PDO $db, array $table, array $fields, $schema, $prefix = NULL){
		$this->db = $db;
		$this->prefix = $prefix;
		$this->table = key($table);
		$this->tablePrefix = ($prefix) ? $prefix.$this->table : $this->table;
		$this->tableSingular = $table[$this->table];
		$this->fields = $fields;		
		return $this;
	}

	/**
	 * @param string $key - The table column name to retrieve 
	 * @return mixed - The value of the key, if the key exists, FALSE otherwise
	 */
	function __get($key){
		return (array_key_exists($key, $this->valueStorage)) ? $this->valueStorage[$key] : FALSE;		
	}

	/**
	 * @param string $key - The table column name to be assigned a value
	 * @param $value - The value to assign to the key
	 * @return boolean - TRUE if the key exists, FALSE otherwise
	 */
	function __set($key, $value){
		if (in_array($key, $this->fields)){
			$this->valueStorage[$key] = $value;
		} else {
			throw new Exception('The "'.$key.'" column is not detected for the "'.$this->table.'" table and cannot be 
				assigned a value. Please verify that "'.$key.'" exists as both a column in your database and as a field 
				in your	database class for this table.');
		}
	}

	/**
	 * @description Gets the plural form of the table name. This should correspond to the actual name of the table in 
	 * 		the database.
	 * return The plural name of the table as a string.
	 */
	public function getPluralName(){
		return $this->table;
	}

	/**
	 * @description Gets the singular form of the table name.
	 * return The singular name of the table as a string.
	 */
	public function getSingularName(){
		return $this->tableSingular;
	}	
	
	/**
	 * @description Gets the fields (columns) for the table
	 * @return An array of the table fields.
	 */
	public function getFields(){
		return $this->fields;
	}
	
	/**
	 * @description Adds a value to be bound on query execution
	 * @param mixed $value - The value(s) to be bound
	 * @return string - The named value placeholder
	 */
	protected function addBoundValue($value){
		if (is_array($value)){
			$valueNames = array();
			foreach ($value as $val){
				$this->numBoundValues += 1;
				$valueName = ':'.$this->numBoundValues;
				$this->boundValues[$valueName] = $val;
				$this->patterns[] = '#(\s'.$valueName.'[\s]?)#';
				$valueNames[] = $valueName;
			}
			return $valueNames;			
		} else {
			$this->numBoundValues += 1;
			$valueName = ':'.$this->numBoundValues;
			$this->boundValues[$valueName] = $value;
			$this->patterns[] = '#(\s'.$valueName.'[\s]?)#';
			return $valueName;
		}
	}

	/**
	 * @description Adds space separators to each bound value
	 * @return array - The spaced bound values
	 */
	protected function getSpacedBoundValues(){
		$boundValues = ' '.implode(' | ', $this->boundValues).' ';
		return explode('|', $boundValues);
	}

	/**
	 * @param string $alias - Sets the alias for the main table. Optional for query build.
	 * @return The object for chaining  
	 */
	public function alias($alias) {
		$this->alias = $alias;
		return $this;
	}	
	
	/**
	 * @var array $selectList - Used for determining which database fields should be selected. Fields from joins may be included,
	 * 	but must include either the full table name or alias as a prefix. 
	 * @return The object for chaining
	 */
	public function selectList(array $selectList) {
	 	foreach($selectList as $value) {
			$this->selectList[] = $value;
		}
		return $this;
	}

	/**
	 * @param string $quotable - Escapes an input variable for use in an SQL query. Returns the escaped string. Optional for query build.
	 * @return The escaped input
	 */
	public function quote($quotable) {
		return $this->db->quote($quotable);
	}

	/**
	 * @description Gets a list of tables that have been joined to the table for the current query.
	 * @return An array of tables that have been joined to the query.
	 */
	public function getJoinedTables(){
		return $this->joinedTables;
	}
	
	/**
	 * @description Joins will be added to the join array and processed in their array order and use the ON syntax 
	 * 	rather than USING. Optional for query build.
	 * @param string $joinType -The type of join to be performed. Acceptable values are 'left', 'right', 'inner', and 'full'
	 * @param string $table - The name of the table to be joined with the current table
	 * @param string $column - The column(s) to be compared to the value
	 * @param string $operator - The operator to be used in the comparison
	 * @param string $value - The value to which $column is compared
	 * @param string $tableAlias - optional - The alias of the joined table. If not set, alias defaults to the table name
	 * @return The object for chaining
	 */
	public function join($joinType, $table, $column, $operator, $value, $tableAlias=NULL){
		$this->joinedTables[] = $table;
		$joinAlias = ($tableAlias) ? $tableAlias : $table;
		$joinName = ($this->prefix) ? $this->prefix.$table : $table;
		$expr = "$column $operator $value";	
		switch (strtolower($joinType)) {
			case "left":
				$this->joins[] = " LEFT JOIN $joinName AS $joinAlias ON $expr";
				break;
			case "right":
				$this->joins[] = " RIGHT JOIN $joinName AS $joinAlias ON $expr";
				break;					
			case "inner":
				$this->joins[] = " INNER JOIN $joinName AS $joinAlias ON $expr";
				break;
			case "full":
				$this->joins[] = " FULL JOIN $joinName AS $joinAlias ON $expr";
				break;
			default:
				throw new Exception("'$joinType' is not a supported join type.");
		}
		return $this;
	}
	
	/**
	 * @description A convenience method for creating joins. Joins will be added to the join array and processed
	 * 		in their array order and use the ON syntax rather than USING. Optional for query build.
	 * @param string $joinType - The type of join: 'left', 'right', 'inner', 'full'.
	 * @param string $finalTable - The name of the table to be joined with the current table. For 2 table joins, this
	 * 		will simply be the second table. For 3 table joins, it will be the third table, which is joined to the
	 * 		current table through an intermediary table.
	 * @param string $throughTable - optional - The name of the intermediary table in a 3 table join. Do not use for a
	 * 		2 table join.
	 * @note In order to use the autoJoin method, your database must follow a strict naming pattern.
	 * 		Database tables must named using plural forms of whatever nouns they represent. Foreign keys refering to
	 * 		the id of any table must be camel-cased and use the singular form of the noun. So, for a table named
	 * 		'users', any foreign key reffering to the users table must be named 'userId'. <strong>Because VM PHP
	 * 		Framework does not contain a pluralization dictionary, if your noun has irregular pluralization, ie.
	 * 		'person/people', you will not be able to use the autoJoin method and should instead construct your query
	 * 		manually using the join method.</strong>
	 */	
	protected function autoJoin($joinType, $finalTable, $throughTable = NULL){
		$tableName = '\Db\\'.$finalTable;
		$tableDb = new $tableName($this->db);
		$finalTableName = $tableDb->getSingularName();
		
		if ($throughTable){
			$tableName = '\Db\\'.$throughTable;
			$tableDb = new $tableName($this->db);
			$throughTableName = $tableDb->getSingularName();			
			$this->join($joinType, $throughTable, $this->table.'.id', '=', $throughTable.'.'.$this->tableSingular.'Id');
			$this->join($joinType, $finalTable, $throughTable.'.'.$finalTableName.'Id', '=', $finalTable.'.id');
		} else {
			if (in_array($finalTableName.'Id', $this->fields)){
				$this->join($joinType, $finalTable, $this->table.'.'.$finalTableName.'Id', '=', $finalTable.'.id');
			} else {
				$this->join($joinType, $finalTable, $this->table.'.id', '=', $finalTable.'.'.$this->tableSingular.'Id');
			}
		}		
	}
	
	/**
	 * @description A convenience method for creating left joins. Joins will be added to the join array and processed 
	 * 		in their array order and use the ON syntax rather than USING. Optional for query build.
	 * @param string $finalTable - The name of the table to be joined with the current table. For 2 table joins, this 
	 * 		will simply be the second table. For 3 table joins, it will be the third table, which is joined to the 
	 * 		current table through an intermediary table.
	 * @param string $throughTable - optional - The name of the intermediary table in a 3 table join. Do not use for a
	 * 		2 table join.
	 * @return The object for chaining
	 * @note In order to use the leftJoin method, your database must follow a strict naming pattern. 
	 * 		Database tables must named using plural forms of whatever nouns they represent. Foreign keys refering to
	 * 		the id of any table must be camel-cased and use the singular form of the noun. So, for a table named 
	 * 		'users', any foreign key reffering to the users table must be named 'userId'. <strong>Because VM PHP
	 * 		Framework does not contain a pluralization dictionary, if your noun has irregular pluralization, ie. 
	 * 		'person/people', you will not be able to use the leftJoin method and should instead construct your query 
	 * 		manually using the join method.</strong>
	 */	
	public function leftJoin($finalTable, $throughTable = NULL){
		$this->autoJoin('left', $finalTable, $throughTable);
		return $this;
	}

	/**
	 * @description A convenience method for creating right joins. Joins will be added to the join array and processed 
	 * 		in their array order and use the ON syntax rather than USING. Optional for query build.
	 * @param string $finalTable - The name of the table to be joined with the current table. For 2 table joins, this 
	 * 		will simply be the second table. For 3 table joins, it will be the third table, which is joined to the 
	 * 		current table through an intermediary table.
	 * @param string $throughTable - optional - The name of the intermediary table in a 3 table join. Do not use for a
	 * 		2 table join.
	 * @return The object for chaining
	 * @note In order to use the rightJoin method, your database must follow a strict naming pattern. 
	 * 		Database tables must named using plural forms of whatever nouns they represent. Foreign keys refering to
	 * 		the id of any table must be camel-cased and use the singular form of the noun. So, for a table named 
	 * 		'users', any foreign key reffering to the users table must be named 'userId'. <strong>Because VM PHP
	 * 		Framework does not contain a pluralization dictionary, if your noun has irregular pluralization, ie. 
	 * 		'person/people', you will not be able to use the rightJoin method and should instead construct your query 
	 * 		manually using the join method.</strong>
	 */	
	public function rightJoin($finalTable, $throughTable = NULL){
		$this->autoJoin('right', $finalTable, $throughTable);
		return $this;
	}	

	/**
	 * @description A convenience method for creating inner joins. Joins will be added to the join array and processed
	 * 		in their array order and use the ON syntax rather than USING. Optional for query build.
	 * @param string $finalTable - The name of the table to be joined with the current table. For 2 table joins, this
	 * 		will simply be the second table. For 3 table joins, it will be the third table, which is joined to the
	 * 		current table through an intermediary table.
	 * @param string $throughTable - optional - The name of the intermediary table in a 3 table join. Do not use for a
	 * 		2 table join.
	 * @return The object for chaining
	 * @note In order to use the innerJoin method, your database must follow a strict naming pattern.
	 * 		Database tables must named using plural forms of whatever nouns they represent. Foreign keys refering to
	 * 		the id of any table must be camel-cased and use the singular form of the noun. So, for a table named
	 * 		'users', any foreign key reffering to the users table must be named 'userId'. <strong>Because VM PHP
	 * 		Framework does not contain a pluralization dictionary, if your noun has irregular pluralization, ie.
	 * 		'person/people', you will not be able to use the innerJoin method and should instead construct your query
	 * 		manually using the join method.</strong>
	 */
	public function innerJoin($finalTable, $throughTable = NULL){
		$this->autoJoin('inner', $finalTable, $throughTable);
		return $this;
	}	

	/**
	 * @description A convenience method for creating full joins. Joins will be added to the join array and processed
	 * 		in their array order and use the ON syntax rather than USING. Optional for query build.
	 * @param string $finalTable - The name of the table to be joined with the current table. For 2 table joins, this
	 * 		will simply be the second table. For 3 table joins, it will be the third table, which is joined to the
	 * 		current table through an intermediary table.
	 * @param string $throughTable - optional - The name of the intermediary table in a 3 table join. Do not use for a
	 * 		2 table join.
	 * @return The object for chaining
	 * @note In order to use the fullJoin method, your database must follow a strict naming pattern.
	 * 		Database tables must named using plural forms of whatever nouns they represent. Foreign keys refering to
	 * 		the id of any table must be camel-cased and use the singular form of the noun. So, for a table named
	 * 		'users', any foreign key reffering to the users table must be named 'userId'. <strong>Because VM PHP
	 * 		Framework does not contain a pluralization dictionary, if your noun has irregular pluralization, ie.
	 * 		'person/people', you will not be able to use the fullJoin method and should instead construct your query
	 * 		manually using the join method.</strong>
	 */
	public function fullJoin($finalTable, $throughTable = NULL){
		$this->autoJoin('full', $finalTable, $throughTable);
		return $this;
	}	
	
	/**
	 * @description Creates a where clause.  Optional for query build.
	 * @param string $column - The column(s) to be compared to the value
	 * @param string $operator - The operator to be used in the comparison. 
	 * @param mixed $value - The value to which $column is compared - If multiple values are entered as an array, they will be wrapped in parentheses, else use a string
	 * @param string (optional) $antecedent - The operator preceding the WHERE clause. Acceptable values are 'AND' and 'OR' 
	 * @param string (optional) $paren - Adds a paren to the WHERE clause - 'open', 'close', 'both'
	 * @param boolean $caseSensitive - optional - Whether or not the clause should be case sensitive, defaults TRUE. If FALSE, will use UTF8_GENERAL_CI
	 * @param boolean $bind - optional - Whether or not $value should be a bound parameter, defaults TRUE. Use FALSE when $value is a subquery
	 * @return The object for chaining
	 */
	public function where($column, $operator, $value, $antecedent=NULL, $paren=NULL, $caseSensitive = TRUE, $bind = TRUE){
		if (!in_array($operator, $this->operators)) {
			throw new Exception('"'.$operator.'" is not a supported operator.');
		}
		
		$value = ($bind) ? $this->addBoundValue($value) : $value;
		$value = (is_array($value)) ? '( '.implode(', ', $value).')' : $value;
		$operator = ($caseSensitive) ? $operator : 'COLLATE UTF8_GENERAL_CI '.$operator;
		switch ($paren){
			case 'open':
				$this->where[] = ($antecedent) ? " $antecedent ($column $operator $value" : " ($column $operator $value";
				break;
			case 'close':
				$this->where[] = ($antecedent) ? " $antecedent $column $operator $value)" : " $column $operator $value)";
				break;
			case 'both':
				$this->where[] = ($antecedent) ? " $antecedent ($column $operator $value)" : " ($column $operator $value)";
				break;						
			default:
				$this->where[] = ($antecedent) ? " $antecedent $column $operator $value" : " $column $operator $value";				
		}
		return $this;		
	}

	/**
	 * @param array $groupBy - The fields by which the result set should be grouped. Optional for query build.
	 * @return The object for chaining
	 */
	public function groupBy(array $groupBy) {
	 	foreach($groupBy as $value) {
			$this->groupBy[] = $value;
		}
		return $this;
	}

	/**
	 * @description Creates a HAVING clause.  Optional for query build. MUST be used in conjunction with the GROUP BY clause
	 * @param string $column - The column(s) to be compared to the value. Note: The column is not a bound parameter in this clause
	 * @param string $operator - The operator to be used in the comparison
	 * @param string $value - The value to which $column is compared
	 * @param string $antecedent - optional - The operator preceding the HAVING clause. Acceptable values are 'AND' and 'OR'
	 * @param string $function - optional - The SQL function to apply to the column
	 * @return The object for chaining 
	 */
	public function having($column, $operator, $value, $antecedent=NULL, $function=NULL) {
		if (!in_array($operator, $this->operators)) {
			throw new Exception('"'.$operator.'" is not a supported operator.');
		}
		
		$column = $this->addBoundValue($column);
		$column = ($function) ? "$function( $column)" : $column;
		$value = $this->addBoundValue($value);
		$this->having[] = ($antecedent) ? " $antecedent $column $operator $value" : "$column $operator $value";
		return $this;
	}

	/**
	 * @description Orders the query.  Optional for query build
	 * @param string $field - The field to sort by
	 * @param string $sort (optional) - The sort type. Acceptable values are ASC and DESC
	 * @param boolean $caseSensitive - optional - Whether or not the ordering should be case sensitive, defaults TRUE. If FALSE, will use UTF8_GENERAL_CI
	 * @return The object for chaining
	 */
	public function orderBy($field, $sort=NULL, $caseSensitive=TRUE){
		$sort = (strtolower($sort) == 'desc') ? 'DESC' : 'ASC';
		$sort = ($caseSensitive) ? $sort : 'COLLATE UTF8_GENERAL_CI '.$sort;
		$this->orderBy[] = ' '.$field." $sort";
		return $this;
	}
	
	/**
	 * @param int $limit - The limit of the result set.  Optional for query build.
	 * @return The object for chaining
	 */
	public function limit($limit) {
		if (!is_int($limit)){
			throw new Exception('Limit must be an integer');
		}
		$this->limit = ($limit != 0) ? $limit : NULL;
		return $this;
	}
	
	/**
	 * @param int $offset - The offset of the result set.  Optional for query build.
	 * @return The object for chaining
	 */
	public function offset($offset) {
		if (!is_int($offset)){
			throw new Exception('Offset must be an integer');
		}
		$this->offset = ($offset != 0) ? $offset : NULL;
		return $this;
	}

	/**
	 * @attribution Credit for the ranking formula: <a href="http://particletree.com/notebook/ranked-searches-with-sql/">Ryan Campbell</a> 
	 * @param array $columns - The columns to be searched
	 * @param array $terms - The terms to be searched for as an array of strings. Note: Wildcard characters may be included (%, _)
	 * @param array $excludedTerms - optional - The terms to be excluded as an array of strings. Note: Wildcard characters may be included (%, _)
	 * @param string $type - optional - 'any' to match any of the terms and 'all' to match all of the terms, defaults to 'all'
	 * @param boolean $ranked - optional - Whether or not to rank the results, defaults TRUE
	 * @param boolean $caseSensitive - optional - Whether or not to the search should be case sensitive, defaults FALSE.
	 * @return The object for chaining
	 */
	public function search(array $columns, array $terms, array $excludedTerms = array(), $type = 'all', $ranked = TRUE, $caseSensitive = FALSE){
		//This combines all the columns together to search against
		$searchColumns = (sizeof($columns) > 1) ? "CONCAT_WS(' ', ".implode(", ", $columns).")" : implode('', $columns);
		$searchColumns = ($caseSensitive) ? $searchColumns : $searchColumns.' COLLATE UTF8_GENERAL_CI';

		/**
		 * Here we remove each search term from the combined search columns and get the difference in string length, which will tell how many times
		 * the search term was used. By dividing each term by an increasingly larger number, we give greater importance to matching the first term
		 * than the last. The sum of all the string length differences will give us a number by which the search columns can be ranked, with the highest
		 * number being the most relevant. 
		 */
		if ($ranked){
			$i = 1;
			$sumString = 'SUM(';
			
			foreach ($terms as $term){
				$divisor = $i*2 + 2;
				$term = $this->quote(preg_replace('#[%_]#', '', $term));
				$sumString .= ($i > 1) ? ' + ' : NULL;
				$sumString .= "((LENGTH($searchColumns) - LENGTH(REPLACE($searchColumns, $term, '')))/$divisor)";
				$i++;
			}
			$sumString .= ") AS searchOccurrences";
			
			$this->selectList(array($sumString));
			$this->orderBy('searchOccurrences', 'DESC');
			$this->groupBy($columns);
		}
		
		
		$i = 1;
		$numTerms = sizeof($terms);
		foreach ($terms as $term){
			//The first term is always required
			$antecedent = (($type == 'all')||($i == 1)) ? 'AND' : 'OR';
			if (sizeof($this->where) == 0){ //It's the first WHERE statement
				if ($type == 'any'){
					if ($numTerms == 1){
						$this->where($searchColumns, 'LIKE', $term);
					} else {
						$this->where($searchColumns, 'LIKE', $term, NULL, 'open');
					}
				} else {
					$this->where($searchColumns, 'LIKE', $term);
				}
			} else {
				if ($type == 'any'){				
					if (($i == 1) && ($i == $numTerms)){ 
						$this->where($searchColumns, 'LIKE', $term, $antecedent);
					} else if ($i == 1){
						$this->where($searchColumns, 'LIKE', $term, $antecedent, 'open');
					} else if ($i == $numTerms){
						$this->where($searchColumns, 'LIKE', $term, $antecedent, 'close');
					} else {
						$this->where($searchColumns, 'LIKE', $term, $antecedent);
					}
				} else {
					$this->where($searchColumns, 'LIKE', $term, $antecedent);
				}
			}
			$i++;
		}
		
		foreach ($excludedTerms as $term){
			$term = ($caseSensitive) ? $term : preg_replace('#[A-Z]#', '_', $term);
			if (substr($term, 0, 1) == '%'){
				$term = ($caseSensitive) ? $term : '%_'.substr($term, 2);
			} else {
				$term = ($caseSensitive) ? $term : '%'.substr($term, 1);
			}
			if (sizeof($this->where) == 0){
				$this->where($searchColumns, 'NOT LIKE', $term);
			} else {
				$this->where($searchColumns, 'NOT LIKE', $term, 'AND');
			}
		}
		return $this;		
	}
		
	/**
	 * @description Clears all class variables by setting them to NULL, allow class instance reuse
	 * @param boolean $clearBound - optional - Whether or not the bound variables should be cleared, defaults TRUE
	 */
	public function clear($clearBound = TRUE) {
 		$this->valueStorage = array();
		$this->selectList = NULL;
		$this->alias = NULL;
		$this->joinedTables = array();
		$this->joins = NULL;
		$this->where = NULL;
		$this->groupBy = NULL;	
		$this->having = NULL;			
		$this->orderBy = NULL;
		$this->limit = NULL;
		$this->offset = NULL;
		if ($clearBound){
			$this->boundValues = NULL;
			$this->numBoundValues = 0;
		}
	} 

	/**
	 * @description Gets the PDO bound parameter data type.
	 * @param mixed $value - The bound value
	 * @return Returns the PDO bound parameter data type constant. If the value's data type is not a null, a bool, or a 
	 * 		an int, the data type will be returned as a string.
	 */
	protected function getBoundType($value) {
		if (is_null($value)) {
			return \PDO::PARAM_NULL;
		} else if (is_bool($value)) {
			return \PDO::PARAM_BOOL;
		} else if (is_int($value)) {
			return \PDO::PARAM_INT;
		} else {
			return \PDO::PARAM_STR;
		}		
	}

	/**
	 * @description Gets the fields from joined tables and prepends them with the table name. Fields in the current 
	 * 		table will not be prepended.
	 * @param array $tables - The table names to be joined to the current table, either as keys or values or both
	 * @return The object for chaining.
	 * @note The autoSelectList method will automatically change the names of fields from joined tables in order to 
	 * 		prevent overwriting the current table's fields. The name of the table will be made singular and prepended 
	 * 		to the name of the field. The composite name will be camel-cased. For example, if <i>users</i> is the current 
	 * 		table and it is joined to <i>pages</i> table, pages.id will aliased as pageId, pages.name will be pageName, 
	 * 		and so on. Any fields in the users table will not be aliased, so users.id will simply be id, users.name will 
	 * 		remain name, etc. 
	 */
	public function autoSelectList(array $tables){
		if (!$this->selectList){
			$throughTables = array_keys($tables);
			$tables = array_values($tables);
			foreach ($throughTables as $throughTable){
				if (!is_numeric($throughTable)){
					$tables[] = $throughTable;
				}
			}
			
			foreach ($this->fields as $field){
				$this->selectList[] = $this->table.'.'.$field;
			}
			
			foreach ($tables as $table){
				$tableName = '\Db\\'.$table;
				$tableDb = new $tableName($this->db);
				$tableSingular = $tableDb->getSingularName();
				$tableFields = $tableDb->getFields();
				foreach ($tableFields as $field){
					
					$this->selectList[] = $table.'.'.$field.' AS '.$tableSingular.ucfirst($field);
				}
			}
		}
		return $this;
	}
	
	/**
	 * @description A shortcut method for quickly retrieving data from the table
	 * @param mixed $values - Either a single value or an array of values to be found in the specified column.
	 * @param string $column - optional - The name of the column for which data should be matched.  The column must be 
	 * 		present in the table or else an exception will be thrown. Defaults to 'id'.
	 * @param array $joins - optional - An array of table names that should be joined to the returned result. For 
	 * 		simple 2 table joins, enter the joined table name as an array value. If you have a 3 table join with an 
	 * 		intermediary table between the current table and the final table, enter the intermediary table as the array 
	 * 		key and the final table as the array value. See the attached note for important information on naming 
	 * 		conventions.
	 * @return The object for chaining.
	 * @note In order to use the joins parameter of the find method, your database must follow a strict naming pattern. 
	 * 		Database tables must named using plural forms of whatever nouns they represent. Foreign keys refering to
	 * 		the id of any table must be camel-cased and use the singular form of the noun. So, for a table named 
	 * 		'users', any foreign key reffering to the users table must be named 'userId'. <strong>Because VM PHP
	 * 		Framework does not contain a pluralization dictionary, if your noun has irregular pluralization, ie. 
	 * 		'person/people', you will not be able to use the find method and should instead construct your query 
	 * 		manually.</strong>
	 * @note Because the find method is merely a shortcut method that dynamically creates where and join clauses for 
	 * 		simple queries, you can use other methods for any of the other SQL clauses before using the find method to 
	 * 		further refine your queries. The sole exception to this is that you will not be able to use the alias 
	 * 		method or any alias parameters as find relies on full table names rather than aliases.
	 * @note The joins parameter is only for use with the select method and will be ignored when using the find method 
	 * 		in conjuction with delete or update methods.
	 * @note When using the joins parameter, keep in mind that any field names from the joined tables will be altered 
	 * 		in the result set returned from the query. find will automatically run autoSelectList if needed. See the 
	 * 		note on the autoSelectList method for more details.
	 */
	public function find($values, $column = 'id', array $joins = array()){
		if (!in_array($column, $this->fields)){
			throw new Exception("The column '$column' does not exist in $this->table.");
		}
		
		$values = (is_array($values)) ? $values : array($values);
		$numValues = sizeof($values);
		
		if (sizeof($joins) > 0){
			$this->autoSelectList($joins);
		}
		
		foreach ($joins as $throughTable=>$finalTable){
			if (is_numeric($throughTable)){
				$this->autoJoin('left', $finalTable);
			} else {
				$this->autoJoin('left', $finalTable, $throughTable);
			}
		}
		
		for ($i=0; $i<$numValues; $i++){
			if ($i == 0){
				$this->where($this->table.'.'.$column, '=', $values[$i]);
			} else {
				$this->where($this->table.'.'.$column, '=', $values[$i], 'OR');
			}
		}
		return $this;
	}

	/**
	 * @description Retrieves the first row in the table when sorted by id.
	 * @param string $mode - optional - Corresponds to the mode parameter in the select method. See that method for more
	 * 		details. Defaults to 'single'.
	 * @return mixed - The query result set as detailed in the select method.
	 */
	public function first($mode = 'single'){
		$this->limit(1);
		return $this->select($mode);
	}

	/**
	 * @description Retrieves the last row in the table when sorted by id.
	 * @param string $mode - optional - Corresponds to the mode parameter in the select method. See that method for more
	 * 		details. Defaults to 'single'.
	 * @return mixed - The query result set as detailed in the select method.
	 */	
	public function last($mode = 'single'){
		$this->limit(1)->orderBy('id', 'DESC');
		return $this->select($mode);
	}	

	/**
	 * @description A convenience method for retrieving the number of records. Is equivalent to select('count').
	 * @returns int - The number of records for the given query.
	 */
	public function count(){
		return $this->select('count');
	}

	/**
	 * @description Builds the select query string
	 * @param string $selectType - optional - 'DISTINCT' or 'ALL'. Note: $selectType is ignored for the first select 
	 * 		query in a set of unions
	 * @return The select query string 
	 */
	protected function prepareQuery($selectType = NULL){
		if ($selectType){
			$type = ($selectType == 'ALL') ? ' ALL' : ' DISTINCT';
		} else {
			$type = NULL;
		}
		
		$selectList = ($this->selectList) ? ' '.implode(', ', $this->selectList) : ' *';
		$alias = ($this->alias) ? ' AS '.$this->alias : ' AS '.$this->table;
		$joins = ($this->joins) ? implode('', $this->joins) : NULL;
		$where = ($this->where) ? ' WHERE'.implode('', $this->where) : NULL;
		
		if (!$this->groupBy) {
			$groupBy = NULL;
			$having = NULL;
		} else {
			$groupBy = ' GROUP BY '.implode(', ', $this->groupBy);
			$having = ($this->having) ? ' HAVING '.implode('', $this->having) : NULL;
		}
		
		$orderBy = ($this->orderBy) ? ' ORDER BY '.implode(', ', $this->orderBy) : NULL;
		
		if ($this->limit) {
			$limit = ($this->offset) ? ' LIMIT '.$this->offset.', '.$this->limit : ' LIMIT '.$this->limit;
		} else {
			$limit = NULL;
		}
		
		if ((sizeof($this->unions) > 0)&&($mode != 'union')){
			$query = implode(' ', $this->unions)."$orderBy$limit";
		} else {
			$query = "SELECT$type$selectList FROM ".$this->tablePrefix."$alias$joins$where$groupBy$having$orderBy$limit";
		}
		return $query;
	}

	/**
	 * @description Executes the select statement based on the mode.
	 * @param string $mode - The mode of the select results. 
	 * @param PDOStatement $result - The prepared PDOStatement class
	 * @return See the selectm method for more details.
	 */
	protected function executeSelect($mode, \PDOStatement $result){
		$result->execute();
		switch ($mode){
			case "assoc":
				return $result->fetchAll(PDO::FETCH_ASSOC);
			case "count":
				return count($result->fetchAll());
			case "num":
				return $result->fetchAll(PDO::FETCH_NUM);
			case "lazy":
				return $result->fetch(PDO::FETCH_LAZY);
			case "obj":
				return $result->fetchAll(PDO::FETCH_OBJ);
			case "json":
				return json_encode($result->fetchAll(PDO::FETCH_OBJ));
			case "xml":
				$results = $result->fetchAll(PDO::FETCH_ASSOC);
				$xml = new \SimpleXMLElement('<'.$this->table.'/>');
				foreach ($results as $result){
					$table = $xml->addChild($this->tableSingular);
					foreach ($result as $field=>$value){
						$table->addChild($field, $value);
					}
				}
				return $xml->asXML();
			case "single":
				$rows = $result->fetch(PDO::FETCH_ASSOC);
				if (is_array($rows)){
					foreach(array_keys($rows) as $key) {
						$this->valueStorage[$key] = $rows[$key];
					}
				}
				return $rows;
			default:
				return $result->fetchAll(PDO::FETCH_BOTH);
		}		
	}
	
	/**
	 * @description Compiles the given data into a select query and returns a result set based on the query
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
	 * If set to 'json', will return a JSON array of the results.
	 * 
	 * If set to 'xml', will return the results as XML, with the plural table name as the parent element and the 
	 * table name in the singular as a child element for each row. Each singular table element will contain child nodes 
	 * named after the table column with the data as the text node.
	 *
	 * If set to 'subquery', will wrap the query in parantheses and return it for use in a subquery without executing it.
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
	 * @param string $selectType - optional - 'DISTINCT' or 'ALL'. Note: $selectType is ignored for the first select query in a set of unions
	 * @return mixed - The query result set
	 */
	public function select($mode=NULL, $selectType=NULL) {
		$mode = strtolower($mode);
		$result = $this->db->prepare($this->prepareQuery($selectType));
		
		if ((is_array($this->boundValues))&&(!in_array($mode, array('subquery', 'union')))) {
			foreach ($this->boundValues as $name=>$value) {
				$result->bindValue($name, $value, $this->getBoundType($value));
			}
		}
		
		if ($mode == "debug") {
			return preg_replace($this->patterns, $this->getSpacedBoundValues(), $result->queryString, 1);
		} else if ($mode == "subquery") {
			$this->clear(FALSE);
			return '('.$result->queryString.')';
		} else if ($mode == "union") {
			$this->clear(FALSE);
			$this->unions[] = (sizeof($this->unions) >= 1) 
				? "UNION $selectType (".$result->queryString.')' 
				: '('.$result->queryString.')';			
		} else {
			return $this->executeSelect($mode, $result);
		}
	} 
	
	/**
	 * @description The insert function inserts records into the database. Magic methods are used to insert
	 * 	values into each field. Fields that are not assigned a value will not be included in the compiled query.
	 * @example  
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
		$valueTypes = array('array'=>FALSE, 'single'=>FALSE);
		$arrayLength = 0;
		$fieldNames = array();
		$values = array();
		$params = array();
		
		foreach ($this->valueStorage as $name=>$value) {
			$fieldNames[] = $name;
			if (is_array($value)){
				if (!$valueTypes['array']){
					$valueTypes['array'] = TRUE;
				}
				if ($valueTypes['single']) {
					throw new Exception('Insert values must be either arrays of the same length or a single value');
				}	
				$i = 0;
				foreach ($value as $inputValue){
					$params[$i][] = '?';
					$values[$i][] = $inputValue;
					$i++;
				}
			} else {
				if (!$valueTypes['single']){
					$valueTypes['single'] = TRUE;
				}
				if ($valueTypes['array']) {
					throw new Exception('Insert values must be either arrays of the same length or a single value');
				}
				$params[0][] = '?';
				$values[0][] = $value;
			}
		}
		$numInserts = 0;
		foreach ($params as $count=>$value){
			$params[$count] = '('.implode(',', $value).')';
			$numInserts += 1;
		}
		$params = implode(',', $params);
		
		$numValues = sizeof($values[0]);
		$boundValues = array();
		foreach ($values as $value){
			if (sizeof($value) != $numValues){
				throw new Exception('Insert values must be either arrays of the same length or a single value');
			}
			foreach ($value as $boundValue){
				$boundValues[] = $boundValue;
			}
		}
		
		$query = 'INSERT INTO '.$this->tablePrefix.' ('.implode(',', $fieldNames).') VALUES '.$params;
		$result = $this->db->prepare($query);

		if (strtolower($mode) == 'debug') {
			$patterns = array();
			foreach ($boundValues as $value){
				$patterns[] = '#\?#';
			}
			return preg_replace($patterns, $boundValues, $result->queryString, 1);
		} else {
			$i = 0;
			foreach ($boundValues as $value){
				$boundValues[$i] = $boundValues[$i];
				$result->bindValue(($i+1), $boundValues[$i], $this->getBoundType($value));
				$i++;
			}
			$result->execute();
			return $this->db->lastInsertId() + $numInserts - 1;
		}
	} 

	/**
	 * @description Updates a database field with values obtained from magic methods representing the field names. 
	 * @note Multiple table updates are currently not supported, nor are ordering or limiting result sets due to
	 * 	DBMS syntax inconsistencies
	 * @param string $mode - optional - If set to 'debug', returns the compiled SQL query
	 * @return int - The number of affected rows 
	 */
	public function update($mode=NULL) {
		$fields = array();
		$boundValues = array();
		
		foreach (array_keys($this->valueStorage) as $field) {
			$fields[]= "$field=";
		}
		$parameters = implode("?, ", $fields).'?';

		$i = 1;
		foreach (array_keys($this->valueStorage) as $field){
			$boundValues[$i]= $this->valueStorage[$field];
			$i++;
		}
		
		$where = ($this->where) ? ' WHERE'.implode('', $this->where) : NULL;
		$where = preg_replace('#(\s:[\w-]+[\s]?)#', ' ? ', $where);
		$boundValues = array_merge($boundValues, array_values($this->boundValues));
		$boundValues = $boundValues;
		
		$query = "UPDATE ".$this->tablePrefix." SET $parameters$where";
		$result = $this->db->prepare($query);
				
		if (strtolower($mode) == 'debug') {
			$patterns = array();
			foreach ($boundValues as $value){
				$patterns[] = '#\?#';
			}
			return preg_replace($patterns, $boundValues, $result->queryString, 1);
		} else {
			$i = 0;
			foreach ($boundValues as $value){
				$boundValues[$i] = $boundValues[$i];
				$result->bindValue($i+1, $boundValues[$i], $this->getBoundType($value));
				$i++;
			}
			$result->execute();
			return $result->rowCount();
		}
	} 
	
	/**
	 * @description The delete function deletes all rows that meet the conditions specified in the where clause
	 * 	and returns the number of affected rows
	 * @param string $mode - optional - Acceptable value is 'debug', which prints the compiled query
	 */
	public function delete($mode=NULL) {
		$where = ($this->where) ? ' WHERE'.implode('', $this->where) : NULL;
		$query = 'DELETE FROM '.$this->tablePrefix.$where;
		$result = $this->db->prepare($query);
		
		if (strtolower($mode) == 'debug') {
			return preg_replace($this->patterns, $this->getSpacedBoundValues(), $result->queryString, 1);
		} else {
			foreach ($this->boundValues as $name=>$value) {
				$result->bindValue($name, $value, $this->getBoundType($value));
			}
			$result->execute();
			return $result->rowCount();
		}
	}

	/**
	 * @description Deletes all rows in the table, returns the number of affected rows.
	 * @return int - The number of affected rows
	 */
	public function deleteAll(){
		$result = $this->db->prepare('DELETE FROM '.$this->tablePrefix);
		$result->execute();
		return $result->rowCount();
	} 
}