<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Loads the suite history files
 * @extends Vm_Model
 */
class Test_Model_Suite_History extends Vm_Model {

	protected $fileName = FALSE;
	protected $historyExists = FALSE;
	protected $reportName = FALSE;
	
	function __construct(){
		$this->checkHistory();
	}

	protected function checkHistory(){
		$this->getReportName();
		if (($this->reportName)){
			$this->setData('reportName', str_replace('.json', '', $this->reportName)); 
			$this->readHistory();
		} else {
			$this->setData('reportName', NULL);			
			$this->setData('historyExists', FALSE);
		}
	}
	
	protected function getReportName(){
		if ((isset($_POST['reports']))&&($_POST['reports'] != 'new')&&(preg_match('/^[a-zA-Z0-9-.]*$/', $_POST['reports']))){
			$this->reportName = str_replace('~', '-', str_replace('-', ' ', str_replace('---', '-~-', $_POST['reports'])));
			$this->fileName = $_POST['reports'];
		} else if (($_POST['reports'] == 'new')&&(isset($_POST['saveResultsName']))&&(preg_match('/^[a-zA-Z0-9 ]*$/', $_POST['saveResultsName']))){
			$this->reportName = ($_POST['saveResultsName'] != 'Report Name') ? $_POST['saveResultsName'] : NULL;
			$this->fileName = str_replace(' ', '-', $_POST['saveResultsName']).'.json';
		}
	}
	
	/**
	 * @description Reads the history for the tested class and compiles it into an appropriate JSON format
	 */
	protected function readHistory(){
		if (file_exists('Test/Reports/Suite/'.$this->fileName)){
			$this->setData('historyExists', TRUE);
			
			$this->historyData['tests']['title'] = 'Unit Tests';
			$this->historyData['tests']['colNames'] = array('Number of Unit Tests', 'Number of Test Cases');
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

			$data = file('Test/Reports/Suite/'.$this->fileName, FILE_IGNORE_NEW_LINES);
			foreach ($data as $index=>$record){
				if ($index != 0){
					$record = json_decode($record, TRUE);
					$this->historyData['tests']['rows'][0][] = $record['numUnitTests'];
					$this->historyData['tests']['rows'][1][] = $record['numTestCases'];
					$this->historyData['coverage']['rows'][0][] = $record['functionCoveragePercentage'];
					$this->historyData['coverage']['rows'][1][] = $record['statementCoveragePercentage'];
					$this->historyData['avgComplexity']['rows'][0][] = $record['avgComplexity'];
					$this->historyData['refactor']['rows'][0][] = $record['refactorPercentage'];
					$this->historyData['refactor']['rows'][1][] = $record['readabilityPercentage'];
					$this->historyData['loc']['rows'][0][] = $record['loc'];
				}		
			}
			
			$this->compileData();
		} else {
			$this->setData('historyExists', FALSE);
		}
	}
		
	/**
	 * @return Returns an associative array of the test history data for the current class.
	 */
	public function compileData(){
		$this->setData('historyTests', json_encode($this->historyData['tests']));
		$this->setData('historyCoverage', json_encode($this->historyData['coverage']));
		$this->setData('historyComplexity', json_encode($this->historyData['avgComplexity']));
		$this->setData('historyRefactor', json_encode($this->historyData['refactor']));
		$this->setData('historyLoc', json_encode($this->historyData['loc']));
	}	
}