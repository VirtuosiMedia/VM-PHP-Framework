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
		$this->form->text('username', array(
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
			$this->connectDb();
			

		}
	}
	
	protected function connectDb(){
		$dbType = (isset($_POST['dbType'])) ? $_POST['dbType'] : NULL;
		$dbName = (isset($_POST['dbName'])) ? $_POST['dbName'] : NULL;
		$dbUsername = (isset($_POST['dbUsername'])) ? $_POST['dbUsername'] : NULL;
		$dbPassword = (isset($_POST['dbPassword'])) ? $_POST['dbPassword'] : NULL;
		$dbHost = (isset($_POST['dbHost'])) ? $_POST['dbHost'] : NULL;		
		
		$connect = new Connect($dbType, $dbName, $dbUsername, $dbPassword, $dbHost);
		$this->db = $connect->getDb();	
	}
	
}