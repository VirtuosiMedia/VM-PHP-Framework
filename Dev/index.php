<?php 
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require_once('Assets/Autoload.php');

$settings = array(
	'excludeFoldersFromDocs'=>array('Vm'),
	'installed'=>FALSE,
	'overridePath'=>NULL,
	'suiteTheme'=>'Centauri'
);

$suite = new Suite_Controller_Front($settings);
echo $suite->render();
?>