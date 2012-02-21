<?php 
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require_once('Assets/Autoload.php');

$settings = array(
	'excludeFoldersFromDocs'=>array('Vm'),
	'fluxCapacitor'=>FALSE,
	'installed'=>FALSE,
	'overridePath'=>NULL,
	'suiteTheme'=>'Centauri'
);

$suite = new Suite\Controller\Install($settings);
$suite->setViewPath('Suite/View/Default/', $settings['overridePath']);
$suite->load();
echo $suite->render();