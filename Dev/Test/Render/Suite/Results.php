<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Renders the unit test results for the test suite
*/
class Test_Render_Suite_Results {

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
	 * @return array - An associative array of the test results
	 */
	public function getResults(){
		$data = array(
			'numTestCases'=>$this->numTestCases,
			'numUnitTests'=>$this->numUnitTests,
			'avgTestTime'=>$this->avgTestTime,
			'totalTime'=>$this->totalTime,
			'successRate'=>$this->successRate,
			'failureRate'=>$this->failureRate
		);
		return $data;
	}
	
	/**
	 * @return string - The pass meter bar as an HTML string
	 */	
	protected function renderPassBar(){
		$passMeter = (sizeof($this->passedTests) == $this->numUnitTests) ? '<span class="pass" style="width:'.$this->successRate.'%"></span>' : '<span class="fail" style="width:'.$this->failureRate.'%"></span>';
		$passCaption = (sizeof($this->passedTests) == $this->numUnitTests) 
			? sizeof($this->passedTests).' of '.$this->numUnitTests.' tests passed (100%)' 
			: sizeof($this->failedTests) + sizeof($this->errorTests)." of ".$this->numUnitTests." tests failed ($this->failureRate%)";					
		return '<div class="meterContainer">'.$passMeter.'</div><span class="meterCaption">'.$passCaption.'</span>';
	}	

	/**
	 * @return string - The stats table for unit tests as an HTML string
	 */
	protected function renderStatsTable(){	
		$view = '<table class="statsTable" cellspacing="0" cellpadding="0" width="100%">';
		$view .= '<thead><tr><th>Test Status</th><th>Count</th></tr></thead>';
		$view .= '<tbody>';
		if (sizeof($this->passedTests) > 0){
			$view .= '<tr><td><span class="pass"></span>Passing</td><td>'.sizeof($this->passedTests).'</td></tr>';
		}
		if (sizeof($this->failedTests) > 0){
			$view .= '<tr><td><span class="fail"></span>Failed</td><td>'.sizeof($this->failedTests).'</td></tr>';
		}
		if (sizeof($this->errorTests) > 0){
			$view .= '<tr><td><span class="fail"></span>Errors</td><td>'.sizeof($this->errorTests).'</td></tr>';
		}
		if (sizeof($this->incompleteTests) > 0){
			$view .= '<tr><td><span class="notRun"></span>Incomplete</td><td>'.sizeof($this->incompleteTests).'</td></tr>';
		}
		if (sizeof($this->skippedTests) > 0){
			$view .= '<tr><td><span class="notRun"></span>Skipped</td><td>'.sizeof($this->skippedTests).'</td></tr>';
		}
		if (sizeof($this->slowTests) > 0){
			$view .= '<tr><td><span class="warning"></span>Slow</td><td>'.sizeof($this->slowTests).'</td></tr>';
		}			
		$view .= '</tbody></table>';
		return $view;		
	}

	protected function renderTestsTable(){
		$nonPassing = array_merge($this->failedTests, $this->errorTests, $this->incompleteTests, $this->skippedTests, $this->slowTests);
		if (sizeof($nonPassing) > 0){
			$testTypes = array('errorTests', 'failedTests', 'skippedTests', 'incompleteTests', 'slowTests');
			$titles = array('Tests With Errors', 'Failed Tests', 'Skipped Tests', 'Incomplete Tests', 'Slow Tests');
			$view = '';
			$view .= '<h3 class="tableTitle">Notable Results</h3>';
			$view .= '<table class="testsTable" cellspacing="0" cellpadding="0" width="100%">';
			$view .= '<thead><tr><th>Class</th><th>Test</th><th>Time/Status</th></tr></thead>';
			$view .= '<tbody>';
			
			foreach ($testTypes as $key=>$type){		
				foreach ($this->$type as $test){
					if (in_array($type, array('failedTests', 'errorTests'))) {
						$class = 'fail';
						$tdAttributes = ' class="tips" title="'.$test['error'].'"';
						if ($type == 'errorTests'){
							$td = '<td><span class="testFail tips" title="This test failed because of an unexpected error or exception in either your tested class or this unit test. Hover over the test name in the column to the left to see the resulting error message.">Error</span></td>';
						} else {
							$td = '<td>'.$test['time'].'</td>';
						}
					} else if (in_array($type, array('incompleteTests', 'skippedTests'))) {
						$tdAttributes = NULL;
						$class = 'notRun';							
						if ($type == 'incompleteTests'){
							$td = '<td><span class="testNotRun tips" title="'.$test['error'].'">Incomplete</span></td>';
						} else {
							$td = '<td><span class="testNotRun tips" title="'.$test['error'].'">Skipped</span></td>';
						}
					} else {
						$tdAttributes = NULL;
						$class = 'warning';
						$td = '<td class="warning tips" title="This test or function is a potential bottleneck and may need to be refactored.">'.$test['time'].'</td>';
					}
					$view .= '<tr><td><span class="'.$class.'"></span><a class="classLink tips" title="View this class" href="#'.$test['className'].'">'.$test['className'].'</a></td><td'.$tdAttributes.'>'.$test['testName'].'</td>'.$td.'</tr>';
				}
			}
			$view .= '</tbody></table>';
		} else {
			$view = '';
		}
		return $view;
	}
	
	/**
	 * @return string - The results container as an HTML string
	 */
	public function render(){
		$view = '<div id="results-for-suiteOverview" class="resultsContainer">';			
			$view .= '<div class="infoContainer">';
				$view .= $this->renderPassBar();
				$view .= '<ul>';
					$view .= '<li><strong>Test Cases</strong>: '.$this->numTestCases.'</li>';	
					$view .= '<li><strong>Unit Tests/Test Case</strong>: '.$this->avgNumUnitTests.'</li>';
					$view .= '<li><strong>Time/Unit Test</strong>: '.$this->avgTestTime.'</li>';
					$view .= '<li><strong>Total Elapsed Time</strong>: '.$this->totalTime.'</li>';
				$view .= '</ul>';				
			$view .= '</div>';		
			$view .= '<div class="resultsTableContainer">';
				$view .= $this->renderStatsTable();
			$view .= '</div>';
			$view .= $this->renderTestsTable();
		$view .= '</div>';
		return $view;
	}
}