<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Renders the history of an individual unit test case
*/
class Tests_Test_Render_History {
	
	protected $historyData = array();
	protected $historyExists;
	protected $testedClassName;

	/**
	 * @param string $testedClassName - The name of the class being tested
	 * @param array $file - The contents of the file, as an array
	 * @param boolean $saveResults - Whether or not to save the test data
	 */		
	function __construct($testedClassName){
		$this->testedClassName = $testedClassName;
		$this->readHistory();				
	}

	/**
	 * Reads the history for the tested class and compiles it into an appropriate JSON format
	 */
	protected function readHistory(){
		if (file_exists('Tests/Test/Reports/'.$this->testedClassName.'Report.json')){
			$this->historyExists = TRUE;
			
			$this->historyData['tests']['title'] = 'Unit Tests';
			$this->historyData['tests']['colNames'] = array('Number of Tests');
			$this->historyData['tests']['rowNames'] = array();
			$this->historyData['coverage']['title'] = 'Code Coverage (%)';
			$this->historyData['coverage']['colNames'] = array('Functional Coverage', 'Statement Coverage');
			$this->historyData['coverage']['rowNames'] = array();
			$this->historyData['avgComplexity']['title'] = 'Cyclomatic Complexity';
			$this->historyData['avgComplexity']['colNames'] = array('Avg. Class Complexity');
			$this->historyData['avgComplexity']['rowNames'] = array();
			$this->historyData['refactor']['title'] = 'Code Cleanup (%)';
			$this->historyData['refactor']['colNames'] = array('Refactor Probability', 'Readability Factor');
			$this->historyData['refactor']['rowNames'] = array();
			$this->historyData['loc']['title'] = 'Lines of Code (LOC)';
			$this->historyData['loc']['colNames'] = array('Lines of Code');
			$this->historyData['loc']['rowNames'] = array();

			$data = file('Tests/Test/Reports/'.$this->testedClassName.'Report.json', FILE_IGNORE_NEW_LINES);
			foreach ($data as $record){
				$record = json_decode($record, TRUE);
				$this->historyData['tests']['rows'][0][] = $record['numTests'];
				$this->historyData['coverage']['rows'][0][] = $record['functionalCoverage'];
				$this->historyData['coverage']['rows'][1][] = $record['statementCoverage'];
				$this->historyData['avgComplexity']['rows'][0][] = $record['avgComplexity'];
				$this->historyData['refactor']['rows'][0][] = $record['refactor'];
				$this->historyData['refactor']['rows'][1][] = $record['readability'];
				$this->historyData['loc']['rows'][0][] = $record['loc'];		
			}
		} else {
			$this->historyExists = FALSE;
		}
	}	

	/**
	 * @return string - The history container as an HTML string
	 */	
	public function render(){
		if ($this->historyExists){
			$view = '<div id="history-for-'.$this->testedClassName.'" class="historyContainer">';
			$view .= '<div id="'.$this->testedClassName.'TestChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="'.$this->testedClassName.'TestChart" class="lines"></div></div>';
			$view .= '<p>The number of tests should increase over time until each possible state of '.$this->testedClassName.' has been tested.</p>';
			$view .= '<div id="'.$this->testedClassName.'TestChartData" style="display:none;">'.json_encode($this->historyData['tests']).'</div>';
			$view .= '<div id="'.$this->testedClassName.'CoverageChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="'.$this->testedClassName.'CoverageChart" class="lines"></div></div>';
			$view .= '<p>Functional coverage tests that each method in your class is called while statement coverage tests that each line of executable code is run.'.
					'You should try to achieve 100% coverage for both.</p>';
			$view .= '<div id="'.$this->testedClassName.'CoverageChartData" style="display:none;">'.json_encode($this->historyData['coverage']).'</div>';
			$view .= '<div id="'.$this->testedClassName.'ComplexityChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="'.$this->testedClassName.'ComplexityChart" class="lines"></div></div>';
			$view .= '<p>Cyclomatic Complexity measures how complex a given piece of code is based on the number of decision points it contains. This chart'.
					' gives the average complexity rating for all of '.$this->testedClassName.'\'s methods. Lower numbers are good, above 8 should be looked at,'.
					' and over 16 should be refactored.</p>';
			$view .= '<div id="'.$this->testedClassName.'ComplexityChartData" style="display:none;">'.json_encode($this->historyData['avgComplexity']).'</div>';
			
			$view .= '<div id="'.$this->testedClassName.'RefactorChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="'.$this->testedClassName.'RefactorChart" class="lines"></div></div>';
			$view .= '<div id="'.$this->testedClassName.'RefactorChartData" style="display:none;">'.json_encode($this->historyData['refactor']).'</div>';
			$view .= '<p>The Refactor Probability attempts gauge if a class needs to be refactored based on its complexity, readability, and length. '.
					'Readability simply measures how easily a class can be understood based on its complexity and the amount of commenting.</p>';			
			$view .= '<div id="'.$this->testedClassName.'LocChartContainer" class="tips" title="Click on the chart for a new chart type."><div id="'.$this->testedClassName.'LocChart" class="lines"></div></div>';
			$view .= '<p>This chart simply tracks the number of total lines of code in '.$this->testedClassName.' over time, including comments and whitespace.</p>';
			$view .= '<div id="'.$this->testedClassName.'LocChartData" style="display:none;">'.json_encode($this->historyData['loc']).'</div>';
		} else {	
			$view = '<div id="history-for-'.$this->testedClassName.'" class="historyContainer empty">';	
			$view .= '<p>History for this test class is not available. Please rerun the test suite and select \'Save Results\' to begin tracking the results of this test class over time.</p>';
		}		
		$view .= '</div>';
		return $view;			
	}
}