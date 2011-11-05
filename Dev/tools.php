<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Loads tools for developing with VM Framework
*/
require_once('Assets/Autoload.php');

if (file_exists('includes/Version.php')){ //VM Framework is already installed
	$version = new Version();
	$xml = new Vm_Xml();
	
	$message = 'Use the tools below to generate files for your '.$xml->strong($version->get('name')).' application.';

	$configClass = $version->get('package').'_Config';
	$config = new $configClass();
	
	$connect = new Tools_Db_Connect($config->dbType, $config->dbName, $config->username, $config->password, $config->host);
	if ($connect->getStatus() && $config->dbType && $config->dbName && $config->username && $config->password && $config->host){
		$dbStatusBody = $xml->tbody($xml->tr($xml->td('Database Connection Successful', array('class'=>'pass'))));
		
		$dbFilesForm = new Vm_Form(array(), array('submittedCheckName'=>'dbFilesForm'));
		$dbFilesForm->hidden('test', array('attributes'=>array('value'=>'test')));
		$dbFilesForm->submit(array('value'=>'Generate DB Files', 'class'=>'submit'));
		
		if (($dbFilesForm->submitted()) && (!$dbFilesForm->errorsExist())){
			$generate = new Tools_Db_Files_Generate();
			Vm_Url::redirect('./tools.php');
		} 
		$h2 = $xml->h2('Generate Database Files');
		$p = $xml->p('Allow VM Framework to automatically generate the database files from your application database. These files will allow you to work with your database in a safe, secure, and efficient way that minimizes code while still writing SQL-like statements. You can write your database code once and VM Framework will do the translation work to multiple database management systems with a simple switch statement.');
		$p .= $xml->p($xml->strong('Note').': If your database table names contain underscores, the underscores will not appear in generated database file or class names, though your database will not be changed. For example, a table named "admin_users" will appear in "includes/Db/AdminUsers.php" with a class name of "Db_AdminUsers". This is done to avoid autoloader conflicts.');
		$content = $xml->div($h2.$p.$dbFilesForm->render(), array('class'=>'content'));
	} else {
		$dbStatusBody = $xml->tbody($xml->tr($xml->td('Database Connection Failed', array('class'=>'fail'))));

		//Generate Database Form	
		$dbForm = new Vm_Form(array(), array('submittedCheckName'=>'dbForm'));
		$dbForm->append($xml->p('If you\'ve already created a database for your app, enter your connection information below. '.$xml->strong('Note').': If you have a custom config file, enter the database information manually as submitting this form will overwrite the existing config file.'));
		$dbForm->select('dbType', array(
			'selectOptions'=>array('mysql'=>'mysql'),
			'label' => array(
				'innerHtml'=>'Database Type'
			), 
			'validators' => array('Alpha'=>'Letters only.')
		));	
	
		$dbForm->text('dbName', array(
			'attributes'=>array('checked'=>'checked'),
			'label' => array(
				'innerHtml'=>'Database Name'
			)	
		));
		$dbForm->text('dbUsername', array(
			'attributes'=>array('checked'=>'checked'),
			'label' => array(
				'innerHtml'=>'Database Username'
			)	
		));
		$dbForm->text('dbPassword', array(
			'attributes'=>array('checked'=>'checked'),
			'label' => array(
				'innerHtml'=>'Database Password'
			)	
		));
		$dbForm->text('dbHost', array(
			'attributes'=>array('checked'=>'checked'),
			'label' => array(
				'innerHtml'=>'Database Host'
			)	
		));
		$dbForm->submit(array('value'=>'Install', 'class'=>'submit'));
		
		if (($dbForm->submitted()) && (!$dbForm->errorsExist())){
			$config = new Tools_Installer_ConfigGen($version->get('package'), $dbForm->getValue('dbType'), $dbForm->getValue('dbName'), $dbForm->getValue('dbUsername'), $dbForm->getValue('dbPassword'), $dbForm->getValue('dbHost'));
			Vm_Url::redirect('./tools.php');
		} else {
			$content = $xml->div($dbForm->render(), array('class'=>'content'));
		}			
	}	

	$dbFiles = new Tools_Db_Files_Check();
	
	$dbStatusHead = $xml->thead($xml->tr($xml->td('Database Status', array('class'=>'testTitle'))));
	$dbStatusBody .= $xml->tbody($xml->tr($xml->td($dbFiles->getFilesMessage(), array('class'=>$dbFiles->getFilesClass()))));
	$dbStatus = $xml->div($xml->table($dbStatusHead.$dbStatusBody, array('cellspacing'=>'0', 'cellpadding'=>'0', 'width'=>'100%')), array('class'=>'content'));
		
} else { //VM Framework has not yet been installed
	$message = 'You must first install VM Framework to access the framework tools. You can install the framework through the install tab above.';
	$content = NULL;
	$dbStatus = NULL;
}	

echo '<?xml version="1.0" encoding="UTF-8" ?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>VM Framework Tools</title>
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
		<li><a href="install.php">Install</a></li>
		<li><a href="security.php">Security</a></li>
		<li><a href="tests.php">Tests</a></li>
		<li><a href="tools.php" class="active">Tools</a></li>
	</ul>
</div>
<div class="content">
<h1>VM Framework Tools</h1>
<p><?php echo $message; ?></p>
</div>
<?php echo $dbStatus; ?>
<?php echo $content; ?>
</body>
</html>