<?php
/**
 * @author: Virtuosi Media Inc.
 * @license: MIT License
 * @group: VM PHP Framework
 * @subgroup: ACL
 * @description: Tests the Vm_Acl class
 * @requirements: PHP 5.2 or higher
 * @namespace Tests\Vm
 * @uses Vm\Acl
 */
namespace Tests\Vm;

use \Vm\Acl;

class AclTest extends \Tests\Test {
	
	protected function setUp(){
		$this->fixture = new Acl('admin');
	}

	protected function testGetId(){
		return $this->assertEqual($this->fixture->getId(), 'admin');
	}
	
	protected function testSetGetResourceList(){
		$resources = array('admin', 'frontend');
		$this->fixture->setResourceList($resources);
		return $this->assertEqual($this->fixture->getResourceList(), $resources);
	}
	
	protected function testSetGetPermissions(){
		$permissions = array('admin', 'frontend');
		$this->fixture->setResourceList($permissions);
		return $this->assertEqual($this->fixture->getResourceList(), $permissions);
	}

	protected function testAccessPermitted(){
		$user = new Acl('user');
		$user->setPermissions(array('admin', 'frontend'));
		$this->fixture->setResourceList($user->getPermissions());
		return $this->assertTrue($this->fixture->accessPermitted());
	}
	
	protected function testAccessDenied(){
		$user = new Acl('user');
		$user->setPermissions(array('blog', 'frontend'));
		$this->fixture->setResourceList($user->getPermissions());
		return $this->assertFalse($this->fixture->accessPermitted());
	}	
}