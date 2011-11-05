<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Renders the results of an individual unit test case
*/
class Tests_Test_Render_Test {
	
	protected $allData = array();
	protected $coverage;
	protected $coverageResults;	
	protected $file;
	protected $fileExists;
	protected $history;
	protected $includeCoverage;
	protected $includeMetrics = TRUE;
	protected $metrics;
	protected $parents = array();
	protected $results;
	protected $saveResults = FALSE;
	protected $testData;
	protected $testedClassName;
	protected $testResults;
	
	/**
	 * @param string $testedClassName - The name of the class being tested
	 * @param array $testData - The metadata of the test class: array(author=>authorName, group=>groupName, subgroup=>subgroupName, description=>description)
	 * @param array $testResults - The results of the test: array(name=subtestName, pass=bool, profile=testTime)
	 * @param boolean $includeCoverage - Whether or not to include test coverage
	 * @param array $coverageResults - The xdebug coverage stats array
	 * @param boolean $saveResults - Whether or not to save the test data
	 */
	function __construct($testedClassName, array $testData, array $testResults, $includeCoverage = FALSE, array $coverageResults = array(), $saveResults = FALSE){
		$this->testedClassName = $testedClassName;
		$this->testData = $testData;
		$this->testResults = $testResults;
		$this->includeCoverage = $includeCoverage;
		$this->coverageResults = $coverageResults;
		$this->saveResults = $saveResults;
		$this->getFile();
		$this->compileTestData();
	}
	
	/**
	 * @param bool $includeMetrics - TRUE if metrics should be included in the test results, FALSE otherwise
	 */
	public function includeMetrics($includeMetrics){
		$this->includeMetrics = $includeMetrics;
	}

	protected function getFile(){
		$fileName = str_replace('_', '/', $this->testedClassName).'.php';
		if (file_exists($fileName)){
			$this->file = file($fileName, FILE_IGNORE_NEW_LINES);
			$this->fileExists = TRUE;

		} else {
			$this->file = array();
			$this->fileExists = FALSE;
		}		
	}
		
	/**
	 * Compiles the test results, coverage, metrics and history
	 */
	protected function compileTestData(){
		$this->results = new Tests_Test_Render_Results($this->testedClassName, $this->testData, $this->testResults);
		$this->coverage = new Tests_Test_Render_Coverage($this->testedClassName, $this->file, $this->includeCoverage, $this->coverageResults);
		$this->metrics = new Tests_Test_Render_Metrics($this->testedClassName, $this->file, $this->coverage->getCoveredMethods());

		$this->allData = array_merge($this->testData, $this->results->getResults(), $this->coverage->getCoverage(), $this->metrics->getClassMetrics());
		
		if ($this->saveResults){
			$save = new Tests_Test_Save_Test($this->testedClassName, $this->file, $this->allData, $this->includeCoverage, $this->includeMetrics);
		}
		
		$this->history = new Tests_Test_Render_History($this->testedClassName);
	}

	/**
	 * @return array - An associative array with all of the test results data from unit testing, coverage, and metrics, if all have been run
	 */
	public function getResults(){
		return $this->allData;
	}
	
	/**
	 * @return string - The rendered test results
	 */
	public function render(){
		$title = '<h3 class="title"><a href="#'.$this->testedClassName.'">'.$this->testedClassName.'</a></h3>';
		$view = '<ul id="'.$this->testedClassName.'" class="tabMenu">';
			$view .= '<li><a class="'.$this->testedClassName.'Tab firstTab active" href="#results-for-'.$this->testedClassName.'">Results</a></li>';
			if ($this->includeCoverage){
				$view .= '<li><a class="'.$this->testedClassName.'Tab tab" href="#coverage-for-'.$this->testedClassName.'">Coverage</a></li>';
			}
			if ($this->includeMetrics){
				$view .= '<li><a class="'.$this->testedClassName.'Tab tab" href="#metrics-for-'.$this->testedClassName.'">Metrics</a></li>';
			}
			$view .= '<li><a id="'.$this->testedClassName.'HistoryTab" class="'.$this->testedClassName.'Tab tab" href="#history-for-'.$this->testedClassName.'">History</a></li>';
		$view .= '</ul>';
				
		$view .= '<div class="tabContent">';
			$view .= '<div class="titleContainer">'.$title.'<a href="#top" class="topLink tips" title="Return to the top of the page"></a></div>';
			$view .= $this->results->render();
			if ($this->includeCoverage){
				$view .= $this->coverage->render();
			}
			if ($this->includeMetrics){
				$view .= $this->metrics->render();
			}
			$view .= $this->history->render();
		$view .= '</div>';
		return $view;
	}
}