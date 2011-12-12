<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the namespaces page variables for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model\Docs
 * @requires Vm\Folder
 * @requires Vm\Version
 */
namespace Suite\Model\Docs;

class Namespaces extends \Vm\Model {
	
	protected $params;
	protected $settings;
	
	/**
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
		$namespace = strip_tags($this->params['n']);
		$this->setData('namespaceName', $namespace);
		
		$path = '..'.DIRECTORY_SEPARATOR.'Includes'.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
			
		$folder = new \Vm\Folder($path);
		if ($folder->isDir()){
			$files = $folder->getFiles(FALSE, 'php');
			$classes = array();
			
			foreach ($files as $file){
				$classes[] = $namespace.'\\'.str_replace('.php', '', $file);
			}
			
			$folders = $folder->getFolders();
			$subnamespaces = array();
			
			foreach ($folders as $sub){
				$subnamespaces[] = $namespace.'\\'.$sub;
			}
			
			$this->setData('namespaceExists', TRUE);
			$this->setData('classes', $classes);
			$this->setData('subnamespaces', $subnamespaces);
		} else {
			$this->setData('namespaceExists', FALSE);
		}
				
		$version = new \Vm\Version();
		
		$this->setData('version', $version->get('version'));
		$this->setData('copyright', $version->get('copyright'));		
	}
}