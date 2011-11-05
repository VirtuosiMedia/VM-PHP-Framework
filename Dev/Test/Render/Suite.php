<?php
/**
* @author: Virtuosi Media Inc.
* @license: MIT License
* Description: Renders the test suite
*/
class Tests_Test_Render_Suite {

	protected $coverage;
	protected $filterData = array();
	protected $history;
	protected $includeCoverage = FALSE;
	protected $includeMetrics =  FALSE;
	protected $metrics;
	protected $results;		
	protected $testSuite;
	
	function __construct(){
		$this->loadTestSuite();	
	}

	protected function loadTestSuite(){
		if ((isset($_POST['reports']))&&($_POST['reports'] == 'new')){
			$this->includeCoverage = (isset($_POST['coverage'])) ? TRUE : FALSE;
			$this->includeMetrics = (isset($_POST['metrics'])) ? TRUE : FALSE;
		} else {
			$this->loadReport();
		}
		
		$saveResults = (isset($_POST['saveResults'])) ? TRUE : FALSE;

		$this->testSuite = new Tests_Test_Suite('Vm', 'Tests/Vm', $this->includeCoverage, $this->includeMetrics, $saveResults);		
	}

	protected function loadReport(){
		if ((isset($_POST['reports']))&&(preg_match('/^[a-zA-Z0-9-.]*$/', $_POST['reports']))){
			if (file_exists('Tests/Test/Reports/Suite/'.$_POST['reports'])){
				$report = file('Tests/Test/Reports/Suite/'.$_POST['reports'], FILE_IGNORE_NEW_LINES);
				$this->filterData = json_decode($report[0], TRUE);
				$this->includeCoverage = $this->filterData['includeCoverage'];
				$this->includeMetrics = $this->filterData['includeMetrics'];
			}			
		} 
	}

	/**
	 * @param array $data - The filter data
	 */
	protected function setFilterData(array $data){
		$data = ((isset($_POST['reports']))&&($_POST['reports'] == 'new')) ? $data : $this->filterData;	
		if (sizeof($data['includedAuthors']) > 0){
			$this->testSuite->setAuthors($data['includedAuthors']);
		} else if (sizeof($data['excludedAuthors']) > 0){
			$this->testSuite->setExcludedAuthors($data['excludedAuthors']);
		}
		
		if (sizeof($data['includedGroups']) > 0){
			$this->testSuite->setGroups($data['includedGroups']);
		} else if (sizeof($data['excludedGroups']) > 0){
			$this->testSuite->setExcludedGroups($data['excludedGroups']);
		}

		if (sizeof($data['includedSubgroups']) > 0){
			$this->testSuite->setSubgroups($data['includedSubgroups']);
		} else if (sizeof($data['excludedSubgroups']) > 0){
			$this->testSuite->setExcludedSubgroups($data['excludedSubgroups']);
		}
	}	
	
	/**
	 * @return string - The rendered test suite overview for all tests
	 */	
	protected function renderSuiteOverview(){
		$view = '<ul id="suiteOverview" class="tabMenu">';
			$view .= '<li><a class="suiteOverviewTab firstTab active" href="#results-for-suiteOverview">Results</a></li>';
			if ($this->includeCoverage){
				$view .= '<li><a class="suiteOverviewTab tab" href="#coverage-for-suiteOverview">Coverage</a></li>';
			}
			if ($this->includeMetrics){
				$view .= '<li><a class="suiteOverviewTab tab" href="#metrics-for-suiteOverview">Metrics</a></li>';
			}
			$view .= '<li><a id="suiteOverviewHistoryTab" class="suiteOverviewTab tab" href="#history-for-suiteOverview">History</a></li>';
		$view .= '</ul>';
		$view .= '<div class="tabContent">';
			$view .= '<div class="titleContainer"><h3 class="title"><a href="">Test Suite Results</a></h3><a href="#top" class="topLink tips" title="Return to the top of the page"></a></div>';
				$view .= $this->results->render();
				if ($this->includeCoverage){
					$view .= $this->coverage->render();
				}
				if ($this->includeMetrics){
					$view .= $this->metrics->render();
				}
				$view .= $this->history->render();				
			$view .= '</div>';
		$view .= '</div>';
		return $view;			
	}

	protected function saveResults(){
		$results = array_merge($this->results->getResults(), $this->coverage->getResults(), $this->metrics->getResults());	
		$saveResults = new Tests_Test_Save_Suite($results, $this->includeCoverage, $this->includeMetrics);
		$saveResults->setAuthors($this->testSuite->getAuthors());
		$saveResults->setExcludedAuthors($this->testSuite->getExcludedAuthors());
		$saveResults->setGroups($this->testSuite->getGroups());
		$saveResults->setExcludedGroups($this->testSuite->getExcludedGroups());
		$saveResults->setSubgroups($this->testSuite->getSubgroups());
		$saveResults->setExcludedSubgroups($this->testSuite->getExcludedSubgroups());
		$saveResults->save();		
	}
	
	/**
	 * @return mixed - The rendered test results if they should be displayed, otherwise NULL
	 */	
	protected function getResults(){
		if ($this->form->displayResults()){
			$this->setFilterData($this->form->getFilterData());
			$this->testSuite->runAllTests();
			$testResults = $this->testSuite->getResults();
			
			$this->results = new Tests_Test_Render_Suite_Results($testResults);
			$this->coverage = new Tests_Test_Render_Suite_Coverage($testResults);
			$this->metrics = new Tests_Test_Render_Suite_Metrics($testResults);
			$this->history = new Tests_Test_Render_Suite_History($testResults);				
			
			if (isset($_POST['saveResults'])){			
				$this->saveResults();
			}

			$results = $this->renderSuiteOverview();
			$results .= implode('', $this->testSuite->getRenderedTests());	
		} else {
			$results = NULL;
		}
		return $results;
	}
	
	/**
	 * @return string - The rendered test suite and results
	 */
	public function render(){
		$this->form = new Tests_Test_Render_SelectionForm($this->testSuite);
		
		$view = '<ul id="suiteControls" class="tabMenu firstTabMenu">';
			$view .= '<li><a class="suiteControlsTab firstTab active" href="#reportGenerator">Reports</a></li>';
			$view .= '<li><a class="suiteControlsTab tab" href="#help">Help</a></li>';
		$view .= '</ul>';
		$view .= '<div class="tabContent">';
			$view .= $this->form->render();
			$view .= '<div id="help">';
				$view .= '<h3 class="title">Test Suite Help</h3>';
				$view .= '<p>VM PHP Framework allows you to run reports for your unit tests. It also provides test coverage analysis and code metrics statistics. ';
				$view .= 'By default, the testing suite tests VM PHP Framework files, but once you install the framework, you can also use it to begin testing your own code.</p>';
				$view .= '<p>Please read the following tutorials to learn how to best use the testing suite:</p>';
				$view .= '<ul>';
					$view .= '<li><a href="#">How to use the testing suite</a></li>';
					$view .= '<li><a href="testdocs.php">How to write unit tests for your own code</a></li>';
				$view .= '</ul>';						
			$view .= '</div>';		
		$view .= '</div>';
		$view .= $this->getResults();
		return $view;
	}
}