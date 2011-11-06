<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: Renders the unit test results for the test suite
*/
class Test_Render_Suite_History {

	protected $historyExists = FALSE;
	protected $logFileName;
	
	function __construct(array $results){
		$this->results = $results;
		$this->generateLogFileName();
	}

	protected function generateLogFileName(){
		
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
	 * @return string - The results container as an HTML string
	 */
	public function render(){
		if ($this->historyExists){
			$this->compileStats();
			$view = '<div id="history-for-suiteOverview" class="historyContainer">';
			$view .= '<div id="suiteOverviewTestChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="suiteOverviewTestChart" class="lines"></div></div>';
			$view .= '<p>The number of tests should increase over time until each possible state of each class has been tested.</p>';
			$view .= '<div id="suiteOverviewTestChartData" style="display:none;">'.json_encode($this->historyData['tests']).'</div>';
			$view .= '<div id="suiteOverviewCoverageChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="suiteOverviewCoverageChart" class="lines"></div></div>';
			$view .= '<p>Functional coverage tests that each method in your class is called while statement coverage tests that each line of executable code is run.'.
					'You should try to achieve 100% coverage for both.</p>';
			$view .= '<div id="suiteOverviewCoverageChartData" style="display:none;">'.json_encode($this->historyData['coverage']).'</div>';
			$view .= '<div id="suiteOverviewComplexityChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="suiteOverviewComplexityChart" class="lines"></div></div>';
			$view .= '<p>Cyclomatic Complexity measures how complex a given piece of code is based on the number of decision points it contains. This chart'.
					' gives the average complexity rating for all of the classes in this test suite configuration. Lower numbers are good, above 8 should be looked at,'.
					' and over 16 should be refactored.</p>';
			$view .= '<div id="suiteOverviewComplexityChartData" style="display:none;">'.json_encode($this->historyData['complexity']).'</div>';
			
			$view .= '<div id="suiteOverviewRefactorChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="suiteOverviewRefactorChart" class="lines"></div></div>';
			$view .= '<div id="suiteOverviewRefactorChartData" style="display:none;">'.json_encode($this->historyData['refactor']).'</div>';
			$view .= '<p>The Refactor Probability attempts gauge if a class needs to be refactored based on its complexity, readability, and length. '.
					'Readability simply measures how easily a class can be understood based on its complexity and the amount of commenting.</p>';			
			$view .= '<div id="suiteOverviewLocChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="suiteOverviewLocChart" class="lines"></div></div>';
			$view .= '<p>This chart simply tracks the number of total lines of code in this test suite configuration over time, including comments and whitespace.</p>';
			$view .= '<div id="suiteOverviewLocChartData" style="display:none;">'.json_encode($this->historyData['loc']).'</div>';
		} else {	
			$view = '<div id="history-for-suiteOverview" class="historyContainer empty">';	
			$view .= '<p>History for this test suite configuration is not available. Please rerun the test suite and select \'Save Results\' to begin tracking the results of this test suite configuration over time.</p>';
		}		
		$view .= '</div>';
		return $view;
	}
}