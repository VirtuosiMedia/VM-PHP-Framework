<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Tests the Vm_View class
* Requirements: PHP 5.2 or higher
*/
class Tests_Vm_ViewTest extends Tests_Test {
	
	protected function setUp(){
		$this->fixture = new Vm_View();
	}
	
	protected function testAppendDefault(){
		$this->fixture->append('This is a test data string');
		return $this->assertEqual($this->fixture->render(), 'This is a test data string');
	}
	
	protected function testAppendKey(){
		$this->fixture->append('This is a test data string', 'data');
		return $this->assertEqual($this->fixture->render('data'), 'This is a test data string');
	}

	protected function testAppendFileTrueFileExists(){
		return $this->assertTrue($this->fixture->appendFile('Tests/Test/Assets/test.txt'));
	}	
	
	protected function testAppendFileFalseFileDoesNotExist(){
		return $this->assertFalse($this->fixture->appendFile('Tests/Test/Assets/test2.txt'));
	}	
	
	protected function testAppendFileWithoutKey(){
		$this->fixture->appendFile('Tests/Test/Assets/test.txt');		
		return $this->assertEqual($this->fixture->render(), 'Hello World, I am for testing.');
	}
	
	protected function testAppendFileWithKey(){
		$this->fixture->appendFile('Tests/Test/Assets/test.txt', 'view');		
		return $this->assertEqual($this->fixture->render('view'), 'Hello World, I am for testing.');
	}

	protected function testAppendFilesWithoutKey(){
		$this->fixture->appendFiles(array('Tests/Test/Assets/test.txt', 'Tests/Test/Assets/test.txt'));		
		return $this->assertEqual($this->fixture->render(), 'Hello World, I am for testing.Hello World, I am for testing.');
	}	

	protected function testAppendFilesWithKey(){
		$this->fixture->appendFiles(array('Tests/Test/Assets/test.txt', 'Tests/Test/Assets/test.txt'), 'view');		
		return $this->assertEqual($this->fixture->render('view'), 'Hello World, I am for testing.Hello World, I am for testing.');
	}	
	
	protected function testClearWithNoKey(){
		$this->fixture->append('Hello')->clear();
		return $this->assertNull($this->fixture->render());
	}
	
	protected function testClearWithKey(){
		$this->fixture->append('Hello', 'view')->clear('view');
		return $this->assertNotEqual($this->fixture->render('view'), 'Hello');
	}	
}
?>