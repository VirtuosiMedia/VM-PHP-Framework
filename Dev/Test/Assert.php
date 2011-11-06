<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A base class for test assertions, meant to be extended by individual assertion classes
*/
abstract class Test_Assert {

	protected $error;
	protected $result;
	
	/**
	 * @return boolean - TRUE if the test passed, FALSE otherwise
	 */
	public function getResult(){
		return $this->result;
	}

	/**
	 * @param bool $result - The result to set for the assertion
	 */
	public function setResult($result = FALSE){
		$this->result = $result;
	}	
	
	/**
	 * @return mixed - The error as a string if it exists, FALSE if no error exists
	 */
	public function getError(){
		return ($this->result === TRUE) ? FALSE : $this->error;
	}
	
	/**
	 * @param string $error - The error to set for the assertion
	 */
	public function setError($error){
		$this->error = $error;
	}
}
?>