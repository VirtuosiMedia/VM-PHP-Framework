<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A base class for active record
 * @requires A database connection script that uses a PDO extension
 * @namespace Vm\Db
 * @uses Vm\Db\Exception
 * @uses PDO
 */
namespace Vm\Db;

use \Vm\Db\Exception;
use \PDO;

class ActiveRecord {

	protected $db;
	protected $dbTable;
	protected $errors = array();
	protected $errorsExist = FALSE;
	protected $fields = array();
	protected $joinedTables = array();
	protected $relationships;
	protected $relationshipTables = array();
	protected $resultSet;
	protected $table;
	protected $updatedTables = array();
	protected $validations;
	
	function __construct(PDO $db, $table, array $validations = array(), array $relationships = array()){
		$this->db = $db;
		$this->table = $table;
		$this->validations = $validations;
		$this->relationships = $relationships;
		
		$dbTableName = '\Db\\'.$table;
		$this->dbTable = new $dbTableName($this->db);
		$this->fields = $this->dbTable->getFields();
		
		foreach($relationships as $relationship=>$tables){
			foreach ($tables as $throughTable=>$finalTable){
				if ((!is_numeric($throughTable)) && (in_array($relationship, array('hasMany', 'belongsTo')))){
					$throughName = '\Db\\'.$throughTable;
					$this->relationshipTables[$throughTable] = new $throughName($this->db);
				}
				
				$finalName = '\Db\\'.$finalTable;
				$this->relationshipTables[$finalTable] = new $finalName($this->db);					
			}
		}
	}

