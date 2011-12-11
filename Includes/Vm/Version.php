<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Version and copyright information about VM Framework - Can be extended by an application to include 
 * 		application metadata
 * @namespace Vm
 */
namespace Vm;

class Version extends \Vm\Klass {

	/**
	 * @param array $options - optional - Metadata for the package
	 */
	function __construct($options = NULL){
		$defaultOptions = array(
			'copyright' => 'Copyright &copy; 2011, Virtuosi Media Inc.',
			'license' => 'MIT license',
			'licenseUrl' => 'http://www.opensource.org/licenses/mit-license.php',
			'version' => '0.9.0',
			'package' => 'VM Framework',
			'packageUrl' => 'http://www.virtuosimedia.com/vmframework',
			'requirements' => 'PHP 5.2.7 or higher',
			'description' => 'VM Framework is an OOP framework built for use with PHP5',
			'author' => 'Virtuosi Media Inc.',
			'authorUrl' => 'http://www.virtuosimedia.com/'
		);
		
		parent::__construct();
		$this->setOptions($options, $defaultOptions);	
	}

	/**
	 * @param string $metadata - The metadata to retrieve
	 * @return string - The requested metadata
	 */
	public function get($metadata){
		return $this->options[$metadata];
	}
}