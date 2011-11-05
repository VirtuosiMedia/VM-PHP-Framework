<?php
/**
 * Autoloader function for VM Framework. Note: A new Autoloader will be generated it VM Framework application scaffolding is installed 
 * Files must adhere to the following naming convention:
 * 		Path: Folder/Folder1/Folder2/File.php
 * 		Class Name: Folder_Folder1_Folder2_File
 */

/**
 * @param string $className - The class to be loaded 
 */
function __autoload($className) {
	$uris = explode('_', $className);
	foreach($uris as $key=>$uri){
		$uris[$key] = ucfirst($uri);
	}
	$className = implode('/', $uris);
	//$prefix = ((file_exists('../Includes/Version.php'))&&(!preg_match('#^(Tests\/)#', $className))&&(!preg_match('#^(Tools\/)#', $className))) ? '../Includes/' : NULL;
	$prefix = ($uris[0] == 'Vm') ? '../Includes/' : './';
	require_once($prefix.$className.'.php');
}