	/**
	 * @description Gets the passed-in field if it exists in the table or if it exists in any tables belonging to the 
	 * 	table.
	 * @param string $name - The name of the field for which a value should be retrieved
	 * @throws Exception
	 * @return The value of the field.
	 */
	function __get($name){
		if ((in_array($name, $this->fields)) || (in_array($name, array_keys($this->relationshipTables)))){
			return $this->resultSet->$name;
		} else {
			throw new Exception("'$name' is not a field in the $this->table table or in any of the fields that 
				belong to it.");
		}
	}
	
	function __set($name, $value){
		if (in_array($name, $this->fields)){
			$this->dbTable->$name = $value;
		} else if (in_array($name, array_keys($this->relationshipTables))){
			$this->relationshipTables[$name] = $value;
			$this->updatedTables[] = $name;
		}
	}

	/**
	 * @description Allows one to use most of the query construction methods in Vm\Db\Factory\Dml as well as dynamic 
	 * 		method name construction. See the example for more details.
	 * @example In addition to the query construction methods in Vm\Db\Factory\Dml, the following magic methods are 
	 * 		allowed:
	 * 		findBy{fieldName}[And{fieldName},And{fieldName},..]
	 * 		findOneBy{fieldName}[And{fieldName},And{fieldName},..]
	 * 		findAllBy{fieldName}[And{fieldName},And{fieldName},..]		
	 * 		find{int}By{fieldName}[And{fieldName},And{fieldName},..]
	 * 		findFirstBy{fieldName}[And{fieldName},And{fieldName},..]
	 * 		findLastBy{fieldName}[And{fieldName},And{fieldName},..]		
	 * @param string $name - The name of the method
	 * @param array $arguments - The arguments will vary depending on the method called
	 * @return \Vm\Db\ActiveRecord
	 */
	function __call($name, $arguments){
		$dbMethods = array('selectList', 'quote', 'join', 'leftJoin', 'rightJoin', 'innerJoin', 'fullJoin', 'where',
			'groupBy', 'having', 'orderBy', 'limit', 'offset', 'clear', 'first', 'last', 'count');
		
		if (in_array($name, $dbMethods)){
			call_user_func_array(array($this->dbTable, $name), $arguments);
			return $this;
		} else {
			return $this->magicFind($name, $arguments);	
		}
	}
	
	/**
	 * @description A compilation method for __call
	 * @param string $name - The name of the method
	 * @param array $arguments - The arguments will vary depending on the method called
	 * @throws Exception
	 */
	protected function magicFind($name, $arguments){
		$validCommands = array('find', 'findFirst', 'findLast', 'findOne', 'findAll');
		list($command, $fields) = explode('By', $name);
		
		if (!in_array($command, $validCommands)){
			$limit = ltrim($command, 'find');
			if (!is_numeric($limit)){
				throw new Exception("$name is not a valid method.");
			} else {
				$this->dbTable->limit((int) $limit);
			}
		}
		
		$fields = explode('And', $fields);
		
		if (sizeof($fields) != sizeof($arguments)){
			throw new Exception("The number of fields and arguments must match for the $name method.");
		}

		$wheres = array_combine($fields, $arguments);
		
		$i = 0;
		foreach ($wheres as $field=>$value){
			$field = lcfirst($field);
			if (!in_array($field, $this->fields)){
				throw new Exception("The $field field is not found in the $this->table table. Dynamic find queries can 
					only use fields found in the object table.");
			}
			
			if ($i == 0){
				$this->dbTable->where($this->table.'.'.$field, '=', $value);
				$i++;
			} else {
				$this->dbTable->where($this->table.'.'.$field, '=', $value, 'AND');
			}
		}

		foreach ($this->relationships['hasOne'] as $joinedTable){
			$this->dbTable->leftJoin($joinedTable);
		}

		$this->joinedTables = $this->dbTable->getJoinedTables();
		$this->dbTable->autoSelectList(array_merge($this->relationships['hasOne'], $this->dbTable->getJoinedTables()));
		
		switch($command){
			case 'findFirst':
				$this->resultSet = $this->dbTable->first();
				$this->objectifyJoinedResults();
				return $this->resultSet;
			case 'findLast':
				$this->resultSet = $this->dbTable->last();
				$this->objectifyJoinedResults();
				return $this->resultSet;
			case 'findOne':
				$this->dbTable->limit(1);
				$this->resultSet = $this->dbTable->select('single');
				$this->objectifyJoinedResults();
				return $this->resultSet;
			case 'find1':
				$this->resultSet = $this->dbTable->select('single');
				$this->objectifyJoinedResults();
				return $this->resultSet;
			default: //findAll is included here
				$this->resultSet = $this->dbTable->select('assoc');
				$this->objectifyJoinedResults();
				return $this->resultSet;
		}
	}

	/**
	 * @description Turns the results into a stdClass object, with joined tables belonging to the current table being
	 * 	returned as a sub-object.
	 */
	protected function objectifyJoinedResults(){
		$joinedTableNames = array();

		foreach ($this->joinedTables as $joinedTable){
			if (isset($this->relationshipTables[$joinedTable])){
				$singularName = $this->relationshipTables[$joinedTable]->getSingularName();
			} else {
				$tableName = '\Db\\'.$joinedTable;
				$tableDb = new $tableName($this->db);
				$singularName = $tableDb->getSingularName();
			}
			$joinedTableNames[$singularName] = $joinedTable;
		}
		
		if (isset($this->resultSet[0])){ //This indicates a result set that has been returned as 'both'
			$numResults = sizeof($this->resultSet);
		
			for ($i=0; $i < $numResults; $i++){
				foreach ($joinedTableNames as $singularName=>$pluralName){
					$this->resultSet[$i][$pluralName] = new \stdClass();
				}
				foreach ($this->resultSet[$i] as $field=>$value){
					if ((!in_array($field, $this->fields)) && (!in_array($field, $joinedTableNames))){
						$name = preg_replace('/([a-z0-9])?([A-Z])/','$1 $2', $field, 1); //Split based on caps
						list($singularName, $field) = explode(' ', $name);
						$field = lcfirst($field);
						$pluralName = $joinedTableNames[$singularName];
						$this->resultSet[$i][$pluralName]->$field = $value;
					}
				}
				$this->resultSet[$i] = (object) $this->resultSet[$i];				
			}
		} else {
			foreach ($joinedTableNames as $singularName=>$pluralName){
				$this->resultSet[$pluralName] = new \stdClass();
			}
			foreach ($this->resultSet as $field=>$value){
				if ((!in_array($field, $this->fields)) && (!in_array($field, $joinedTableNames))){
					$name = preg_replace('/([a-z0-9])?([A-Z])/','$1 $2', $field, 1); //Split based on caps
					list($singularName, $field) = explode(' ', $name);
					$field = lcfirst($field);
					$pluralName = $joinedTableNames[$singularName];
					$this->resultSet[$pluralName]->$field = $value;
				}
			}
			$this->resultSet = (object) $this->resultSet;
		}
	}
	
	protected function clearErrors(){
		$this->errors = array();
	}

	protected function runValidations(){
		
	}

	/**
	 * @description Creates a new record for the object table. Will also create new records for any table that has been 
	 * 		listed as a	hasOne relationship. If the current table belongs to one or more tables, the table names and 
	 * 		owning ids must be passed in as a parameter or an exception will be thrown.
	 * @param array $ownerIds - optional - The names of any tables listed in the belongsTo relationships as keys, the 
	 * 		owning entity ids as values. Ex: If pages belong to users, you would pass in array('users'=>12), with 12 
	 * 		being the user id that owns the page just created.
	 */
	public function create(array $ownerIds = array()){
		$this->clearErrors();
		$this->runValidations();
		
		if ($this->errorsExist){
			return FALSE;
		}
		
		foreach ($ownerIds as $ownerTable=>$ownerId){
			$ownerField = rtrim($ownerTable, 's').'Id';
			if ((in_array($ownerTable, $this->relationships['belongsTo'])) && (in_array($ownerField, $this->fields))){
				$this->dbTable->$ownerField = $ownerId;
			}
		}
		
		$id = $this->dbTable->insert();
		$currentTableName = rtrim($this->table, 's').'Id';
		
		foreach ($this->updatedTables as $table){
			if (in_array($currentTableName, $this->relationshipTables[$table]->getFields())){
				if (in_array($table, $this->relationships['hasOne'])){
					$this->relationshipTables[$table]->$currentTableName = $id;
					$this->relationshipTables[$table]->insert();
				} else if (in_array($table, array_keys($this->relationships['belongsTo']))){
					if (isset($ownerIds[$table])){
						$ownerField = rtrim($ownerTable, 's').'Id';
						$this->relationshipTables[$table]->$currentTableName = $id;
						$this->relationshipTables[$table]->$ownerField = $ownerIds[$table];
						$this->relationshipTables[$table]->insert();
					} else {
						throw new Exception("The $table table has been listed as owning the $this->table table and must
							be included in an array as a parameter to the create method with the table name as the 
							array key and the id of the owning row as a value");
					}
				}
			}
		}
		
		return $id;
	}

	public function find(){
		//hasOne
		//hasMany
		//belongsTo
		return $this;
	}
	
	public function update(){
		//hasOne
		
		foreach ($this->updatedTables as $table){
			if (in_array($currentTableName, $this->relationshipTables[$table]->getFields())){
				if (in_array($table, $this->relationships['hasOne'])){
					$this->relationshipTables[$table]->$currentTableName = $id;
					$this->relationshipTables[$table]->insert();
				} else if (in_array($table, array_keys($this->relationships['belongsTo']))){
					if (isset($ownerIds[$table])){
						$ownerField = rtrim($ownerTable, 's').'Id';
						$this->relationshipTables[$table]->$currentTableName = $id;
						$this->relationshipTables[$table]->$ownerField = $ownerIds[$table];
						$this->relationshipTables[$table]->insert();
					} else {
						throw new Exception("The $table table has been listed as owning the $this->table table and must
								be included in an array as a parameter to the create method with the table name as the
								array key and the id of the owning row as a value");
					}
				}
			}
		}		
		
		return $this;
	}
	
	public function delete(){
		//hasOne
		//hasMany
		//belongsToThrough
		return $this;
	}
}