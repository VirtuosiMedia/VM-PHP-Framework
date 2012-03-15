<?php
/**
 * Autoloader function for VM PHP Framework
 * Files must adhere to the following naming convention:
 * 		Path: Folder/Folder1/Folder2/File.php
 * 		Namspace: Folder\Folder1\Folder2\File
 */

/**
 * @param string $className - The class to be loaded 
 */
function __autoload($className) {
	$uris = explode('\\', ltrim($className, '\\'));
	foreach($uris as $key=>$uri){
		$uris[$key] = ucfirst($uri);
	}
	$className = implode(DIRECTORY_SEPARATOR, $uris);
	$prefix = (in_array($uris[0], array('Ar', 'Db', 'Suite', 'Test', 'Tests', 'Tools'))) ? './' : '../Includes/';
	require_once($prefix.$className.'.php');
}