<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for creating forms
 * @requirements: PHP 5.3 or higher
 * @namespace Vm\Form
 * @uses Vm\Form
 */
namespace Vm\Form;

use \Vm\Form\Exception;

abstract class Model {

	protected $db;
	protected $form;
	protected $formAttributes;
	protected $formOptions;
	protected $formType;
	
	/**
	 * @param string $formType - The type of form to render and process. Accepts 'create', 'update', and 'destroy'.
	 * @param PDO $db - optional - The PDO connection, if it is needed for the form
	 * @throws \Vm\Form\Exception
	 */
	function construct($formType, $db = NULL){
		$this->db = $db;
		$this->formType = strtolower($formType);
	}

	/**
	 * @param array $formAttributes - The HTML attributes of the form as an associative array of attribute/value pairs
	 */
	public function setFormAttributes(array $formAttributes){
		$this->formAttributes = $formAttributes;
	}

	/**
	 * @param array $formAttributes - The HTML attributes of the form as an associative array of attribute/value pairs
	 */
	public function setFormOptions(array $formOptions){
		$this->formOptions = $formOptions;
	}	
	
	/**
	 * @description Renders a form based on the form type passed into the constructor
	 * @returns The rendered form as a string
	 */
	public function render(){
		$this->form = new Form($this->formAttributes, $this->formOptions);
		if ($this->formType == 'create'){
			$this->createForm();
		} else if ($this->formType == 'update'){
			$this->updateForm();
		} else if ($this->formType == 'delete'){
			$this->deleteForm();
		} else {
			$this->defaultForm();
		}
		return $this->form->render();		
	}

	/**
	 * @description Processes a form based on the form type passed into the constructor
	 * @returns mixed - The return
	 */	
	public function process(){
		if ($this->form->submitted() && (!$this->form->errorsExist())){
			if ($this->formType == 'create'){
				return $this->processCreate();
			} else if ($this->formType == 'update'){
				return $this->processUpdate();
			} else if ($this->formType == 'delete'){
				$this->processDelete();
			} else {
				$this->processDefault();
			}
		}
	}

	/**
	 * @description A placeholder method which should be overwritten by the extending class and contain a form that
	 * 		does not create, update, or delete a resource
	 */
	protected function defaultForm(){
		throw new Exception("The defaultForm method has not been overwritten and cannot be executed.");
	}	
	
	/**
	 * @description A placeholder method which should be overwritten by the extending class and contain the form that
	 * 		creates a resource
	 */ 	
	protected function createForm(){
		throw new Exception("The createForm method has not been overwritten and cannot be executed.");
	}

	/**
	 * @description A placeholder method which should be overwritten by the extending class and contain the form that
	 * 		updates a resource
	 */	
	protected function updateForm(){
		throw new Exception("The updateForm method has not been overwritten and cannot be executed.");
	}

	/**
	 * @description A placeholder method which should be overwritten by the extending class and contain the form that
	 * 		deletes a resource
	 */	
	protected function deleteForm(){
		throw new Exception("The deleteForm method has not been overwritten and cannot be executed.");
	}	

	/**
	 * @description A placeholder method which should be overwritten by the extending class and process a form that
	 * 		does not create, update, or delete a resource
	 */
	protected function processDefault(){
		throw new Exception("The processDefault method has not been overwritten and cannot be executed.");
	}	
	
	/**
	 * @description A placeholder method which should be overwritten by the extending class and process the form that
	 * 		creates a resource
	 */	
	protected function processCreate(){
		throw new Exception("The processCreate method has not been overwritten and cannot be executed.");
	}	

	/**
	 * @description A placeholder method which should be overwritten by the extending class and process the form that
	 * 		updates a resource
	 */	
	protected function processUpdate(){
		throw new Exception("The processUpdate method has not been overwritten and cannot be executed.");		
	}

	/**
	 * @description A placeholder method which should be overwritten by the extending class and process the form that
	 * 		deletes a resource
	 */	
	protected function processDelete(){
		throw new Exception("The processDelete method has not been overwritten and cannot be executed.");		
	}
}