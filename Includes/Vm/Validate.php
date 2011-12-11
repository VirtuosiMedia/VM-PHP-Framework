<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A basic validation class that constructs validators and collects errors
 * @namespace Vm
 */
namespace Vm;

class Validate extends Klass {

	protected $allErrors = array();
	protected $inputErrors = array();	

	/**
 	 * @description Protected method: Updates error and input values and checks if $input should be auto-santitized
	 * @param string $fieldName - The field name to be sanitized
	 */	
	protected function update($validator, $fieldName){	
		if (!$validator->validates()) {
			$this->inputErrors[$fieldName][] = $validator->getError();
			$this->allErrors[] = $validator->getError();
		}
	}	

	/**
	 * @description Validates an input against a validator
	 * @param string $fieldName - The field name to be validated
	 * @param string $input - The input to be validated
	 * @param string $validatorName - The name of the validator to be used
	 * @param array $error - optional - If no custom error message is given, then the default validator message is 
	 * 		returned.
	 * @param mixed $param1 - optional - If the validator calls for an additional parameter, include it here
	 * @param mixed $param1 - optional - If the validator calls for a second additional parameter, include it here
	 */
	public function addValidator($fieldName, $input, $validatorName, $error = NULL, $param1 = NULL, $param2 = NULL){
		$validatorName = 'Vm\Validate\\'.$validatorName;
		if (($param1) && ($param2)) {
			$validator = new $validatorName($input, $error, $param1, $param2); 
		} else if ($param1) {
			$validator = new $validatorName($input, $error, $param1); 
		} else {
			$validator = new $validatorName($input, $error); 
		}
		$this->update($validator, $fieldName);
	}

	/**
	 * @description Validates an input against an array of validators
	 * @param string $fieldName - The field name to be validated
	 * @param string $input - The input to be validated
	 * @param array $validators - The validators to validate the input against, with the validator as the key, the 
	 * 		custom error message as the value. If no custom error message is given, then a default message is returned. 
	 * 		If the validator includes additional parameters, the value should be an array with the first item as the 
	 * 		error message, the second as the first additional paramter, and the third as an optional second parameter. 
	 * 		Note: A validator cannot be named after an integer
	 */
	public function addValidators($fieldName, $input, array $validators){
		foreach ($validators as $key => $value) {
			$validatorName = (is_int($key)) ? $value : $key;
			if ((is_array($value)) && (!is_int($key))) {
				$validatorError = $value[0]; 
				$param1 = $value[1];
				$param2 = ($value[2]) ? $value[2] : NULL;
			} else if (!is_int($key)) {
				$validatorError = $value;
				$param1 = NULL;
				$param2 = NULL;
			} else {
				$validatorError = NULL;
				$param1 = NULL;
				$param2 = NULL;
			}
			$this->addValidator($fieldName, $input, $validatorName, $validatorError, $param1, $param2);
		}	
	}

	/**
	 * @param string $fieldName - The name of the field to which the error should be added
	 * @param string $error - A custom error message to be returned if the input fails validation	
	 */	
	public function addError($fieldName, $error){
		$this->inputErrors[$fieldName][] = $error;
		$this->allErrors[] = $error;
	}
	
	/**
	 * @param string $fieldName - The name of the field for which an array of errors should be returned
	 * @return array - An array of errors for the field name
	 */	
	public function getErrors($fieldName){
		return (isset($this->inputErrors[$fieldName])) ? $this->inputErrors[$fieldName] : array();
	}
	
	/**
	 * @return array - An array of errors for all fields
	 */	
	public function getAllErrors(){
		return ($this->allErrors) ? $this->allErrors : array();
	}
	
	/**
	 * @param string $fieldName - optional - If a field name is specified, function checks if errors exist for that 
	 * 		field, else it will check all fields
	 * @return boolean - Returns TRUE if errors exist, FALSE if they do not
	 */	
	public function errorsExist($fieldName = NULL){
		$errors = (($fieldName)&&(isset($this->inputErrors[$fieldName]))) 
			? $this->inputErrors[$fieldName] 
			: $this->allErrors;
		return (sizeof($errors) > 0) ? TRUE : FALSE;
	}	
}