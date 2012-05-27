<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the install form for VM PHP Framework Suite
 * @requirements: PHP 5.3 or higher
 * @namespace Suite\Model\Install
 * @uses Vm\Db\Connect
 * @uses Vm\Form
 */
namespace Suite\Model\Install;

use \Vm\Db\Connect;
use \Vm\Form;

class Database extends \Vm\Model {
	
	protected $db;
	protected $form;
	
	function __construct(){
		$this->compileData();		
	}
	
	protected function compileData(){
		$this->createForm();
		$this->processForm();
	}
	
	protected function createForm(){
		$this->form = new Form(array());
		$this->form->select('dbType', array(
			'selectOptions'=>array(
				'mssql'=>'MS SQL',
				'mysql'=>'MySQL',
				'oracle'=>'Oracle',
				'postgresql'=>'PostgreSQL'
			),
			'selected'=>array('mysql'),
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
		
		$connect = new Connect($dbType, $dbName, $dbUsername, $dbPassword, $dbHost);
		if (!$connect->isConnected() && $this->form->submitted()){
			$this->form->addError('dbName', $connect->getError());
		} else {
			$this->db = $connect->getDb();
		}
		
		$this->form->text('dbName', array(
			'label' => array(
				'innerHtml'=>'Database Name'
			),
			'validators'=>array('Required'=>'A database name is required.')
		));
		$this->form->text('dbUsername', array(
			'label' => array(
				'innerHtml'=>'Database Username'
			),
			'validators'=>array('Required'=>'A database username is required.')	
		));
		$this->form->text('dbPassword', array(
			'label' => array(
				'innerHtml'=>'Database Password'
			),
			'validators'=>array('Required'=>'A database password is required.')	
		));
		$this->form->text('dbHost', array(
			'label' => array(
				'innerHtml'=>'Database Host'
			),
			'validators'=>array('Required'=>'A database host is required.')	
		));
		$this->form->submit(array('value'=>'Install Database', 'class'=>'submit'));
		$this->setData('databaseForm', $this->form->render());
	}
	
	protected function processForm(){
		if ($this->form->submitted() && (!$this->form->errorsExist())){
			$database = new \Vm\Db\FromXml($this->db, $this->form->getValue('dbType'));
			$database->install(array(
				'Suite/Sql/Groups.xml',
				'Suite/Sql/GroupPermissions.xml',
				'Suite/Sql/Messages.xml',
				'Suite/Sql/Notifications.xml',
				'Suite/Sql/Tools.xml',
				'Suite/Sql/Users.xml',
				'Suite/Sql/UserGroups.xml',
				'Suite/Sql/UserSessions.xml',
				'Suite/Sql/UserSettings.xml',
				'Suite/Sql/UserMessages.xml'
			), 'structure');
			
			$files = new \Vm\Db\File\Generator($this->db, $this->form->getValue('dbType'));
			$files->generateAll($this->form->getValue('dbType'), 'Db');			

			$this->createConfigFile();
			
			$url = new \Vm\Url();
			$url->redirect('install.php?p=install-admin-user');
		}
	}
	
	protected function createConfigFile(){
		$dbType = $this->form->getValue('dbType');
		$dbName = $this->form->getValue('dbName');
		$dbUsername = $this->form->getValue('dbUsername');
		$dbPassword = $this->form->getValue('dbPassword');
		$dbHost = $this->form->getValue('dbHost');
		$salt = md5(time());
		
		$fileContents = <<<EOT
<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description The config file for the VM PHP Framework Development Suite
 * @requirements PHP 5.3 or higher
 * @namespace Suite
 */
namespace Suite;

class Config {

	private \$configSettings = array(
		'dbType'=>'$dbType',												//The database type
		'dbName'=>'$dbName',												//The database name
		'dbUsername'=>'$dbUsername',											//The database username
		'dbPassword'=>'$dbPassword',												//The database password
		'dbHost'=>'$dbHost',											//The host name, usually localhost
		'dbPort'=>'',														//The database port
		'dbCharset'=>'',													//The database charset
		'salt'=>'$salt'						//The application salt for passwords
	);

	/**
	 * @description A magic method that returns the desired config setting string. Ex: \$config = new \Suite\Config(); 
	 * 		\$host = \$config->host;
	 * @param string \$key - The key of the setting that should be retrieved 
	 */
	function __get(\$key) {
		return \$this->configSettings[\$key];
	}
}
EOT;
		
		$file = new \Vm\File('Config.php', 'Suite');
		$file->write($fileContents);		
	}
}