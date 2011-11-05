<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: The model for generating the install form for VM PHP Framework Suite
 * @requirements: PHP 5.2 or higher
 */
class Suite_Model_Install_Install extends Vm_Model {
	
	protected $form;
	
	function __construct(){
		$this->compileData();		
	}
	
	protected function compileData(){
		$this->createForm();
		$this->processForm();
		$this->setData('form', $this->form->render());
	}
	
	protected function createForm(){
		$this->form = new Vm_Form(array());
		$this->form->text('fullAppName', array(
			'label' => array(
				'innerHtml'=>'Full Application Name'
			),
			'filters'=>array('AddSlashes'),
			'validators'=>array(
				'Required'=>'An application name is required'
			)				
		));
		$this->form->text('shortAppName', array(
			'label' => array(
				'innerHtml'=>'Application Short Name (1 word)'
			),
			'validators'=>array(
				'Alpha'=>'Only letters are allowed',
				'Required'=>'A short name is required'
			)	
		));
		$this->form->textarea('appDesc', array(
			'label' => array(
				'innerHtml'=>'Application Description'
			),
			'filters'=>array('AddSlashes')	
		));
		$this->form->text('developer', array(
			'label' => array(
				'innerHtml'=>'Developer Name'
			),
			'filters'=>array('AddSlashes')	
		));
		$this->form->append('<p>If you\'ve already created a database for your app, enter your connection information below. Otherwise, leave blank to enter it manually in the Config.php file later.</p>');
		$this->form->select('dbType', array(
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
			$this->form->addError('dbName', $connect->getError());
		}
		
		$this->form->text('dbName', array(
			'label' => array(
				'innerHtml'=>'Database Name'
			)	
		));
		$this->form->text('dbUsername', array(
			'label' => array(
				'innerHtml'=>'Database Username'
			)	
		));
		$this->form->text('dbPassword', array(
			'label' => array(
				'innerHtml'=>'Database Password'
			)	
		));
		$this->form->text('dbHost', array(
			'label' => array(
				'innerHtml'=>'Database Host'
			)	
		));
		$this->form->submit(array('value'=>'Install', 'class'=>'submit'));		
	}
	
	protected function processForm(){
		$installMessage = NULL;
		if (($this->form->submitted()) && (!$this->form->errorsExist())){
			$installTable = '';
			
			$fullAppName = $this->form->getValue('fullAppName');
			$shortAppName = ucfirst(strtolower($this->form->getValue('shortAppName')));
			
			//Create app folders
			$folder = new Vm_Folder('.');
			$folder->createDir('admin');
			$folder->createDir('css');
			$folder->createDir('images');
			$folder->createDir('includes/'.$shortAppName);
			$folder->createDir('includes/Db');
			$folder->createDir('js');
			$folder->createDir('Dev/Tests/'.$shortAppName);
			$folder->createDir('Dev/Tests/Db');
			
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
			$config = new Tools_Installer_ConfigGen($shortAppName, $this->form->getValue('dbType'), $this->form->getValue('dbName'), $this->form->getValue('dbUsername'), $this->form->getValue('dbPassword'), $this->form->getValue('dbHost'));
			$bootstrap = new Tools_Installer_BootstrapGen($shortAppName);
			$version = new Tools_Installer_VersionGen($shortAppName, $fullAppName, $this->form->getValue('appDesc'), $this->form->getValue('developer'));
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
/*
			if (file_exists('includes/Version.php')){ //VM Framework is already installed
				$version = new Version();
				
				$message = $xml->h1('VM Framework Already Installed');
				$message .= $xml->p('Great job! You\'ve already installed VM Framework and generated an application skeleton for '.$xml->strong($version->get('name')).', so you can skip this step and start developing your application.');
				$content = $xml->div($message, array('class'=>'content'));
			} else {
				$installMessage = $xml->h2('Installation');
				$installMessage .= $xml->p('The VM Framework installer will generate a minimal application skeleton to enable a quick start for development.');
				$installMessage .= $this->form->render();
				$installMessage = $xml->div($installMessage, array('class'=>'content'));
			}
			$content = NULL;
*/			
		}		
	}
}