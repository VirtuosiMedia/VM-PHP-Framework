<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Creates a database table from an XML file using DbOperations
 * @namespace Vm\Db
 */
namespace Vm\Db;

class FromXml extends \Vm\Db\Factory\Ddl {

	/**
	 * @description Installs the table into the database if it doesn't already exist 
	 * @param object $table - A simpleXML object representing the table
	 */
	protected function installStructure($table){
		foreach ($table->structure->columns->column as $column){
				if ((string) $column->extra == 'auto_increment'){
					$this->setPrimaryKey((string)$column['name']);
				} else {
					switch (strtolower((string) $column['datatype'])) {
						case 'smallint':
							$this->addSmallInt((string) $column['name'], (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'int':
							$this->addInt((string) $column['name'], (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;	
						case 'bigint':
							$this->addBigInt((string) $column['name'], (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'float':
							$this->addReal((string) $column['name'], (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'real':
							$this->addReal((string) $column['name'], (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;							
						case 'double':
							$this->addDouble((string) $column['name'], (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'decimal':
							$this->addDecimal((string) $column['name'], (string) $column->value1, (string) $column->value2, (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'numeric':
							$this->addNumeric((string) $column['name'], (string) $column->value1, (string) $column->value2, (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'char':
							$this->addChar((string) $column['name'], (string) $column->value1, (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'varchar':
							$this->addVarchar((string) $column['name'], (string) $column->value1, (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'text':
							$this->addText((string) $column['name'], (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'date':
							$this->addDate((string) $column['name'], (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'timestamp':
							$this->addTimestamp((string) $column['name'], (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
						case 'blob':
							$this->addBlob((string) $column['name'], (string) $column->value1, (string) $column['null'], (string) $column->default, (string) $column->comment);
							break;
					}
				}
			}
			
			$uniqueKeys = array();
			$foreignKeys = array();
			foreach ($table->structure->keys->key as $key){
				if ((string) $key['type'] == 'unique'){
					$uniqueKeys[] = (string) $key;
				} else if ((string) $key['type'] == 'foreign'){
					$foreignKeys[] = (string) $key;
				}
			}
			$this->unique($uniqueKeys);
			$this->foreignKey($foreignKeys);
			
			//TODO: Add Table comments, collation
			$this->createTable((string)$table['name'], (string)$table['schema']);		
	}
	
	/**
	 * @description Adds data to the database 
	 * @param object $table - A simpleXML object representing the table
	 */
	protected function installData($table){
		$data = array();
		foreach ($table->data->rows->row as $row){
			foreach ($row->field as $field){
				$name = (string) $field['name'];
				$data[$name][] = (string) $field;
			}
		}

		$columns = $this->showColumns((string)$table['name']);
		$fields = array();
		foreach ($columns as $column){
			$fields[] = $column['Field'];
		}
		$db = new DbObject($this->db, (string)$table['name'], $fields, (string)$table['schema'], $this->prefix);
		foreach($fields as $field){
			$db->$field = $data[$field];
		}
		$db->insert();
	}
	
	/**
	 * @description Creates a database table based on an xml database file
	 * @param mixed $fileNames - The name of the xml database file with relative path included as a string, else an array
	 * 	of file names and paths
	 * @param string $mode - optional - 'structure', 'data', or 'both', defaults to 'both' 
	 */
	public function install($fileNames, $mode='both'){
		$fileNames = (is_array($fileNames)) ? $fileNames : array($fileNames);
		foreach ($fileNames as $fileName){
			$xml = new SimpleXMLElement($fileName, NULL, TRUE);
			foreach ($xml->table as $table) {
				if ((strtolower($mode) == 'structure')||(strtolower($mode) == 'both')){
					$this->installStructure($table);
				} 				
				if ((strtolower($mode) == 'data')||(strtolower($mode) == 'both')){
					$this->installData($table);
				} 
			}
		}
	}
}