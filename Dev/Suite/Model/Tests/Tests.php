<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Models the results of an individual unit test case
 * @namespace Suite\Model\Tests
 * @uses Test\Model
 */
namespace Suite\Model\Tests;

use \Test\Model;

class Tests extends \Vm\Model {
	
	protected $allData = array();
	protected $coverage;
	protected $coverageResults;	
	protected $file;
	protected $fileExists;
	protected $history;
	protected $includeCoverage = FALSE;
	protected $includeMetrics = TRUE;
	protected $metrics;
	protected $parents = array();
	protected $results;
	protected $saveResults = FALSE;
	protected $settings;
	protected $testData;
	protected $testedClassName;
	protected $testResults;
	
	/**
	 * @param string $testedClassName - The name of the class being tested
	 * @param array $testData - The metadata of the test class: array(author=>authorName, group=>groupName, 
	 * 		subgroup=>subgroupName, description=>description)
	 * @param array $testResults - The results of the test: array(name=subtestName, pass=bool, profile=testTime)
	 * @param array $inclusions - An array of boolean values, with the first being whether or not to include test 
	 * 		coverage, the second if metrics should be included in the test results.
	 * @param array $coverageResults - The xdebug coverage stats array
	 * @param array $settings - The framework settings array. 
	 * @param boolean $saveResults - Whether or not to save the test data
	 */
	function __construct(
		$testedClassName, 
		array $testData, 
		array $testResults,
		array $inclusions, 
		array $coverageResults = array(), 
		array $settings,
		$saveResults = FALSE
	){
		$this->testedClassName = $testedClassName;
		$this->testData = $testData;
		$this->testResults = $testResults;
		$this->includeCoverage = $inclusions[0];
		$this->includeMetrics = $inclusions[1];
		$this->coverageResults = $coverageResults;
		$this->settings = $settings;
		$this->saveResults = $saveResults;
		$this->getFile();
		$this->compileTestData();
	}
	
	protected function getFile(){
		$fileName = '../Includes/'.str_replace('_', '/', $this->testedClassName).'.php';
		if (file_exists($fileName)){
			$this->file = file($fileName, FILE_IGNORE_NEW_LINES);
			$this->fileExists = TRUE;

		} else {
			$this->file = array();
			$this->fileExists = FALSE;
		}		
	}
		
	/**
	 * @description Compiles the test results, coverage, metrics and history
	 */
	protected function compileTestData(){
		$this->results = new Model\Results(
			$this->testedClassName, 
			$this->testData, 
			$this->testResults, 
			$this->settings
		);
		$this->allData = array_merge($this->testData, $this->results->getData());
		
		if ($this->includeCoverage){
			$this->coverage = new Model\Coverage($this->testedClassName, $this->file, $this->coverageResults);
			$this->allData = array_merge($this->allData, $this->coverage->getData());
		}
		
		if ($this->includeMetrics){
			$coveredMethods = ($this->includeCoverage) ? $this->coverage->getCoveredMethods() : array();
			$this->metrics = new Model\Metrics($this->testedClassName, $this->file, $coveredMethods);
			$this->allData = array_merge($this->allData, $this->metrics->getData());
		}
		
		if ($this->saveResults){
			$save = new \Test\Save\Test(
				$this->testedClassName, 
				$this->file, 
				$this->allData, 
				$this->includeCoverage, 
				$this->includeMetrics
			);
		}
		
		$this->history = new Model\History($this->testedClassName);
		$this->allData = array_merge($this->allData, $this->history->getData());
	}

	/**
	 * @return An associative array with all of the test results data from unit testing, coverage, and metrics, 
	 * 		if all have been run
	 */
	public function getResults(){
		return $this->allData;
	}
}