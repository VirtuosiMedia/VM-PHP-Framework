<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description An abstract model class
 * @requirements PHP 5.2 or higher
 */
abstract class Vm_Model {
	
	protected $viewData = array();
	
	/**
	 * Sets the data to be passed into the view
	 * @param string $name - The name of the view variable as a string
	 * @param mixed $value - The value of the view variable
	 */
	protected function setData($name, $value){
		$this->viewData[$name] = $value;
	}
	
	/**
	 * Gets all of the model's data to be passed directly into the view using Vm_View::map()
	 * @return array - An associative array of key/value pairs, with the key being the name of the view variable, the value being it's value
	 */
	public function getViewData(){
		return $this->viewData;
	}
}