<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A generic validator class
 * @requirements PHP 5.2 or higher
 * @namespace Vm
 */
namespace Vm;

abstract class Validator {

	protected $error = NULL;
	protected $validates = TRUE;
	
	/**
	 * Sets the error message and sets validatation to FALSE
	 * @param string $error - The error to be set
	 */
	public function setError($error){
		$this->error = $error;
		$this->validates = FALSE;
	}
	
	/**
	 * @return boolean - TRUE if the validator validates, FALSE otherwise
	 */
	public function validates() {
		return $this->validates;
	}
	
	/**
	 * @return string - The error message
	 */
	public function getError(){
		return $this->error;
	}
}