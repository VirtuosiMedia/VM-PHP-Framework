<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Models the history of an individual unit test case
 * @namespace Test\Model
 */
namespace Test\Model;

class History {
	
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
	 * @description Reads the history for the tested class and compiles it into an appropriate JSON format
	 */
	protected function readHistory(){
		if (file_exists('Test/Reports/'.str_replace('\\', '-', $this->testedClassName).'Report.json')){
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

			$data = file('Test/Reports/'.str_replace('\\', '-', $this->testedClassName).'Report.json', FILE_IGNORE_NEW_LINES);
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
	 * @return Returns an associative array of the test history data for the current class.
	 */
	public function getData(){
		$history = array();
		if ($this->historyExists){
			$history['historyExists'] = TRUE;
			$history['historyTests'] = json_encode($this->historyData['tests']);
			$history['historyCoverage'] = json_encode($this->historyData['coverage']);
			$history['historyComplexity'] = json_encode($this->historyData['avgComplexity']);
			$history['historyRefactor'] = json_encode($this->historyData['refactor']);
			$history['historyLoc'] = json_encode($this->historyData['loc']);
		} else {
			$history['historyExists'] = FALSE;	
		}		
		return $history;			
	}
}