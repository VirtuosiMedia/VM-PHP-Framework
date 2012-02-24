<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the admin user install form for VM PHP Framework Suite
 * @requirements: PHP 5.3 or higher
 * @namespace Suite\Model\Install
 * @uses Vm\Db\Connect
 * @uses Vm\Form
 */
namespace Suite\Model\Install;

use \Vm\Db\Connect;
use \Vm\Form;

class Admin extends \Vm\Model {
	
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
		$password = (isset($_POST['password'])) ? $_POST['password'] : 1;

		$this->form = new Form(array());
		$this->form->text('name', array(
			'label' => array(
				'innerHtml'=>'Username'
			),
			'validators'=>array('Required'=>'A username is required.')
		));
		$this->form->text('email', array(
			'label' => array(
				'innerHtml'=>'Email'
			),
			'validators'=>array(
				'Required'=>'A user email address is required.',
				'Email'=>'Please enter a valid email address.'	
			)	
		));
		$this->form->password('password', array(
			'label' => array(
				'innerHtml'=>'Password'
			),
			'validators'=>array(
				'Required'=>'A password is required.',
				'Password'=>'Your password must contain one lowercase letter, one uppercase letter, one number, and be 
					at least 6 characters long.'
			)	
		));
		$this->form->password('confirm', array(
			'label' => array(
				'innerHtml'=>'Confirm Password'
			),
			'validators'=>array(
				'Required'=>'Password confirmation is required.',
				'Matches'=>array('Oops, your passwords don\'t match.', $password)
			)	
		));
		$this->form->submit(array('value'=>'Next Step', 'class'=>'submit'));
		$this->setData('adminForm', $this->form->render());
	}
	
	protected function processForm(){
		if ($this->form->submitted() && (!$this->form->errorsExist())){
			$config = \Suite\Config();
			$this->connectDb($config);
			
			$salt = md5($this->form->getValue('password').$config->salt.$this->form->getValue('email'));
			$passHash = hash("sha512", (string) $salt);
			
			$users = new \Db\Users($this->db);
			$users->name = $this->form->getValue('name');
			$users->email = $this->form->getValue('email');
			$users->password = $this->form->getValue('password');
			$users->insert();
			
			$groups = new \Db\Groups($this->db);
			$groups->name = 'Admins';
			$groups->insert();
			
			$userGroups = new \Db\Groups($this->db);
			$userGroups->userId = 1;
			$userGroups->groupId = 1;
			$userGroups->insert();
			
			$url = new \Vm\Url();
			$url->redirect('install.php?p=install-app-data');
		}
	}
	
	protected function connectDb($config){
		$connect = new Connect(
			$config->dbType, 
			$config->dbName, 
			$config->dbUsername, 
			$config->dbPassword, 
			$config->dbHost
		);
		$this->db = $connect->getDb();	
	}
	
}