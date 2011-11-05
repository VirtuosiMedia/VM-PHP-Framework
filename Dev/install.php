<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Builds a unit testing suite by scanning a given directory
*/
require_once('Assets/Autoload.php');

//Environment and security checks
$phpVersionClass = (defined('PHP_VERSION_ID')) ? 'pass' : 'fail';
$phpVersion = ($phpVersionClass == 'pass') ? 'Current PHP Version: '.phpversion().'.' : 'Current PHP Version: '.phpversion().' - Version 5.2.7 or higher required'; 
$gdLibraryClass = (extension_loaded('gd') && function_exists('gd_info')) ? 'pass' : 'fail';
$gdLibrary = ($gdLibraryClass == 'pass') ? 'GD Library is enabled.' : 'GD Library must be enabled for some classes to work';
$zlibClass = (extension_loaded('zlib')) ? 'pass' : 'fail';
$zlib = ($zlibClass == 'pass') ? 'Zlib extension is enabled.' : 'Zlib extension is disabled. Some classes will not function properly.';
$pdoClass = (extension_loaded('pdo')) ? 'pass' : 'fail';
$pdo = ($pdoClass == 'pass') ? 'PDO extension is enabled.' : 'PDO extension is disabled. Database classes will not function properly.';
$mysqlPdoClass = (extension_loaded('pdo')) ? 'pass' : 'fail';
$mysqlPdo = ($mysqlPdoClass == 'pass') ? 'MySQL PDO extension is enabled.' : 'MySQL PDO extension is disabled. MySQL database classes will not function properly.';
$ctypeClass = (extension_loaded('ctype')) ? 'pass' : 'fail';
$ctype = ($ctypeClass == 'pass') ? 'Ctype extension is enabled.' : 'Ctype extension is disabled. Some validation classes will not function properly.';
$reflectionClass = (class_exists('Reflection', false)) ? 'pass' : 'fail';
$reflection = ($reflectionClass == 'pass') ? 'Reflection extension is enabled.' : 'Reflection extension is disabled. Unit testing suite will not function properly.';
$xdebugClass = (function_exists('xdebug_start_code_coverage')) ? 'pass' : 'warning';
$xdebug = ($xdebugClass == 'pass') ? 'Xdebug extension is enabled' : 'Xdebug extension is disabled. Code coverage analysis will not be available.';

//Install form
$install = new Vm_Form(array());
$xml = new Vm_Xml();
$install->text('fullAppName', array(
	'label' => array(
		'innerHtml'=>'Full Application Name'
	),
	'filters'=>array('AddSlashes'),
	'validators'=>array(
		'Required'=>'An application name is required'
	)				
));
$install->text('shortAppName', array(
	'label' => array(
		'innerHtml'=>'Application Short Name (1 word)'
	),
	'validators'=>array(
		'Alpha'=>'Only letters are allowed',
		'Required'=>'A short name is required'
	)	
));
$install->textarea('appDesc', array(
	'label' => array(
		'innerHtml'=>'Application Description'
	),
	'filters'=>array('AddSlashes')	
));
$install->text('developer', array(
	'label' => array(
		'innerHtml'=>'Developer Name'
	),
	'filters'=>array('AddSlashes')	
));
$install->append($xml->p('If you\'ve already created a database for your app, enter your connection information below. Otherwise, leave blank to enter it manually in the Config.php file later.'));
$install->select('dbType', array(
	'selectOptions'=>array('mysql'=>'mysql'),
	'label' => array(
		'innerHtml'=>'Database Type'
	), 
	'validators' => array('Alpha'=>'Letters only.')
));	

$dbType = (isset($_POST['dbType'])) ? $_POST['dbType'] : NULL;
$dbName = (isset($_POST['dbName'])) ? $_POST['dbName'] : NULL;
$dbUsername = (isset($_POST['dbUsername'])) ? $_POST['dbUsername'] : NULL;
$dbPassword = (isset($_POST['dbPassword'])) ? $_POST['dbPassword'] : NULL;
$dbHost = (isset($_POST['dbHost'])) ? $_POST['dbHost'] : NULL;

$connect = new Tools_Db_Connect($dbType, $dbName, $dbUsername, $dbPassword, $dbHost);
if (!$connect->getStatus()){
	$install->addError('dbName', $connect->getError());
}

$install->text('dbName', array(
	'label' => array(
		'innerHtml'=>'Database Name'
	)	
));
$install->text('dbUsername', array(
	'label' => array(
		'innerHtml'=>'Database Username'
	)	
));
$install->text('dbPassword', array(
	'label' => array(
		'innerHtml'=>'Database Password'
	)	
));
$install->text('dbHost', array(
	'label' => array(
		'innerHtml'=>'Database Host'
	)	
));
$install->submit(array('value'=>'Install', 'class'=>'submit'));

