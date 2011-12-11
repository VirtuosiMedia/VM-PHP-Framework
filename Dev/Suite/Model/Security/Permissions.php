<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the security page folder permissions for VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 * @namespace Suite\Model\Security
 * @uses Vm\Folder
 */
namespace Suite\Model\Security;

use \Vm\Folder;

class Permissions extends \Vm\Model {
	
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
		$dir = new Folder('../Includes');
		$dirs = $dir->getFolders(TRUE, TRUE);
		asort($dirs);
		$folders = array();

		foreach ($dirs as $key=>$folderName){
			$dir = new Folder($folderName);
			$permissions = (int) $dir->getPermissions();
			$folders[$key] = array();
			
			if ($permissions == 777){
				$folders[$key]['class'] = 'fail';
			} elseif (($permissions < 777) && ($permissions > 755)){
				$folders[$key]['class'] = 'warning';
			} else {
				$folders[$key]['class'] = 'pass';
			}
					
			$folders[$key]['name'] = $folderName;
			$folders[$key]['permissions'] = $permissions;
		}

		$this->setData('folders', $folders);
	}
}