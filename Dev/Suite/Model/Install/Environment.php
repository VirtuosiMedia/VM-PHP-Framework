<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The model for generating the server environment variables for VM PHP Framework Suite
 * @requirements PHP 5.2 or higher
 * @namespace Suite\Model\Install
 * @uses Tools\Installer
 * @uses Vm\Folder
 * @uses Vm\Form
 */
namespace Suite\Model\Install;

class Environment extends \Vm\Model {
	
	function __construct(){
		$this->compileData();		
	}
	
	protected function compileData(){
		list($major, $minor, $release) = explode('.', phpversion());
		$phpVersionClass = (($major >= 5) && ($minor >= 3)) ? 'pass' : 'fail';
		$phpVersion = ($phpVersionClass == 'pass') ? 
			'Current PHP Version: '.phpversion().'.' 
			: 'Current PHP Version: '.phpversion().' - Version 5.3 or higher required'; 
		$gdLibraryClass = (extension_loaded('gd') && function_exists('gd_info')) ? 'pass' : 'fail';
		$gdLibrary = ($gdLibraryClass == 'pass') 
			? 'GD Library is enabled.' 
			: 'GD Library must be enabled for some classes to work';
		$zlibClass = (extension_loaded('zlib')) ? 'pass' : 'fail';
		$zlib = ($zlibClass == 'pass') 
			? 'Zlib extension is enabled.' 
			: 'Zlib extension is disabled. Some classes will not function properly.';
		$pdoClass = (extension_loaded('pdo')) ? 'pass' : 'fail';
		$pdo = ($pdoClass == 'pass') 
			? 'PDO extension is enabled.' 
			: 'PDO extension is disabled. Database classes will not function properly.';
		$mysqlPdoClass = (extension_loaded('pdo')) ? 'pass' : 'fail';
		$mysqlPdo = ($mysqlPdoClass == 'pass') 
			? 'MySQL PDO extension is enabled.' 
			: 'MySQL PDO extension is disabled. MySQL database classes will not function properly.';
		$ctypeClass = (extension_loaded('ctype')) ? 'pass' : 'fail';
		$ctype = ($ctypeClass == 'pass') 
			? 'Ctype extension is enabled.' 
			: 'Ctype extension is disabled. Some validation classes will not function properly.';
		$reflectionClass = (class_exists('Reflection', false)) ? 'pass' : 'fail';
		$reflection = ($reflectionClass == 'pass') 
			? 'Reflection extension is enabled.' 
			: 'Reflection extension is disabled. Unit testing suite will not function properly.';
		$xdebugClass = (function_exists('xdebug_start_code_coverage')) ? 'pass' : 'warning';
		$xdebug = ($xdebugClass == 'pass') 
			? 'Xdebug extension is enabled.' 
			: 'Xdebug extension is disabled. Code coverage analysis will not be available.';
		$conditionsList = array(
				$phpVersionClass, $gdLibraryClass, $zlibClass, $zlibClass, $pdoClass, $mysqlPdoClass, $ctypeClass,
				$reflectionClass, $xdebugClass
		);
		$conditions = (!in_array('fail', $conditionsList)) ? TRUE : FALSE;
		
		$this->setData('phpVersionClass', $phpVersionClass);
		$this->setData('phpVersion', $phpVersion);
		$this->setData('gdLibraryClass', $gdLibraryClass);
		$this->setData('gdLibrary', $gdLibrary);
		$this->setData('zlibClass', $zlibClass);
		$this->setData('zlib', $zlib);
		$this->setData('pdoClass', $pdoClass);
		$this->setData('pdo', $pdo);
		$this->setData('mysqlPdoClass', $mysqlPdoClass);
		$this->setData('mysqlPdo', $mysqlPdo);
		$this->setData('ctypeClass', $ctypeClass);
		$this->setData('ctype', $ctype);
		$this->setData('reflectionClass', $reflectionClass);
		$this->setData('reflection', $reflection);
		$this->setData('xdebugClass', $xdebugClass);
		$this->setData('xdebug', $xdebug);
		$this->setData('conditions', $conditions);		
	}
}