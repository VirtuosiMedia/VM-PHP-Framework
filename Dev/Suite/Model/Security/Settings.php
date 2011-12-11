<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the security settings for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model\Security
 */
namespace Suite\Model\Security;

class Settings extends \Vm\Model {
	
	protected $params;
	protected $settings;
	
	/**
	 * 
	 * @param array $params - An associative array of the URL parameters, with the parameter name as the key, it's 
	 * 		value as the value
	 * @param array $settings - An associative settings array, with the setting name as the key, it's value as the value
	 */	
	function __construct($params, $settings){
		$this->params = $params;
		$this->settings = $settings;
		$this->compileData();		
	}
	
	protected function compileData(){
		$magicQuotesClass = (get_magic_quotes_gpc()) ? 'fail' : 'pass';
		$magicQuotes = ($magicQuotesClass == 'pass') 
			? 'Magic Quotes is disabled.' 
			: 'Magic Quotes must be disabled.';
		$displayErrorsClass = (ini_get('display_errors')) ? 'pass' : 'warning';
		$displayErrors = ($displayErrorsClass == 'pass') 
			? 'display_errors is disabled. Enable for development only.' 
			: 'display_errors is enabled. Disable for production use.';
		$allowUrlIncludeClass = (ini_get('allow_url_include')) ? 'pass' : 'warning';
		$allowUrlInclude = ($allowUrlIncludeClass == 'pass') 
			? 'allow_url_include is disabled.' 
			: 'allow_url_include is enabled and can be a security risk.';
		$allowUrlFopenClass = (ini_get('allow_url_fopen ')) ? 'pass' : 'warning';
		$allowUrlFopen = ($allowUrlFopenClass == 'pass') 
			? 'allow_url_fopen is disabled.' 
			: 'allow_url_fopen is enabled and can be a security risk.';
		$registerGlobalsClass = (ini_get('register_globals')) ? 'pass' : 'fail';
		$registerGlobals = ($registerGlobalsClass == 'pass') 
			? 'register_globals is disabled.' 
			: 'register_globals is enabled and is a security risk.';
		$exposePhpClass = (ini_get('expose_php')) ? 'warning' : 'pass';
		$exposePhp = ($exposePhpClass == 'pass') 
			? 'expose_php is disabled.' 
			: 'expose_php is enabled and may be a security risk.';
		
		$this->setData('magicQuotesClass', $magicQuotesClass);
		$this->setData('magicQuotes', $magicQuotes);
		$this->setData('displayErrorsClass', $displayErrorsClass);
		$this->setData('displayErrors', $displayErrors);
		$this->setData('allowUrlIncludeClass', $allowUrlIncludeClass);
		$this->setData('allowUrlInclude', $allowUrlInclude);
		$this->setData('allowUrlFopenClass', $allowUrlFopenClass);
		$this->setData('allowUrlFopen', $allowUrlFopen);
		$this->setData('registerGlobalsClass', $registerGlobalsClass);
		$this->setData('registerGlobals', $registerGlobals);
		$this->setData('exposePhpClass', $exposePhpClass);
		$this->setData('exposePhp', $exposePhp);
	}
}