$installMessage = NULL;
if (($install->submitted()) && (!$install->errorsExist())){
	$installTable = '';
	
	$fullAppName = $install->getValue('fullAppName');
	$shortAppName = ucfirst(strtolower($install->getValue('shortAppName')));
	
	//Create app folders
	$folder = new Vm_Folder('.');
	$folder->createDir('admin');
	$folder->createDir('css');
	$folder->createDir('images');
	$folder->createDir('includes');
	$folder->createDir('includes/'.$shortAppName);
	$folder->createDir('includes/Db');
	$folder->createDir('includes/Vm');
	$folder->createDir('js');
	$folder->createDir('Tests/'.$shortAppName);
	$folder->createDir('Tests/Db');
	
	$vmFolder = new Vm_Folder('Vm');
	$vmFolders = $vmFolder->getFolders(TRUE, TRUE);
	foreach ($vmFolders as $sourceFolder){
		if (!is_dir('includes/'.$sourceFolder)){
			$folder->createDir('includes/'.$sourceFolder, 0755, TRUE);
		}
	}
	
	$installTable .= $xml->tr($xml->td("$shortAppName application folders generated.", array('class'=>'pass')));

	//Copy over VM Framework files
	$vmFiles = $vmFolder->getFiles(TRUE, NULL, TRUE);

	foreach ($vmFiles as $source){
		$sourceData = explode('/', $source);
		$sourceFileName = array_pop($sourceData);
		$sourcePath = implode('/', $sourceData);
		
		$sourceFile = new Vm_File($sourceFileName, $sourcePath);
		$sourceContents = $sourceFile->read();
		
		$copyFile = new Vm_File($sourceFileName, 'includes/'.$sourcePath);
		$copyFile->write($sourceContents);
	}	

	$installTable .= $xml->tr($xml->td("VM Framework copied into $shortAppName.", array('class'=>'pass')));
	
	//Generate the application files
	$autoloader = new Tools_Installer_AutoloadGen($shortAppName);
	$connect = new Tools_Installer_ConnectMySqlGen($shortAppName);
	$config = new Tools_Installer_ConfigGen($shortAppName, $install->getValue('dbType'), $install->getValue('dbName'), $install->getValue('dbUsername'), $install->getValue('dbPassword'), $install->getValue('dbHost'));
	$bootstrap = new Tools_Installer_BootstrapGen($shortAppName);
	$version = new Tools_Installer_VersionGen($shortAppName, $fullAppName, $install->getValue('appDesc'), $install->getValue('developer'));
	$adminIndex = new Tools_Installer_AdminIndexGen($shortAppName);
	$index = new Tools_Installer_IndexGen($shortAppName);
		
	$installTable .= $autoloader->getTableRow();
	$installTable .= $connect->getTableRow();
	$installTable .= $config->getTableRow();
	$installTable .= $bootstrap->getTableRow();
	$installTable .= $version->getTableRow();
	$installTable .= $adminIndex->getTableRow();
	$installTable .= $index->getTableRow();

	$message = $xml->h1('Installation Complete');
	$message .= $xml->p('Awesome! You just installed VM Framework and created an application skeleton for '.$fullAppName.'.');
	$message .= $xml->p('Happy developing!');
	$installed = $xml->table($xml->thead($xml->tr($xml->td($fullAppName.' Generation', array('class'=>'testTitle')))).$xml->tbody($installTable), array('cellspacing'=>'0', 'cellpadding'=>'0', 'width'=>'100%'));
	
	$content = $xml->div($message, array('class'=>'content')).$xml->div($installed, array('class'=>'content'));

} else { //The form has not been submitted
	if (file_exists('includes/Version.php')){ //VM Framework is already installed
		$version = new Version();
		
		$message = $xml->h1('VM Framework Already Installed');
		$message .= $xml->p('Great job! You\'ve already installed VM Framework and generated an application skeleton for '.$xml->strong($version->get('name')).', so you can skip this step and start developing your application.');
		$content = $xml->div($message, array('class'=>'content'));
	} else {
		$installMessage = $xml->h2('Installation');
		$installMessage .= $xml->p('The VM Framework installer will generate a minimal application skeleton to enable a quick start for development.');
		$installMessage .= $install->render();
		$installMessage = $xml->div($installMessage, array('class'=>'content'));
	}
	$content = NULL;
}

$checkHead = $xml->thead($xml->tr($xml->td('PHP Environment Check', array('class'=>'testTitle'))));
$checkBody = $xml->tr($xml->td($xml->span('', array('class'=>$phpVersionClass)).$phpVersion));
$checkBody .= $xml->tr($xml->td($xml->span('', array('class'=>$gdLibraryClass)).$gdLibrary));
$checkBody .= $xml->tr($xml->td($xml->span('', array('class'=>$zlibClass)).$zlib));
$checkBody .= $xml->tr($xml->td($xml->span('', array('class'=>$pdoClass)).$pdo));
$checkBody .= $xml->tr($xml->td($xml->span('', array('class'=>$mysqlPdoClass)).$mysqlPdo));
$checkBody .= $xml->tr($xml->td($xml->span('', array('class'=>$ctypeClass)).$ctype));
$checkBody .= $xml->tr($xml->td($xml->span('', array('class'=>$reflectionClass)).$reflection));
$checkBody .= $xml->tr($xml->td($xml->span('', array('class'=>$xdebugClass)).$xdebug));
$checkBody = $xml->tbody($checkBody);

$content .= $xml->div($xml->table($checkHead.$checkBody, array('cellspacing'=>'0', 'cellpadding'=>'0', 'width'=>'100%')), array('class'=>'content'));
$content .= $installMessage;

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>VM Framework Installer</title>
<link rel="stylesheet" type="text/css" href="Assets/Css/default.css"/>
<link rel="stylesheet" type="text/css" href="Assets/Css/tests.css"/>
<link rel="shortcut icon" href="Assets/Images/favicon.ico" type="image/x-icon" />
</head>
<body>
<div id="navContainer">
	<ul id="topNav">
		<li><a href="index.html" id="logo"></a></li>
		<li><a href="about.php">About</a></li>
		<li><a href="Docs/index.html">Docs</a></li>
		<li><a href="install.php" class="active">Install</a></li>
		<li><a href="security.php">Security</a></li>
		<li><a href="tests.php">Tests</a></li>
		<li><a href="tools.php">Tools</a></li>
	</ul>
</div>
<?php echo $content; ?>
</body>
</html>