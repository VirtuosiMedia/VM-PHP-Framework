<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: An array mapping class
* Requirements: PHP 5.2 or higher
*/
class Vm_Map extends Vm_Klass {

	protected $map = array();
	
	/**
	 * @param array $map - optional - An array for mapping
	 */
	public function __construct($map=array()){
		$this->setMap($map);
	}

	/**
	 * @param array $map - An array for mapping column names to an alias, for security reasons so the database column name doesn't
	 *	appear in the URL - The alias is the key, the column name is the value
	 */
	public function setMap(array $map){
		$this->map = $map;
		return $this;		
	}

	/**
	 * Description - Clears the map
	 * @param string $key - The array key
	 */
	public function clear($key = NULL){
		if ($key) {
			unset($this->map[$key]);		
		} else {
			$this->map = array();
		}
	}

	/**
	 * Description - Sets a specific $key=>$value pair
	 * @param mixed $key - The array key
	 * @param mixed $value - The value for the key
	 */	
	public function set($key, $value){
		$this->map[$key] = $value;
	}
	
	/**
	 * @param string $key - Gets the value for the map key
	 * @return - The value for the $key if it exists, FALSE otherwise 
	 */
	public function getValue($key){
		return (isset($this->map[$key])) ? $this->map[$key] : FALSE;
	}
	
	/**
	 * @param string $value - Gets the value for the map key alias
	 * @return - The key for the given value if it exists, FALSE otherwise
	 */
	public function getKey($value){
		$key = array_keys($this->map, $value);		
		return (sizeof($key) > 0) ? $key[0] : FALSE;
	}	
}
?>