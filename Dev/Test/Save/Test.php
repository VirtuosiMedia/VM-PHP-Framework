<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Saves the history of an individual unit test case.
 * @namespace Test\Save
 */
namespace Test\Save;

class Test {
	
	protected $file;
	protected $includeCoverage;
	protected $includeMetrics;
	protected $results;
	protected $testedClassName;

	/**
	 * @param string $testedClassName - The name of the class being tested
	 * @param array $file - The file contents
	 * @param array $results - The test results array 
	 * @param boolean $includeCoverage - Whether or not to include test coverage
	 * @param boolean $includeMetrics - Whether or not to include code metrics 
	 */		
	function __construct($testedClassName, array $file, array $results, $includeCoverage = FALSE, $includeMetrics = FALSE){
		$this->testedClassName = $testedClassName;
		$this->file = $file;
		$this->results = $results;
		$this->includeCoverage = $includeCoverage;
		$this->includeMetrics = $includeMetrics;
		$this->saveResults();				
	}
	
	/**
	 * Saves the results of the test suite run
	 */
	protected function saveResults(){
		$statementCoverage = ($this->includeCoverage) ? $this->results['statementCoverage'] : '';
		$functionalCoverage = ($this->includeCoverage) ? $this->results['functionalCoverage'] : '';
		$avgComplexity = ($this->includeMetrics) ? $this->results['avgComplexity'] : '';
		$totalComplexity = ($this->includeMetrics) ? $this->results['totalComplexity'] : '';
		$refactor = ($this->includeMetrics) ? $this->results['refactor'] : '';
		$readability = ($this->includeMetrics) ? $this->results['readability'] : '';
		$readability = ($readability > 100) ? 100 : $readability;
		$totalLoc = ($this->includeMetrics) ? $this->results['totalLoc'] : sizeof($this->file);
		
		$data = array(
			'datetime'=>date("M d, Y, H:i"),
			'numTests'=>$this->results['numTests'],
			'statementCoverage'=>$statementCoverage,
			'functionalCoverage'=>$functionalCoverage,
			'avgComplexity'=>$avgComplexity,
			'totalComplexity'=>$totalComplexity,
			'refactor'=>$refactor,
			'readability'=>$readability,
			'loc'=>$totalLoc,
			'time'=>$this->results['time']
		);

		$data = json_encode($data);
		
		$logFile = 'Test/Reports/'.$this->testedClassName.'Report.json';
		$directive = (file_exists($logFile)) ? 'a' : 'w';
		$data = ($directive == 'a') ? "\n".$data : $data; //Done for parsing purposes

		$handle = fopen($logFile, $directive);
		fwrite($handle, $data);
		fclose($handle);			
	}	
}