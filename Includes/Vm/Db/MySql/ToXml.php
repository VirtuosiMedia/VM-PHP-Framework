<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Creates an XML file based on a database table structure (not contents) using DbOperations
 * @extends Vm\Db\MySql\Ddl
 * @namespace Vm\Db\MySql
 */
namespace Vm\Db\MySql;

class ToXml extends \Vm\Db\MySql\Ddl {

	protected $xml;

	/**
	 * @description Creates an XML snippet for the table structure of the given table 
	 * @param string $tableName - The name of the table for which a snippet should be created
	 * @return string - The XML snippet
	 */
	protected function getTableStructure($tableName){
		$primaryKey = '';
		$columns = '';			
		$dbColumns = $this->showColumns($tableName);
		
		foreach ($dbColumns as $dbColumn){
			$column = '';		
			
			$datatype = preg_split('#\(#', $dbColumn['Type']);
			
			if (isset($datatype[1])){
				$value = preg_replace('#\)#', '', $datatype[1]);
				$value = preg_split('#,#', $value);
				$column .= $this->xml->value1($value[0]);
				if (isset($value[1])){
					$column .= $this->xml->value2($value[1]);
				}
			}
			
			$column .= ($dbColumn['Default']) ? $this->xml->default($dbColumn['Default']) : NULL;
			$column .= ($dbColumn['Extra']) ? $this->xml->extra($dbColumn['Extra']) : NULL;
			$column .= ($dbColumn['Comment']) ? $this->xml->comment($dbColumn['Comment']) : NULL;

			if ($dbColumn['Key'] == 'PRI'){
				$primaryKey = $this->xml->key($dbColumn['Field'], array('type'=>'primary'));
			} 
			
			$null = ($dbColumn['Null'] == 'NO') ? 'false' : 'true';
			
			$columns .= $this->xml->column($column, array(
				'name'=>$dbColumn['Field'],
				'datatype'=>$datatype[0],
				'null'=>$null,
				'collation'=>$dbColumn['Collation']
			));
		}
		$columns = $this->xml->columns($columns);
		
		$uniques = '';
		$foreigns = '';
		
		$rows = $this->showIndex($tableName);
		foreach ($rows as $row){
			if (($row['Non_unique'] == 0) && ($row['Key_name'] != 'PRIMARY')){
				$uniques .= $this->xml->key($row['Column_name'], array('type'=>'unique'));
			} else if ($row['Non_unique'] == 1) {
				$foreigns .= $this->xml->key($row['Column_name'], array('type'=>'foreign'));
			}
		}
		
		$keys = $this->xml->keys($primaryKey.$uniques.$foreigns);
		
		return $this->xml->structure($columns.$keys);
	}

	/**
	 * @description Creates an XML snippet for the table data of the given table 
	 * @param string $tableName - The name of the table for which a snippet should be created
	 * @return string - The XML snippet
	 */	
	protected function getTableData($tableName){
		$dbColumns = $this->showColumns($tableName);
		$fields = array();
		foreach ($dbColumns as $dbColumn){
			$fields[] = $dbColumn['Field'];
		}
		$numFields = sizeof($fields);
		
		$db = new \Vm\Db\MySql\Dml($this->db, $tableName, $fields, 'public', $this->prefix);
		$rows = $db->select();
		$rowsContent = '';
		
		foreach($rows as $row){
			$rowContent = '';
			for ($i = 0; $i < $numFields; $i++){
				$rowContent .= $this->xml->field($row[$fields[$i]], array('name'=>$fields[$i])); 	
			}
			$rowsContent .= $this->xml->row($rowContent);
		}
		
		return $this->xml->data($this->xml->rows($rowsContent));
	}
	
	/**
	 * @description Creates an XML file representing the given table's structure 
	 * @param mixed $tableNames - The names of the tables as a string or an array of strings  
	 * @param string $fileName - optional - The name of the new file, complete with the relative path and with extension
	 * @param string $mode - optional - 'structure', 'data', or 'both', defaults to 'both' 
	 * @return mixed - The xml file as a string only if $fileName is NULL, otherwise returns boolean on file creation
	 */
	public function render($tableNames, $fileName = NULL, $mode = 'both'){
		$tableNames = (is_array($tableNames)) ? $tableNames : array($tableNames);
		$this->xml = new \Vm\Xml(FALSE);
		$fileContents = '<tables>';
		foreach ($tableNames as $tableName){
			$tableStatus = $this->showTableStatus($tableName);
			$fileContents .= '<table name="'.$tableName.'" schema="public" collation="'.$tableStatus[0]['Collation'].'">';
			$fileContents .= $this->xml->comments($tableStatus[0]['Comment']);
			if (strtolower($mode) == 'structure'){
				$fileContents .= $this->getTableStructure($tableName);
			} else if (strtolower($mode) == 'data'){
				$fileContents .= $this->getTableData($tableName);
			} else {
				$fileContents .= $this->getTableStructure($tableName);
				$fileContents .= $this->getTableData($tableName);
			}
			$fileContents .= '</table>';
		}
		$fileContents .= '</tables>';
		
		$file = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'.$fileContents);
		return ($fileName) ? $file->asXML($fileName) : $file->asXML();
	}
}