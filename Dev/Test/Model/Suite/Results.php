<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Models the unit test results for the test suite
 * @extends Vm\Model
 * @namespace Test\Model\Suite
 */
namespace Test\Model\Suite;

class Results extends Vm\Model {

	protected $avgNumUnitTests = 0;
	protected $avgTestTime = 0;
	protected $errorTests = array();
	protected $failedTests = array();
	protected $failureRate = 0;
	protected $incompleteTests = array();
	protected $numTestCases = 0;
	protected $numUnitTests = 0;
	protected $passedTests = array();
	protected $results;
	protected $skippedTests = array();
	protected $slowTests = array();
	protected $successRate = 0;
	protected $totalTime = 0;
	
	/**
	 * @param array $results - The results array for the test suite
	 */
	function __construct(array $results){
		$this->results = $results;
		$this->compileStats();
		$this->compileData();
	}

	protected function compileStats(){
		foreach ($this->results as $name=>$results){
			$this->totalTime += (isset($results['time'])) ? $results['time'] : 0;
			
			if ((isset($results['numErrorTests']))&&($results['numErrorTests'] > 0)){
				foreach ($results['errorTests'] as $test){
					$test['className'] = $name;
					$this->errorTests[] = $test;
				}
			}

			if ((isset($results['numFailedTests']))&&($results['numFailedTests'] > 0)){
				foreach ($results['failedTests'] as $test){
					$test['className'] = $name;
					$this->failedTests[] = $test;
				}
			}

			if ((isset($results['numIncompleteTests']))&&($results['numIncompleteTests'] > 0)){
				foreach ($results['incompleteTests'] as $test){
					$test['className'] = $name;
					$this->incompleteTests[] = $test;
				}
			}
			
			if ((isset($results['numPassingTests']))&&($results['numPassingTests'] > 0)){
				foreach ($results['passingTests'] as $test){
					$test['className'] = $name;
					$this->passedTests[] = $test;
				}
			}

			if ((isset($results['numSkippedTests']))&&($results['numSkippedTests'] > 0)){
				foreach ($results['skippedTests'] as $test){
					$test['className'] = $name;
					$this->skippedTests[] = $test;
				}
			}

			if ((isset($results['numSlowTests']))&&($results['numSlowTests'] > 0)){
				foreach ($results['slowTests'] as $test){
					$test['className'] = $name;
					$this->slowTests[] = $test;
				}
			}					
		}

		$this->numTestCases = sizeof($this->results);
		$this->numUnitTests = sizeof($this->passedTests) + sizeof($this->failedTests) + sizeof($this->errorTests);
		$this->avgTestTime = ($this->numUnitTests != 0) ? number_format(($this->totalTime / $this->numUnitTests), 7) : 0;
		$this->avgNumUnitTests = ($this->numTestCases != 0) ? round($this->numUnitTests/$this->numTestCases, 2) : 0;
		$this->successRate = ($this->numUnitTests != 0) ? round(((sizeof($this->passedTests) / $this->numUnitTests)*100), 2) : 0;
		$this->failureRate = ($this->numUnitTests != 0) ? round((((sizeof($this->failedTests) + sizeof($this->errorTests)) / $this->numUnitTests)*100), 2) : 0;	
	}

	/**
	 * @description Calculates the stats for the passed tests meter.
	 */	
	protected function calculatePassBar(){
		$resultsBarStatus = (sizeof($this->passedTests) == $this->numUnitTests) ? 'pass' : 'fail';
		$resultsBarPercentage = (sizeof($this->passedTests) == $this->numUnitTests)	? $this->successRate :$this->failureRate;
		$resultsBarCaption = (sizeof($this->passedTests) == $this->numUnitTests) 
			? sizeof($this->passedTests).' of '.$this->numUnitTests.' tests passed (100%)' 
			: sizeof($this->failedTests) + sizeof($this->errorTests)." of ".$this->numUnitTests." tests failed ($this->failureRate%)";					
		
		$this->setData('resultsBarStatus', $resultsBarStatus);
		$this->setData('resultsBarPercentage', $resultsBarPercentage);
		$this->setData('resultsBarCaption', $resultsBarCaption);
	}	

	/**
	 * @description Calculates the stats for the tests.
	 */
	protected function caclulateTestData(){
		$nonPassing = array_merge($this->failedTests, $this->errorTests, $this->incompleteTests, $this->skippedTests, $this->slowTests);
		$nonPassingTests = array();
		if (sizeof($nonPassing) > 0){
			$testTypes = array('errorTests', 'failedTests', 'skippedTests', 'incompleteTests', 'slowTests');		
			foreach ($testTypes as $key=>$type){		
				foreach ($this->$type as $test){
					if (in_array($type, array('failedTests', 'errorTests'))) {
						$statusClass = 'fail';
						$tdAttributes = ' class="tips" title="'.$test['error'].'"';
						if ($type == 'errorTests'){
							$statusAttributes = 'class="failText tips" 
								title="This test failed because of an unexpected error or exception in either your 
								tested class or this unit test. Hover over the test name in the column to the left to 
								see the resulting error message."';
							$status = 'Error';
							
						} else {
							$statusAttributes = NULL;
							$status = $test['time'];
						}
					} else if (in_array($type, array('incompleteTests', 'skippedTests'))) {
						$tdAttributes = NULL;
						$statusClass = 'notRun';
						$statusAttributes = 'class="notRunText tips" title="'.$test['error'].'"';
						$status = ($type == 'incompleteTests') ? 'Incomplete' : 'Skipped';
					} else {
						$tdAttributes = NULL;
						$statusClass = 'warning';
						$statusAttributes = 'class="warningText tips" title="This test or function is a potential 
							bottleneck and may need to be refactored."';
						$status = $test['time'];
					}
					$nonPassingTests[] = array(
						'statusClass'=>$statusClass,
						'className'=>$test['className'],
						'testName'=>$test['testName'],
						'testNameAttributes'=>$tdAttributes,
						'statusAttributes'=>$statusAttributes,
						'status'=>$status
					);
				}
			}
			
		} 

		$this->setData('numNonPassing', sizeof($nonPassing));
		$this->setData('nonPassing', $nonPassingTests);
	}
	
	/**
	 * @description Compiles all of the test data.
	 */	
	protected function compileData(){
		$this->calculatePassBar();
		$this->caclulateTestData();
		$this->setData('numUnitTests', $this->numUnitTests);		
		$this->setData('numPassedTests', sizeof($this->passedTests));
		$this->setData('numFailedTests', sizeof($this->failedTests));
		$this->setData('numErrorTests', sizeof($this->errorTests));
		$this->setData('numIncompleteTests', sizeof($this->incompleteTests));
		$this->setData('numSkippedTests', sizeof($this->skippedTests));
		$this->setData('numSlowTests', sizeof($this->slowTests));
		$this->setData('numTestCases', $this->numTestCases);
		$this->setData('avgNumUnitTests', $this->avgNumUnitTests);
		$this->setData('avgTestTime', $this->avgTestTime);
		$this->setData('totalTime', $this->totalTime);
	}
}