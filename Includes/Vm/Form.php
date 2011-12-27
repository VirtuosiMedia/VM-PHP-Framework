<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A form rendering class that extends Validate_Validator and uses the same options in its constructor.
 * @requires Vm\Validate\Validator 
 * @requires Vm\Xml
 * @note Vm\Form generates but does not enforce valid HTML.
 * @namespace Vm
 * @uses Vm\Xml
 * @extends Vm\Validate
 * @extends Vm\Klass
 */
namespace Vm;

use Vm\Xml;

class Form extends Validate {

	//@var array $fieldValues - An array of raw, unfiltered field values for each field
	protected $fieldValues = array();	

	//@var array $filteredFieldValues - An array of filtered field values for each field
	protected $filteredFieldValues = array();	
	
	// @var array $formAttributes - An array of each attribute/value pair for the form
	protected $formAttributes = array(
		'method' => 'post'
	);

	// @var string $formRender - The compiled form for rendering
	protected $formRender = '';

	/**
	 * @var array $radioFields - An array of radio element field names that have errors so that the error won't repeat 
	 *	for each radio element
	 */
	protected $radioFields = array();	
	
	protected $submitCheckRendered;
	
	// @var boolean $submittedCheck - Whether or not the form has been sent
	protected $submittedCheck = FALSE;
	
	// @var string $successMessage - A message to be added to the form if it has been completed successfully
	protected $successMessage = NULL;

