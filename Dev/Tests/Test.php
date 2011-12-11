<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit testing class, inspired in part by simpletest and PHPUnit
 * @namespace Tests
 */
namespace Tests;

class Test {

	protected $className;
	protected $coverage = array();
	protected $executedTests = array();
	protected $fixture = NULL;
	protected $includeCoverage;
	protected $testedClassName;
	protected $testSuite = array();
	
	/**
	 * @param boolean $includeCoverage - optional - Whether or not to include code coverage statistics (it will run 
	 * 		slower), defaults FALSE
	 */
	function __construct($includeCoverage = FALSE){
		$this->includeCoverage = $includeCoverage;
		$this->className = get_class($this);
		$this->loadTestedClass();
		$methods = get_class_methods($this->className);
		foreach ($methods as $method){
			if (preg_match("#^(test)#", $method)) {
				$this->addTest($method);
			}
		}
	}

	/**
	 * @description Loads a test assertion class using magic methods, with the method name as the class name and the 
	 * 		parameters in an array for the test class
	 * @param string $className - the class name to be loaded - Note: should not be the full class name
	 * @param array $params - The parameters to be loaded into the class. Note: Up to 4 parameters are allowed
	 * @return boolean - TRUE if the test passes, FALSE otherwise
	 */
	function __call($className, $params) {
		$className = (strstr($className, 'assert')) 
			? str_replace('assert', '\Test\Assert\\', $className) 
			: '\Test\\'.$className;
		$reflect = new \ReflectionMethod($className, '__construct');
		$numParams = sizeof($reflect->getParameters());

		switch ($numParams){
			case 1:
				$assert = new $className($params[0]);
				break;
			case 2: 
				$assert = new $className($params[0], $params[1]);
				break;
			case 3:
				$assert = new $className($params[0], $params[1], $params[2]);
				break;
			case 4: 
				$assert = new $className($params[0], $params[1], $params[2], $params[3]);
				break;
			default:
				$assert = new $className();
				break;				
		}
		return $assert;
	}	
	
	/**
	 * @description A function run before every test, meant to be overwritten
	 */
	protected function setUp(){	}

	/**
	 * @description A function run after every test, it calls the clearFixture function, but can be overwritten
	 */
	protected function tearDown(){
		$this->fixture = NULL;
	}
	
	/**
	 * @description Adds a test to the test suite for the class
	 * @param string $testName - The name of the test method, this should correspond to the name of the class method 
	 * 		being tested, prepended with the word 'test'
	 * @return - The object for chaining
	 */
	protected function addTest($testName){
		$this->testSuite[] = $testName;
		return $this;
	}
	
	/**
	 * @description Loads the tested class, if the autoload function is not defined
	 */
	protected function loadTestedClass(){
		$this->testedClassName = preg_replace('#(Test)$#', '', $this->className);
		$this->testedClassName = str_replace('Tests\\', '', $this->testedClassName);
		$className = '../Includes/'.str_replace('\\', '/', $this->testedClassName);
		require_once($className.'.php');
	}

	/**
	 * @param string $testName - The name of the test that generated the coverage
	 * @param array $coverage - The xdebug_get_code_coverage array
	 */
	protected function processCoverage($testName, $coverage){
		$testedClass = str_replace('_', '/', $this->testedClassName).'.php';
		foreach ($coverage as $fileName=>$results){
			$fileName = str_replace('\\', '/', $fileName);
			if (strstr($fileName, $testedClass)){
				$this->coverage[$testName] = $results;
				if (!isset($this->coverage['all'])){
					$this->coverage['all'] = $results;
				} else {
					foreach($results as $lineNumber=>$executed){
						if (in_array($lineNumber, array_keys($this->coverage['all']))){
							$this->coverage['all'][$lineNumber] += 1;
						} else {
							$this->coverage['all'][$lineNumber] = 1;
						}
					}
				}
			} 	
		}		
	}
	
	/**
	 * @return array - An multi-dimensional array with the test name as the key, an array of line number/number of 
	 * 		executions times pairs as the value
	 */
	public function getCoverage(){
		return $this->coverage;
	}
	
	/**
	 * @return string The name of the class being tested (not the test case name)
	 */
	public function getTestedClassName(){
		return $this->testedClassName;
	}
	
	/**
	 * @return int The number of public methods in the class being tested (not the test case)
	 */
	public function getNumMethodsTestedClass(){
		return sizeof(get_class_methods($this->testedClassName));
	}
	
	/**
	 * @description Runs a single test for the class
	 * @param string $testName - The name of the test that should be run
	 * @return - The object for chaining
	 */
	public function runTest($testName){
		if (in_array($testName, $this->testSuite)){
			$status = FALSE;
			$exception = NULL;
			$profile = NULL;
			set_error_handler(array('\Tests\Test','exceptionErrorHandler'));
			
			try {
				if ((function_exists('xdebug_start_code_coverage'))&&($this->includeCoverage)){
					xdebug_start_code_coverage();
				}
				
				$startTime = microtime(TRUE);
				$this->setUp();
				$status = $this->$testName();
				$this->tearDown();
				$endTime = microtime(TRUE);
				
				if ((function_exists('xdebug_start_code_coverage'))&&($this->includeCoverage)){
					$coverage = xdebug_get_code_coverage();
					xdebug_stop_code_coverage();
					$this->processCoverage($testName, $coverage);
				}
				$profile = number_format((($endTime - $startTime)), 7);
				
			} catch (\Exception $e){
				$exception = $e->getMessage();
				$status = new \Test\Assert();
				$status->setResult(FALSE);
				$status->setError($exception);
			} 
			
			$this->executedTests[$testName] = array(
				'pass'=>$status->getResult(), 
				'error'=>$status->getError(), 
				'profile'=>$profile
			);
			restore_error_handler();
		} else {
			throw new \Test\Exception("$testName does not exist or is not a valid test.");
		}
		return $this;
	}
			
	public function exceptionErrorHandler($errno, $errstr, $errfile, $errline ) {
    	throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
	}	
	
	/**
	 * @description Runs multiple tests for the class
	 * @param array $testNames - The names of each test to be run
	 * @return - The object for chaining
	 */
	public function runTests(array $testNames){
		foreach ($testNames as $testName){
			$this->runTest($testName);
		}
		return $this;
	}
	
	/**
	 * @description Runs all tests for the class
	 * @return - The object for chaining
	 */
	public function runAllTests(){
		foreach ($this->testSuite as $testName){
			$this->runTest($testName);
		}
		return $this;
	}

	/**
	 * @description Gets the name of the test class that is being run
	 * @return string - The test class name
	 */
	public function getName(){
		return $this->className;
	}	
	
	/**
	 * @description Gets the names of the tests in the test suite
	 * @return array - The test suite
	 */
	public function getTestSuite(){
		return $this->testSuite;
	}

	/**
	 * @description Gets the number of tests in the test suite
	 * @return int - The number of tests
	 */
	public function getNumTests(){
		return sizeof($this->testSuite);
	}
	
	/**
	 * @description Gets the results of each test run
	 * @return array - The test results
	 */
	public function getResults(){
		return $this->executedTests;
	}	
}	