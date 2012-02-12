<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the install form for VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
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
		$this->form->submit(array('value'=>'Next Step', 'class'=>'submit'));
		$this->setData('databaseForm', $this->form->render());
	}
	
	protected function processForm(){
		if ($this->form->submitted() && (!$this->form->errorsExist())){
//			$connect = new \Vm\Db\Factory\ToXml($this->db, $this->form->getValue('dbType'));
			$database = new \Vm\Db\FromXml($this->db, $this->form->getValue('dbType'));
			$database->install(array(
				'Suite/Sql/Messages.xml',
				'Suite/Sql/Notifications.xml',
				'Suite/Sql/Tools.xml',
				'Suite/Sql/Users.xml',
				'Suite/Sql/UserSettings.xml',
				'Suite/Sql/UserMessages.xml'
			), 'structure');
			
/*
			$connect->render('messages', 'Suite/Sql/Messages.xml');
			$connect->render('notifications', 'Suite/Sql/Notifications.xml');
			$connect->render('tools', 'Suite/Sql/Tools.xml');
			$connect->render('users', 'Suite/Sql/Users.xml');
			$connect->render('usersettings', 'Suite/Sql/UserSettings.xml');
			$connect->render('usermessages', 'Suite/Sql/UserMessages.xml');
//*/				
						
						
			$this->setData('databaseForm', "<p>Database Created!</p>");
		}
	}
}