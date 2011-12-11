<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A translation controller
 * @extends Vm\Lang
 * @namespace Vm
 */
namespace Vm;

class Translate extends \Vm\Lang {

	protected $currentLang;
	protected $defaultLang;
	protected $lang;
	//TODO: Add the rest of the language codes
	protected $languages = array('en', 'es', 'fr', 'fi');
	
	
	/**
	 * @param string $currentLang - The current language in ISO 639 format (lowercase) - if the file does not exists, 
	 * 		reverts to the default language
	 * @param string $defaultLang - The default language in ISO 639 format (lowercase) - defaults to 'en' if a improper 
	 * 		value is given
	 * @param string $className - The name of the language class, excluding the final underscore and the language code 
	 * 	identifier: For example, a class named Vm_Lang_En would be shortened to Vm_Lang
	 * @param string $includesDirPath - optional - The relative path to the includes directory, without the trailing 
	 * 	slash. Defaults to 'includes' 
	 */
	function __construct ($currentLang, $defaultLang, $className, $includesDirPath = 'includes'){
		$this->currentLang = (in_array($currentLang, $this->languages)) ? $currentLang : $defaultLang;
		$this->defaultLang = (in_array($defaultLang, $this->languages)) ? $defaultLang : 'en';

		if (file_exists($includesDirPath.'/'.preg_replace('#_#', '/', $className).'/'.$this->currentLang.'.php')){
			$loadClass = $className.'_'.$this->currentLang;
			$this->lang = new $loadClass();
		} else if (file_exists($includesDirPath.'/'.preg_replace('#_#', '/', $className).'/'.$this->defaultLang.'.php')){
			$loadClass = $className.'_'.$this->defaultLang;
			$this->lang = new $loadClass();
		} else if (file_exists($includesDirPath.'/'.preg_replace('#_#', '/', $className).'/En.php')){
			$loadClass = $className.'_En';
			$this->lang = new $loadClass();		
		} else {
			throw new Translate\Exception("A translation file does not exist for the language code '$currentLang'");			
		}	
	}
	
	/**
	 * @param string $translationKey - The translation key for the word or phrase to be translated
	 * @param array $replacementText - optional - An array of replacement texts to be applied if the translation text 
	 * 		has placeholders 
	 * @return - The value for the $translationKey if it exists, FALSE otherwise 
	 */
	public function translate($translationKey, array $replacementText = array()){
		$numReplacements = sizeof($replacementText);
		if (($numReplacements > 0) && ($this->lang->getValue($translationKey))){
			$placeholders = array();
			for ($i=1; $i <= $numReplacements; $i++){
				$placeholders[] = "/\{$i\}/";
			}
			return preg_replace($placeholders, $replacementText, $this->lang->getValue($translationKey));
		} else {
			return $this->lang->getValue($translationKey);
		}
	}
	
	/**
	 * @param string $translationString - The translation string for which a key should be retrieved
	 * @return mixed - The translation key if it exists, otherwise FALSE
	 */
	public function getTranslationKey($translationString){
		return $this->lang->getKey($translationString);
	}

	/**
	 * @return string - The current language
	 */	
	public function getCurrentLang(){
		return $this->currentLang;
	}
	
	/**
	 * @return string - The default language
	 */
	public function getDefaultLang(){
		return $this->defaultLang;
	}
}