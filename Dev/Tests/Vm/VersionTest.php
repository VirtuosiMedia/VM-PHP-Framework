<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Tests the Vm\Version class
 * @requirements PHP 5.2 or higher
 * @namespace Tests\Vm
 * @uses Vm\Version
 */
namespace Tests\Vm;

use \Vm\Version;

class VersionTest extends \Tests\Test {

	protected function testDefaultLicense(){
		$this->fixture = new Version();
		return $this->assertEqual($this->fixture->get('license'), 'MIT license');
	}

	protected function testDefaultLicenseUrl(){
		$this->fixture = new Version();
		return $this->assertEqual($this->fixture->get('licenseUrl'), 'http://www.opensource.org/licenses/mit-license.php');
	}

	protected function testDefaultPackage(){
		$this->fixture = new Version();
		return $this->assertEqual($this->fixture->get('package'), 'VM Framework');
	}

	protected function testDefaultPackageUrl(){
		$this->fixture = new Version();
		return $this->assertEqual($this->fixture->get('packageUrl'), 'http://www.virtuosimedia.com/vmframework');
	}

	protected function testDefaultRequirements(){
		$this->fixture = new Version();
		return $this->assertEqual($this->fixture->get('requirements'), 'PHP 5.2.7 or higher');
	}

	protected function testDefaultDescription(){
		$this->fixture = new Version();
		return $this->assertEqual($this->fixture->get('description'), 'VM Framework is an OOP framework built for use with PHP5');
	}

	protected function testDefaultAuthor(){
		$this->fixture = new Version();
		return $this->assertEqual($this->fixture->get('author'), 'Virtuosi Media Inc.');
	}

	protected function testDefaultAuthorUrl(){
		$this->fixture = new Version();
		return $this->assertEqual($this->fixture->get('authorUrl'), 'http://www.virtuosimedia.com/');
	}

	protected function testCustomVersion(){
		$this->fixture = new Version(array('version'=>'1.1'));
		return $this->assertEqual($this->fixture->get('version'), '1.1');
	}	
}