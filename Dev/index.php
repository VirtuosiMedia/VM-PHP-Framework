<?php 
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require_once('Assets/Autoload.php');

if (!file_exists('Suite/Config.php')){
	$url = new Vm\Url();
	$url->redirect('install.php');
}

$settings = array(
	'excludeFoldersFromDocs'=>array('Vm'),
	'fluxCapacitor'=>FALSE,
	'installed'=>FALSE,
	'overridePath'=>NULL,
	'suiteTheme'=>'Centauri'
);

$suite = new Suite\Controller\Front($settings);
echo $suite->render();