	/**
	 * @param array $formAttributes - optional - An array of each attribute/value pair for the form, with the attribute name 
	 *	as the array key, its value as the array value. NOTE: The array key of 'innerHTML' will be ignored in this instance
	 * @param array $options - optional - Sets the options for the class
	 */	
	function __construct(array $formAttributes = array(), $options = NULL){
		//The default action should be the current page
		if (!isset($this->formAttributes['action'])){
			$this->formAttributes['action'] = htmlentities($_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
		}
		
		// @var array The default options array for the class
		$defaultOptions = array(
			'displayErrors' => TRUE,			//Boolean - Whether or not to display the errors. Defaults TRUE
			'errorPosition' => 'beforeInput',	//Where to display the errors: beforeForm, beforeInput, afterInput
			'errorListClass' => 'errorList',	//The class to apply to each error list
			'errorListItemClass' => 'errorListItem',	//The class to apply to each error list item			
			'inputErrorClass' => 'errorInput',	//The class to add to an input when there is an error
			'labelErrorClass' => 'errorLabel',	//The class to add to a label when there is an error
			'labelPosition' => 'beforeInput',	//Where to display the labels: beforeInput, afterInput
			'makeSticky' => TRUE,				//Boolean - If the form inputs retain their values after validation: Defaults TRUE
			'submittedCheckName' => 'submitted',	//The name of a hidden field to tell if the form has been submitted
			'strictDoctype' => FALSE,			//Boolean - TRUE if the doctype is strict, FALSE by default
			'strictContainerTag' => 'div',		//The tag for the form container if a strict doctype is used
			'strictContainerAttributes' => array()	//Array - The attributes to apply to the form container
		);

		//Override the default form attributes
		foreach ($formAttributes as $key => $value){
			$this->formAttributes[$key] = $value;
		}		
		
		parent::__construct();
		$this->setOptions($options, $defaultOptions);
	}
	
	/**
	 * @description Creates a form element
	 * @param string $tagName - The tag name of the form element
	 * @param string $fieldName - The field name of the form element	
	 * @param array $attributesArray - The attributes of the form element, with the attribute name as a key, the value 
	 * 		as a value
	 * @param boolean $selfClosing - Whether or not the tag is self closing
	 * @param string $input - The input for this element if the form has already been submitted
	 * @param array $selectOptionsArray - optional - the options array if the form element is a select box
	 * @return string - The form element in HTML
	 */
	protected function createFormElement($tagName, $fieldName, $attributesArray, $selfClosing, $selectOptionsArray = NULL){		
		$input = $this->fieldValues[$fieldName];
		$input = (is_array($this->fieldValues[$fieldName])) ? $input : htmlentities($input);
		
		//Here we create an id if one is not specified so that we can have a valid label
		if ((!isset($attributesArray['id'])) && (isset($attributesArray['type'])) && ($attributesArray['type'] != 'hidden')) {
			$attributesArray['id'] = $fieldName;
		}
		
		//Override the name value with the fieldName, just so no mismatches occur
		$attributesArray['name'] = $fieldName;								
		if ($this->errorsExist($fieldName)){
			if (isset($attributesArray['class'])){
				$attributesArray['class'] .= ' '.$this->options['inputErrorClass'];
			} else {
				$attributesArray['class'] = $this->options['inputErrorClass'];
			}
		}

		//Add so we don't repeat errors
		if ((isset($attributesArray['type'])) && ($attributesArray['type'] == 'radio') && (!in_array($fieldName, $this->radioFields))){
			$this->radioFields[] = $fieldName;
		}
		
		//Make the form sticky if it's enabled in the options
		if (($this->options['makeSticky'])&&($this->submittedCheck == TRUE)){
			if ($tagName == 'input'){ 
				if (($attributesArray['type'] == 'text')||($attributesArray['type'] == 'password')){
					$attributesArray['value'] = $input;
				} else if (($attributesArray['type'] == 'checkbox') || ($attributesArray['type'] == 'radio')) {
					if ($input == $attributesArray['value']){
						$attributesArray['checked'] = 'checked';
					}
				}
			} else if ($tagName == 'textarea'){ 
				$attributesArray['innerHtml'] = $input;
			}
		}

		if ((isset($attributesArray['multiple']))&&($attributesArray['multiple'] == 'multiple')){
			$attributesArray['name'] .= '[]';
		}
						
		return Xml::createTag($tagName, $attributesArray, $selfClosing)."\n";		
	}

	/**
	 * @description Creates a label element
	 * @param string $fieldName - The field name of the label's form element	
	 * @param array $labelArray - The attributes of the label element, with the attribute name as a key, the value 
	 * 		as a value
	 * @param string $formElementId - The id of the form element, if it exists
	 * @return string - The label in HTML
	 */
	protected function createLabel($fieldName, $labelArray, $formElementId){
		$label = NULL;
		if (isset($labelArray)){
			if (is_array($labelArray)){
				//Here we create a valid label if the for attribute is not already specified
				if (!isset($labelArray['for'])) {
					$labelArray['for'] = (isset($formElementId)) ? $formElementId : $fieldName;
				}
				if ($this->errorsExist($fieldName)){
					if (isset($labelArray['class'])){
						$labelArray['class'] .= ' '.$this->options['labelErrorClass'];
					} else {
						$labelArray['class'] = $this->options['labelErrorClass'];
					}
				}
				$label = Xml::createTag('label', $labelArray)."\n";
			} else {
				throw new Vm\Form\Exception("The value of the 'label' key for '$fieldName' must be an array, with each 
						array key as the attribute name and the value as the attribute value.");			
			}			
		} 
		return $label;
	}

	/**
	 * @description Creates an error list
	 * @param boolean $submittedCheck - Whether or not the form has been submitted
	 * @param string $fieldName - optional - The field name to retrieve errors for. If empty, will fetch all errors 
	 * 		for form
	 * @return string - The compiled error list if errors exist, else NULL
	 */
	protected function createErrorList($fieldName = NULL, $tagName = NULL){
		$errorList = NULL;
		if ($this->submittedCheck){
			$getErrors = ($fieldName) ? $this->getErrors($fieldName) : $this->getAllErrors();
			$errorsExist = ($this->options['errorPosition'] != 'beforeForm') ? $this->errorsExist($fieldName) : $this->errorsExist();
			if ($errorsExist){		
				$errors = '';
				foreach($getErrors as $error){
					$errorAttributes = array(
						'class'=>$this->options['errorListItemClass'], 
						'innerHtml'=>$error
					);
					$errors .= Xml::createTag('li', $errorAttributes)."\n";
				}
				$errorListAttributes = array(
					'id'=>$fieldName.'ErrorList', 
					'class'=>$this->options['errorListClass'], 
					'innerHtml'=>$errors
				);
				$errorList = Xml::createTag('ul', $errorListAttributes)."\n";
			} 
		}
		return $errorList;
	}
	
	/**
	 * @description Processes form elements and their options arrays
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $selectOptions - The select options to be converted to HTML
	 * @param array $selected - The values of the selected options that should be pre-selected 
	 * @return mixed - Returns a string of options to be included in a select box if they exist, else NULL
	 */
	protected function createSelectOptions($fieldName, $selectOptions, $selected){
		if ((isset($selectOptions)) && (is_array($selectOptions))){
			$input = $this->fieldValues[$fieldName];
			$selected = (isset($selected)&&(is_array($selected))) ? $selected : array();
			$options = '';
			foreach ($selectOptions as $key => $value){
				if (is_array($value)){ //This means it should be treated as an optgroup
					$optGroup = '';
					foreach ($value as $optValue => $optText){						
						$optionAttributes = array('value'=>$optValue, 'innerHtml'=>$optText);
						if (in_array($optValue, $selected)){ 
							$optionAttributes['selected'] = 'selected';
						}							
						$optGroup .= Xml::createTag('option', $optionAttributes)."\n";
					}
					$optGroupAttributes = array('label'=>$key, 'innerHtml'=>$optGroup);
					$options .= Xml::createTag('optgroup', $optGroupAttributes)."\n";
				} else { //No optgroup is present
					$optionAttributes = array('value'=>$key, 'innerHtml'=>$value);
					if ((($input == $key) || ((is_array($input)) && (in_array($key, $input)))) && (!empty($input))||(in_array($key, $selected))){ 
						$optionAttributes['selected'] = 'selected';
					}
					$options .= Xml::createTag('option', $optionAttributes)."\n";
				}
			}
			return $options;
		} else {
			throw new Vm\Form\Exception("The '$fieldName' select box must have an array key entitled 'selectOptions' 
				and its value must be an array.");
		}
	}

	/**
	 * @description Adds label, form element, and error list in the proper order to the formRender string 
	 * @param string $tagName - The HTML tag to create - limited to form elements
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @param boolean $selfClosing - Whether or not the element is self closing - Defaults TRUE
	 */
	protected function addToFormRender($label, $labelPos, $formElement, $errorList, $wrapperElement, $wrapperAttributes){
		$render = ($labelPos == 'beforeInput') ? $label.$formElement : $formElement.$label;		
		if (($wrapperElement) && ($wrapperAttributes)){
			$wrapperAttributes['innerHtml'] = $render;
			$render = Xml::createTag($wrapperElement, $wrapperAttributes)."\n";
		}
		
		if ($this->options['errorPosition'] != 'beforeForm'){				
			$render = ($this->options['errorPosition'] == 'beforeInput') ? $errorList.$render : $render.$errorList;
		}
		$this->formRender .= $render;	
	}

	/**
	 * @description Runs filters for each form field
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form validations
	 */
	protected function runFilters($fieldName, $optionsArray){
		if (is_array($optionsArray['filters'])){
			foreach ($optionsArray['filters'] as $key => $value) {
				$input = (isset($this->filteredFieldValues[$fieldName])) 
					? $this->filteredFieldValues[$fieldName] 
					: $this->fieldValues[$fieldName];
				
				if (is_int($key)){
					$filterName = $value;
					$params = array($input);
				} else {
					$filterName = $key;
					$params = array_unshift($value, $input);
					
					if (!is_array($value)){
						throw new Vm\Form\Exception("The value of the '$key' key in the 'filters' array for '$fieldName'
								must be an array.");
					}					
				}
								
				$filterName = 'Vm\Filter\\'.$filterName;
				$filter = new $filterName;
				$this->filteredFieldValues[$fieldName] = call_user_func_array(array($filter, 'filter'), $params);
			}
		} else {
			throw new Vm\Form\Exception("The value of the 'filters' key for '$fieldName' must be an array for which 
				each array key is the filter name and the value is an array of parameters for that filter, excluding 
				the input parameter, which is included automatically. If that particular filter has only the input 
				parameter, the filter name should be the array value rather than the key.");
		}
	} 
	
	/**
	 * @description Runs validators for each form field
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form validations
	 */
	protected function runValidators($fieldName, $optionsArray){
		$input = (isset($this->filteredFieldValues[$fieldName])) 
			? $this->filteredFieldValues[$fieldName] 
			: $this->fieldValues[$fieldName];
		if (is_array($optionsArray['validators'])){
			$this->addValidators($fieldName, $input, $optionsArray['validators']);
		} else {
			throw new Vm\Form\Exception("The value of the 'validators' key for '$fieldName' must be an array for which 
				each array key is the validator name and the value is a custom error message. If no error message is 
				specified, a default error message will be used and the validator name should be the array value.");
		}
	} 
		
	/**
	 * @description Processes form elements and their options arrays
	 * @param string $tagName - The HTML tag to create - limited to form elements
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @param boolean $selfClosing - Whether or not the element is self closing - Defaults TRUE
	 */
	protected function element($tagName, $fieldName, array $optionsArray, $selfClosing = TRUE){
		if (($this->formAttributes['method'] == 'post')&&(isset($_POST[$fieldName]))){ 
			$input = $_POST[$fieldName]; 
		} else if (isset($_GET[$fieldName])){	
			$input = $_GET[$fieldName];
		} else {
			$input = NULL;
		}
		$this->fieldValues[$fieldName] = $input;
	
		$submittedCheckName = $this->options['submittedCheckName'];
		if (($this->formAttributes['method'] == 'post')&&(isset($_POST[$submittedCheckName]))){ 
			$this->submittedCheck = $_POST[$submittedCheckName]; 
		} else if (isset($_GET[$submittedCheckName])){	
			$this->submittedCheck = $_GET[$submittedCheckName];
		} else {
			$this->submittedCheck = NULL;
		}

		if (($this->submittedCheck) && (!in_array($fieldName, $this->radioFields))){
			if (isset($optionsArray['filters'])){
				$this->runFilters($fieldName, $optionsArray);
			}
			if (isset($optionsArray['validators'])){
				$this->runValidators($fieldName, $optionsArray);
			}									
		} 
		
		if ($tagName == 'select') {
			$selected = (isset($optionsArray['selected'])) ? $optionsArray['selected'] : NULL;
			$optionsArray['attributes']['innerHtml'] = $this->createSelectOptions($fieldName, $optionsArray['selectOptions'], $selected);
		}
		
		$optionsArray['label'] = (isset($optionsArray['label'])) ? $optionsArray['label'] : NULL;
		$optionsArray['attributes']['id'] = (isset($optionsArray['attributes']['id'])) ? $optionsArray['attributes']['id'] : NULL;

		$wrapperElement = (isset($optionsArray['wrapperElement'])) ? $optionsArray['wrapperElement'] : NULL;
		$wrapperAttributes = (isset($optionsArray['wrapperAttributes'])) ? $optionsArray['wrapperAttributes'] : NULL;
		$label = $this->createLabel($fieldName, $optionsArray['label'], $optionsArray['attributes']['id']);
		$labelPos = (isset($optionsArray['labelPosition'])) ? $optionsArray['labelPosition'] : $this->options['labelPosition'];
		$errorList = ((isset($optionsArray['attributes']['type']))&&($optionsArray['attributes']['type'] == 'radio') && (in_array($fieldName, $this->radioFields)))
			? NULL 
			: $this->createErrorList($fieldName, $tagName);
		$selectOptions = ((isset($optionsArray['selectOptions']))&&(is_array($optionsArray['selectOptions']))) ? $optionsArray['selectOptions'] : NULL; 	
		$formElement = $this->createFormElement($tagName, $fieldName, $optionsArray['attributes'], $selfClosing, $selectOptions);
				
		$this->addToFormRender($label, $labelPos, $formElement, $errorList, $wrapperElement, $wrapperAttributes);			
	}

	/**
	 * @description Creates a text input and sets its options
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @return - returns the object for chaining	
	 */	
	public function text($fieldName, array $optionsArray = array()){
		$optionsArray['attributes']['type'] = 'text';
		$this->element('input', $fieldName, $optionsArray);
		return $this;
	}

	/**
	 * @description Creates a password input and sets its options
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @return - returns the object for chaining	
	 */		
	public function password($fieldName, array $optionsArray = array()){
		$optionsArray['attributes']['type'] = 'password';
		$this->element('input', $fieldName, $optionsArray);
		return $this;		
	}

	/**
	 * @description Creates a file input and sets its options. Also automatically sets the form enctype to accept forms
	 * 	and the method to post
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @return - returns the object for chaining	
	 */		
	public function file($fieldName, array $optionsArray = array()){
		$optionsArray['attributes']['type'] = 'file';
		$this->element('input', $fieldName, $optionsArray);
		$this->formAttributes['method'] = 'post';
		$this->formAttributes['enctype'] = 'multipart/form-data';
		return $this;		
	}	
	
	/**
	 * @description Creates a checkbox input and sets its options
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @return - returns the object for chaining	
	 */		
	public function checkbox($fieldName, array $optionsArray = array()){
		$optionsArray['attributes']['type'] = 'checkbox';
		$this->element('input', $fieldName, $optionsArray);
		return $this;		
	}	

	/**
	 * @description Creates a radio input and sets its options
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @return - returns the object for chaining	
	 */	
	public function radio($fieldName, array $optionsArray = array()){
		$optionsArray['attributes']['type'] = 'radio';
		$this->element('input', $fieldName, $optionsArray);
		return $this;		
	}

	/**
	 * @description Creates a hidden input and sets its options
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @return - returns the object for chaining	
	 */		
	public function hidden($fieldName, array $optionsArray = array()){
		$optionsArray['attributes']['type'] = 'hidden';
		$this->element('input', $fieldName, $optionsArray);
		return $this;		
	}		

	/**
	 * @description Creates a textarea and sets its options
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @return - returns the object for chaining	
	 */		
	public function textarea($fieldName, array $optionsArray = array()){
		$optionsArray['attributes']['name'] = $fieldName;
		$optionsArray['attributes']['rows'] = (isset($optionsArray['attributes']['rows'])) ? $optionsArray['attributes']['rows'] : 3;
		$optionsArray['attributes']['cols'] = (isset($optionsArray['attributes']['cols'])) ? $optionsArray['attributes']['cols'] : 30;		
		$this->element('textarea', $fieldName, $optionsArray, false);
		return $this;		
	}

	/**
	 * @description Creates a select box and sets its options
	 * @param string $fieldName - The name to be assigned the form field
	 * @param array $optionsArray - The options array to create the form element, its label, its validations, and its filters
	 * @return - returns the object for chaining	
	 */		
	public function select($fieldName, array $optionsArray){
		$optionsArray['attributes']['name'] = $fieldName;
		$this->element('select', $fieldName, $optionsArray, false);
		return $this;		
	}				

	/**
	 * @description Creates a reset button and sets its options
	 * @param array $resetAttributes - The options array to create the form element
	 * @return - returns the object for chaining	
	 */	
	public function reset(array $resetAttributes = NULL){
		$resetAttributes['type'] = 'reset';
		$resetAttributes['value'] = (!isset($resetAttributes['value'])) ? 'Reset' : $resetAttributes['value'];
		$this->formRender .= Xml::createTag('input', $resetAttributes, TRUE)."\n";
		return $this;			
	}

	/**
	 * @description Creates a submit button and sets its options
	 * @param array $submitAttributes - The options array to create the form element
	 * @return - returns the object for chaining	
	 */	
	public function submit(array $submitAttributes = NULL){
		$submitAttributes['type'] = 'submit';
		$submitAttributes['value'] = (!isset($submitAttributes['value'])) ? 'Submit' : $submitAttributes['value'];
		$this->formRender .= Xml::createTag('input', $submitAttributes, TRUE)."\n";
		return $this;			
	}

	/**
	 * @description Creates a button and sets its options
	 * @param array $buttonAttributes - The options array to create the form element
	 * @return - returns the object for chaining	
	 */		
	public function button(array $buttonAttributes = NULL){
		$buttonAttributes['type'] = 'button';
		$this->formRender .= Xml::createTag('input', $buttonAttributes, TRUE)."\n";
		return $this;			
	}

	/**
	 * @description Creates a image button and sets its options
	 * @param array $imageAttributes - The options array to create the form element
	 * @return - returns the object for chaining	
	 */		
	public function image(array $imageAttributes = NULL){
		$imageAttributes['type'] = 'image';
		$this->formRender .= Xml::createTag('input', $imageAttributes, TRUE)."\n";
		return $this;			
	}

	/**
	 * @description Creates a label element and sets its options
	 * @param array $labelAttributes - The options array to create the label
	 * @return - returns the object for chaining	
	 */		
	public function label($labelText, array $labelAttributes = NULL){
		$labelAttributes['innerHtml'] = $labelText;
		$this->formRender .=  Xml::createTag('label', $labelAttributes)."\n";
		return $this;			
	}			

	/** 
	 * @description Creates an HTML element to be inserted into the form
	 * @param string $tagName - The name of the tag to be created
	 * @param array $attributes - optional - An array of each attribute/value pair for the tag, with the attribute name 
	 *	as the array key, its value as the array value. NOTE: The array key of 'innerHTML' has special meaning: It is the
	 *	actual content of the tag, including any child tags or text, and will only be applied to non-self-closing tags
	 * @param boolean $selfClosing - TRUE means no closing tag will be added, FALSE means a closing tag will be added
	 * 	Defaults to FALSE
	 * @return - returns the object for chaining	
	 */
	public function createTag($tagName, array $attributes = array(), $selfClosing = FALSE){
		$this->formRender .=  Xml::createTag($tagName, $attributes, $selfClosing)."\n";	
		return $this;
	}

	/** 
	 * @description Creates the beginnning of a wrapper tag
	 * @param string $tagName - The name of the tag to be created
	 * @param array $attributes - optional - An array of each attribute/value pair for the tag, with the attribute name 
	 *	as the array key, its value as the array value. NOTE: The array key of 'innerHTML' is ignored
	 * @return - returns the object for chaining	
	 */
	public function startTag($tagName, array $attributes = array()){
		$this->formRender .=  Xml::startTag($tagName, $attributes)."\n";	
		return $this;
	}

	/** 
	 * @description Closes a wrapper tag
	 * @param string $tagName - The name of the tag to be created
	 * @return - returns the object for chaining	
	 */	
	public function endTag($tagName){
		$this->formRender .=  Xml::endTag($tagName)."\n";
		return $this;
	}	

	/** 
	 * @description Adds data to the form
	 * @param string $data - The data to be appended to the form
	 * @return - returns the object for chaining	
	 */		
	public function append($data){
		$this->formRender .= $data;
		return $this;
	}
	
	/**
	 * @param string $fieldName - The name of the field for which a value should be returned
	 * @return mixed - The filtered value if it exists, otherwise returns the unfiltered value
	 */
	public function getValue($fieldName){
		return (isset($this->filteredFieldValues[$fieldName])) ? $this->filteredFieldValues[$fieldName] : $this->fieldValues[$fieldName];
	}

	/**
	 * @param string $fieldName - The name of the field for which an unfiltered value should be returned
	 * @return mixed - The value if it exists, FALSE otherwise
	 */	
	public function getUnfilteredValue($fieldName){
		return ($this->fieldValues[$fieldName]) ? $this->fieldValues[$fieldName] : FALSE;
	}	

	/**
	 * Clears filtered and unfiltered values
	 */
	public function clear(){
		$this->fieldValues = array();
		$this->filteredFieldValues = array();
	}
	
	/**
	 * @return boolean - Whether or not the form has been submitted
	 */
	public function submitted(){
		return $this->submittedCheck;
	}
	
	/** 
	 * @description Renders the complete form
	 * @return string - Returns the compiled form
	 */	
	public function render(){
		if ($this->submitCheckRendered != 1){ //This is to prevent multiple submit checks from being rendered if the form is rendered more than once
			$this->submitCheckRendered = 1;
			$this->hidden($this->options['submittedCheckName'], array('attributes'=>array('value'=>'TRUE')));
		}
		if ($this->options['strictDoctype']) {
			$this->options['strictContainerAttributes']['innerHtml'] = $this->formRender;
			$formRender = Xml::createTag($this->options['strictContainerTag'], $this->options['strictContainerAttributes'])."\n";
		} else {
			$formRender = $this->formRender;
		}		
		$this->formAttributes['innerHtml'] = $formRender;
		$form = Xml::createTag('form', $this->formAttributes)."\n";
		if ($this->options['errorPosition'] == 'beforeForm') {
			$form = $this->createErrorList().$form;
		}
		return $form;
	}
	
	/**
	 * Returns the compiled form without the form tag for insertion into another form, not to be used if the errorPosition is set
	 * 	to 'beforeForm'
	 * @return string - The compiled form, without the form tag 
	 */
	public function renderSnippet(){
		$submittedAttributes = array('type'=>'hidden', 'name'=>$this->options['submittedCheckName'], 'value'=>'TRUE');
		$submittedField = Xml::createTag('input', $submittedAttributes, TRUE)."\n";
		$this->formRender .= $submittedField;
		return $this->formRender;
	}
}