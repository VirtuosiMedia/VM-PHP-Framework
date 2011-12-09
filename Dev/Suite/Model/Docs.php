<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the docs page variables for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model
 * @uses Vm\Folder
 * @uses Vm\Version
 */
namespace Suite\Model;

use Vm\Folder;
use Vm\Version;

class Docs extends \Vm\Model {
	
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
		$tabs = array();
		$apps = array();
		
		$folder = new Folder('../Includes');
		$folders = $folder->getFolders();
		
		foreach ($folders as $dir){
			if (!in_array($dir, $this->settings['excludeFoldersFromDocs'])){
				$tabs[] = array('class'=>'firstTab tab active', 'hash'=>'#'.$dir, 'name'=>$dir);
				$apps[] = $dir;
			}	
		}
		
		$tabs[] = array('class'=>'tab', 'hash'=>'#vm', 'name'=>'VM');
		$tabs[] = array('class'=>'tab', 'hash'=>'#suite', 'name'=>'Suite');
		$tabs[] = array('class'=>'tab', 'hash'=>'#help', 'name'=>'Help');		
		$tabs[0]['class'] = 'firstTab tab active';
		
		$this->setData('tabs', $tabs);
		$this->setData('apps', $apps);
		
		foreach($apps as $app){
			$this->getAppData($app);
		}
		
		$this->getAppData('Vm');
		$this->getSuiteData();
		
		$version = new Version();
		$this->setData('version', $version->get('version'));
		$this->setData('copyright', $version->get('copyright'));		
	}

	protected function getAppData($app){
		$folder = new Folder('../Includes/'.$app);
		$files = $folder->getFiles(TRUE, 'php');
		
		$appData = array();
		$classFiles = array();
		foreach ($files as $path=>$file){
			$name = str_replace('/', '\\', str_replace('../Includes/', '', str_replace('.php', '', $path)));
			$appData[] = array(
				'name'=>$name,
				'url'=>'index.php?p=docs&amp;f='.$name
			);
			$classFiles[] = $name;
		}		
		
		if (sizeof($classFiles) > 0){
			$numFiles =  (is_int(sizeof($appData)/2)) ? sizeof($appData)/2 : (sizeof($appData) + 1)/2; 
			$appData = array_chunk($appData, $numFiles);
			$this->setData($app, $appData);
		} else {
			$this->setData($app, FALSE);
		}

		if (is_dir('Docs/Tutorials/'.$app)){
			$folder = new Folder('Docs/Tutorials/'.$app);
			$tutorials = $folder->getFiles(TRUE, 'php');
			
			$appTutorials = array();
			foreach ($tutorials as $path=>$tutorial){
				$classFile = str_replace('/', '_', str_replace('Docs/Tutorials/', '', str_replace('.php', '', $path)));
				if (!in_array($classFile, $classFiles)){
					$param = str_replace('.php', '', $tutorial);
					$name = str_replace('|', '-', str_replace('-', ' ', str_replace('---', '-|-', $param)));
					$appTutorials[] = array(
						'name'=>$name,
						'url'=>'index.php?p=docs&amp;a='.$app.'&amp;t='.$param
					);
				}
			}
			
			if (sizeof($appTutorials) > 0){
				$numTutorials =  (is_int(sizeof($appTutorials)/2)) ? sizeof($appTutorials)/2 : (sizeof($appTutorials) + 1)/2; 	
				$appTutorials = array_chunk($appTutorials, $numTutorials);
				$this->setData($app.'Tutorials', $appTutorials);
			} else {
				$this->setData($app.'Tutorials', FALSE);
			}
		} else {
			$this->setData($app.'Tutorials', FALSE);
		}		
	}
	
	protected function getSuiteData(){
		$folder = new Folder('Docs/Tutorials/Suite');
		$tutorials = $folder->getFiles(TRUE, 'php');
		
		$suite = array();
		foreach ($tutorials as $path=>$tutorial){
			$param = str_replace('.php', '', $tutorial);
			$name = str_replace('|', '-', str_replace('-', ' ', str_replace('---', '-|-', $param)));
			$suite[] = array(
				'name'=>$name,
				'url'=>'index.php?p=docs&amp;a=suite&amp;t='.$param
			);
		}
		
		$numTutorials =  (is_int(sizeof($suite)/2)) ? sizeof($suite)/2 : (sizeof($suite) + 1)/2; 
		
		$suite = array_chunk($suite, $numTutorials);
		$this->setData('suite', $suite);
	}
}