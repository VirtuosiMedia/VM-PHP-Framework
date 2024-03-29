<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Renders the history of a filtered test suite
 * @namespace Test\Save
 */
namespace Test\Save;

class Suite {

	protected $authors = array();
	protected $excludedAuthors = array();
	protected $excludedGroups = array();
	protected $excludedSubgroups = array();	
	protected $fileName;
	protected $groups = array();	
	protected $includeCoverage;
	protected $includeMetrics;
	protected $reportName;
	protected $results;
	protected $subgroups = array();	

	/**
	 * @param array $results - The test results array 
	 * @param boolean $includeCoverage - Whether or not to include test coverage
	 * @param boolean $includeMetrics - Whether or not to include code metrics 
	 */		
	function __construct(array $results, $includeCoverage = FALSE, $includeMetrics = FALSE){
		$this->results = $results;
		$this->includeCoverage = $includeCoverage;
		$this->includeMetrics = $includeMetrics;
		$this->getReportName();				
	}

	protected function getReportName(){
		if ((isset($_POST['reports']))&&($_POST['reports'] != 'new')&&(preg_match('/^[a-zA-Z0-9-.]*$/', $_POST['reports']))){
			$this->fileName = $_POST['reports'];
		} else if (($_POST['reports'] == 'new')&&(isset($_POST['saveResultsName']))&&(preg_match('/^[a-zA-Z0-9 ]*$/', $_POST['saveResultsName']))){
			$this->reportName = $_POST['saveResultsName'];
			$this->fileName = str_replace(' ', '-', $_POST['saveResultsName']).'.json';
		}
	}
	
	/**
	 * @description Sets the list of authors whose tests should be included
	 * @param array $authors - An array of test authors
	 */
	public function setAuthors(array $authors){
		$this->authors = $authors;
	}
	
	/**
	 * @description Sets the list of authors whose tests should be excluded
	 * @param array $authors - An array of test authors
	 */
	public function setExcludedAuthors(array $authors){
		$this->excludedAuthors = $authors;
	}

	/**
	 * @description Sets the list of groups for which tests should be included
	 * @param array $groups - An array of test groups
	 */
	public function setGroups(array $groups){
		$this->groups = $groups;
	}
	
	/**
	 * @description Sets the list of groups for which tests should be excluded
	 * @param array $groups - An array of test groups
	 */
	public function setExcludedGroups(array $groups){
		$this->excludedGroups = $groups;
	}

	/**
	 * @description Sets the list of subgroups for which tests should be included
	 * @param array $subgroups - An array of test subgroups
	 */
	public function setSubgroups(array $subgroups){
		$this->subgroups = $subgroups;
	}
	
	/**
	 * @description Sets the list of subgroups for which tests should be excluded
	 * @param array $subgroups - An array of test subgroups
	 */
	public function setExcludedSubgroups(array $subgroups){
		$this->excludedSubgroups = $subgroups;
	}	

	protected function getReportData(){
		$includeCoverage = ($this->includeCoverage) ? 'true' : 'false';
		$includeMetrics = ($this->includeMetrics) ? 'true' : 'false';

		$data = array(
			'filename'=>$this->fileName,
			'reportName'=>$this->reportName,
			'includeCoverage'=>$includeCoverage,
			'includeMetrics'=>$includeMetrics,
			'includedAuthors'=>$this->authors,
			'excludedAuthors'=>$this->excludedAuthors,
			'includedGroups'=>$this->groups,
			'excludedGroups'=>$this->excludedGroups,
			'includedSubgroups'=>$this->subgroups,
			'excludedSubgroups'=>$this->excludedSubgroups
		);		
		
		return json_encode($data);
	}
	
	/**
	 * Saves the results of the test suite run
	 */
	public function save(){
		$statementCoverage = ($this->includeCoverage) ? $this->results['statementCoveragePercentage'] : '';
		$functionalCoverage = ($this->includeCoverage) ? $this->results['functionCoveragePercentage'] : '';
		$avgComplexity = ($this->includeMetrics) ? $this->results['avgComplexity'] : '';
		$refactor = ($this->includeMetrics) ? $this->results['refactorPercentage'] : '';
		$readability = ($this->includeMetrics) ? $this->results['readabilityPercentage'] : '';
		$readability = ($readability > 100) ? 100 : $readability;
		$totalLoc = ($this->includeMetrics) ? $this->results['metricsLoc'] : '';
		
		$data = array(
			'datetime'=>date("M d, Y, H:i"),
			'numTestCases'=>$this->results['numTestCases'],
			'numUnitTests'=>$this->results['numUnitTests'],
			'statementCoveragePercentage'=>$statementCoverage,
			'functionCoveragePercentage'=>$functionalCoverage,
			'avgComplexity'=>$avgComplexity,
			'refactorPercentage'=>$refactor,
			'readabilityPercentage'=>$readability,
			'loc'=>$totalLoc,
			'totalTime'=>$this->results['totalTime']
		);

		if (file_exists('Test/Reports/Suite/'.$this->fileName)){
			$directive = 'a';
			$data = "\n".json_encode($data);
		} else {
			$directive = 'w';
			$data = $this->getReportData()."\n".json_encode($data);
		}

		$handle = fopen('Test/Reports/Suite/'.$this->fileName, $directive);
		fwrite($handle, $data);
		fclose($handle);			
	}
}