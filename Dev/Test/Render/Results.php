<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Renders the unit test results of an individual unit test case
*/
class Tests_Test_Render_Results {

	protected $results = array();
	protected $testData;
	protected $testedClassName;
	protected $testLines = array();
	protected $testResults;
	protected $testTable;
	
	/**
	 * @param string $testedClassName - The name of the class being tested
	 * @param array $testData - The metadata of the test class: array(author=>authorName, group=>groupName, subgroup=>subgroupName, description=>description)
	 * @param array $testResults - The results of the test: array(name=subtestName, pass=bool, profile=testTime)
	 */ 	
	function __construct($testedClassName, array $testData, array $testResults){
		$this->testedClassName = $testedClassName;
		$this->testData = $testData;
		$this->testResults = $testResults;
		$this->compileTestData();			
	}

	/**
	 * Compiles the test data (not including the code coverage stats) for the test case
	 */
	protected function compileTestData(){
		$this->results['time'] = 0;
		$this->results['numTests'] = 0;
		$this->results['numPassingTests'] = 0;
		$this->results['numErrorTests'] = 0;
		$this->results['numFailedTests'] = 0;
		$this->results['numSkippedTests'] = 0;
		$this->results['numSlowTests'] = 0;
		$this->results['numIncompleteTests'] = 0;
		$this->results['errorTests'] = array();
		$this->results['failedTests'] = array();
		$this->results['passingTests'] = array();
		$this->results['skippedTests'] = array();
		$this->results['slowTests'] = array();		
		$this->results['incompleteTests'] = array();
				
		if (sizeof($this->testResults) > 0){	
			$numTestCaseTests = 0;
			$this->testTable = '<table class="testTable" cellspacing="0" cellpadding="0">';
			
			foreach ($this->testResults as $subtestName=>$result){
				if ($result['pass'] === TRUE){
					$class = 'pass';
					$tdAttributes = NULL;
					$this->results['numPassingTests'] = $this->results['numPassingTests'] + 1;
					$this->results['passingTests'][] = array('testName'=>$subtestName, 'time'=>$result['profile'], 'error'=>$result['error']);
					$time = NULL; 
				} else if (in_array($result['pass'], array('Skipped', 'Incomplete'))){					
					$class = 'notRun';
					$tdAttributes = ' class="tips" title="'.$result['error'].'"';
					$time = '<span class="testNotRun tips" title="'.$result['error'].'">'.$result['pass'].'</span>';
					if ($result['pass'] == 'Skipped'){
						$this->results['numSkippedTests'] = $this->results['numSkippedTests'] + 1;
						$this->results['skippedTests'][] = array('testName'=>$subtestName, 'time'=>NULL, 'error'=>$result['error']);
					} else {
						$this->results['numIncompleteTests'] = $this->results['numIncompleteTests'] + 1;
						$this->results['incompleteTests'][] = array('testName'=>$subtestName, 'time'=>NULL, 'error'=>$result['error']);
					}					
				} else {	
					$class = 'fail';
					$tdAttributes = ' class="tips" title="'.$result['error'].'"';
					if ($result['profile'] > 0){
						$this->results['numFailedTests'] = $this->results['numFailedTests'] + 1;
						$this->results['failedTests'][] = array('testName'=>$subtestName, 'time'=>$result['profile'], 'error'=>$result['error']);
					} else {
						$this->results['numErrorTests'] = $this->results['numErrorTests'] + 1;
						$this->results['errorTests'][] = array('testName'=>$subtestName, 'time'=>NULL, 'error'=>$result['error']);						
					}
					$time = NULL;
				}
				
				if ($result['profile'] > 0.05){
					$timeClass = ' class="warning tips" title="This test or function is a potential bottleneck and may need to be refactored."';
					$this->results['numSlowTests'] = $this->results['numSlowTests'] + 1;
					$this->results['slowTests'][] = array('testName'=>$subtestName, 'time'=>$result['profile'], 'error'=>$result['error']);						
				} else {
					$timeClass = NULL;
				}
				
				if (!isset($time)){
					$time = ($result['profile'] > 0) ? $result['profile'] : '<span class="testFail tips" title="This test failed because of an unexpected error or exception in either your tested class or this unit test. Hover over the test name in the column to the left to see the resulting error message.">Error</span>';
				}
				
				$this->results['numTests'] = ($class != 'notRun') ? $this->results['numTests'] + 1 : $this->results['numTests'];
				$this->results['time'] = ($class != 'notRun') ? $this->results['time'] + $result['profile'] : $this->results['time'];
	
				$this->testTable .= '<tr>';
				$this->testTable .= '<td'.$tdAttributes.'><span class="'.$class.'"></span>'.$subtestName.'</td>';
				$this->testTable .= '<td'.$timeClass.'>'.$time.'</td>';
				$this->testTable .= '</tr>';
			}
	
			$this->testTable .= '<tr><td></td><td class="tips" title="Total test case time in seconds">'.number_format($this->results['time'], 7).'</td></tr></table>';
		} else {
			$this->testTable = '<p class="fail">No tests could be found for this test case.</p>';
		}
	}	

	public function getResults(){
		return $this->results;
	}
	
	/**
	 * @return string - The results container as an HTML string
	 */	
	public function render(){	
		$passPercentage = ($this->results['numPassingTests'] == $this->results['numTests']) ? 100 : number_format(((($this->results['numTests'] - $this->results['numPassingTests'])/$this->results['numTests'])*100), 0);
		$passMeter = ($this->results['numPassingTests'] == $this->results['numTests']) ? '<span class="pass" style="width:'.$passPercentage.'%"></span>' : '<span class="fail" style="width:'.$passPercentage.'%"></span>';
		$passCaption = ($this->results['numPassingTests'] == $this->results['numTests']) 
			? $this->results['numPassingTests'].' of '.$this->results['numTests'].' tests passed (100%)' 
			: $this->results['numTests'] - $this->results['numPassingTests']." of ".$this->results['numTests']." tests failed ($passPercentage%)";
		
		$view = '<div id="results-for-'.$this->testedClassName.'" class="resultsContainer">';			
			$view .= '<div class="infoContainer">';
				$view .= '<div class="meterContainer">'.$passMeter.'</div>';
				$view .= '<span class="meterCaption">'.$passCaption.'</span>';
				$view .= '<ul>';
					$view .= '<li><strong>Group</strong>: '.$this->testData['group'].'</li>';
					$view .= '<li><strong>SubGroup</strong>: '.$this->testData['subgroup'].'</li>';
					$view .= '<li><strong>Test Author</strong>: '.$this->testData['author'].'</li>';
					$view .= '<li><strong>Description</strong>: '.$this->testData['description'].'</li>';
				$view .= '</ul>';
			$view .= '</div>';
			$view .= '<div class="resultsTableContainer">';
				$view .= $this->testTable;
			$view .= '</div>';			
		$view .= '</div>';
		return $view;		
	}
}