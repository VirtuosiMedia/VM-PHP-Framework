<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A basic XML class that simply returns the passed in parameters in XML tag format
 * @requirements PHP 5.2 or higher
 * @namespace Vm
 */
namespace Vm;

class Xml {

	protected $lowercase;
	
	/**
	 * @param boolean $lowercase - optional - Whether or not the XML should be converted to lowercase, defaults TRUE 
	 */
	function __construct($lowercase = TRUE){
		$this->lowercase = $lowercase;
	}
	
	/**
	 * Creates and returns an XML string using magic methods, with the method name as the tag name, the first parameter
	 * 	as the innerHtml, and the second parameter as the attributes array. Both parameters are optional
	 * @return string - An XML string
	 */
	function __call($tagName, $params) {
		$innerHtml = $params[0];
		if (sizeof($params) == 2){
			$attributes = (is_array($params[1])) ? $params[1] : array();
		}
		$tagName = ($this->lowercase) ? strtolower($tagName) : $tagName;
		$selfClosingTags = array('area', 'base', 'basefont', 'br', 'hr', 'input', 'img', 'link', 'meta');
		$attributes['innerHtml'] = $innerHtml;
		return $this->createTag($tagName, $attributes, in_array($tagName, $selfClosingTags));
	}
	
	/** 
	 * @param string $tagName - The name of the tag to be created
	 * @param array $attributes - optional - An array of each attribute/value pair for the tag, with the attribute name 
	 * 		as the array key, its value as the array value. NOTE: The array key of 'innerHTML' has special meaning: It 
	 * 		is the actual content of the tag, including any child tags or text, and will only be applied to 
	 * 		non-self-closing tags
	 * @param boolean $selfClosing - TRUE means no closing tag will be added, FALSE means a closing tag will be added
	 * 		Defaults to FALSE
	 */
	public function createTag($tagName, array $attributes = array(), $selfClosing = FALSE){
		$tag = "<$tagName";
		$innerHTML = NULL;
		foreach ($attributes as $attribute=>$value){
			$attribute = strtolower($attribute);
			if ($attribute != 'innerhtml'){
				$tag .= ' '.$attribute.'="'.$value.'"';
			} else {
				$innerHTML = $value;
			}
		}
		
		if ($selfClosing){
			$tag .= " />";
		} else {
			$tag .= ">";
			if ($innerHTML){
				$tag .= $innerHTML;
			}
			$tag .= "</$tagName>";
		}
		return $tag;		
	}

	/**
	 * @param string $tagName - The name of the opening tag to be created
	 * @param array $attributes - optional - An array of each attribute/value pair for the tag, with the attribute name 
	 * 		as the array key, its value as the array value.	Note: innerHtml is ignored
	 */
	public function startTag($tagName, array $attributes = array()){
		$tag = "<$tagName";
		foreach ($attributes as $attribute=>$value){
			$attribute = strtolower($attribute);
			if ($attribute != 'innerhtml'){
				$tag .= ' '.$attribute.'="'.$value.'"';
			} 
		}
		$tag .= ">";
		return $tag;	
	}

	/**
	 * @param string $tagName - The name of the closing tag to be created
	 */	
	public function endTag($tagName){
		return "</$tagName>";	
	}
}