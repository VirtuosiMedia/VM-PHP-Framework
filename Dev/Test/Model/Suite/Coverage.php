<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Models the test coverage results for the test suite
 * @extends Vm_Model
 */
class Test_Model_Suite_Coverage extends Vm_Model {

	protected $results;
	protected $testData = array();

	/**
	 * @param array $results - The results array for the test suite
	 */
	function __construct(array $results){
		$this->results = $results;
		$this->compileStats();
		$this->calculateFunctionCoverageBar($this->testData, TRUE);
		$this->calculateStatementCoverageBar($this->testData, TRUE);
		$this->calculateTestsTable();		
	}

	protected function compileStats(){
		$this->testData['uncoveredMethods'] = array();
		$this->testData['numCoveredPublicMethods'] = 0;
		$this->testData['numPublicMethods'] = 0;
		$this->testData['passingStatements'] = 0;
		$this->testData['totalStatements'] = 0;
		
		foreach ($this->results as $name=>$results){	
			$this->testData['numCoveredPublicMethods'] += $results['numCoveredPublicMethods'];
			$this->testData['numPublicMethods'] += $results['numPublicMethods'];
			$this->testData['passingStatements'] += $results['passingStatements'];
			$this->testData['totalStatements'] += $results['totalStatements'];					
		}
	}
	
	/**
	 * @return array - An associative array of the test results
	 */
	public function getResults(){
		$data = $this->testData;
		$data['functionalCoverage'] = (int) number_format(($data['numCoveredPublicMethods']/$data['numPublicMethods'])*100);
		$data['statementCoverage'] = (int) number_format(($data['passingStatements']/$data['totalStatements'])*100);		
		return $data;
	}	
	
	/**
	 * @description Calculates the statistics and titles for the functional testing coverage
	 * @param array $classData - The class or suite data
	 * @param boolean $caption - TRUE if a caption should be generated, FALSE otherwise
	 * @return An associative array of the functional coverage results if for a class, FALSE otherwise
	 */
	protected function calculateFunctionCoverageBar(array $classData = array(), $caption = FALSE){
		$title = '<p>Functional coverage tests to see if each public method of your class is executed by your unit 
			tests, but it does not necessarily mean that the class has been thoroughly tested. For true quality test 
			coverage, test each possible state of your classes.</p>';
		$uncovered = (sizeof($classData['uncoveredMethods']) > 0) 
			? '<p>Uncovered methods for this class:</p><ul><li>'.implode('</li><li>', $classData['uncoveredMethods']).'</li></ul>' 
			: NULL;
		$none = (($classData['numCoveredPublicMethods'] == 0)&&($classData['numPublicMethods'] == 0)) 
			? '<p>This class did not contain any public methods to test, but it may extend another class.</p>' 
			: NULL;
		$functionCoverageClass = NULL;
		$passPercentage = 0;
		
		if (($classData['numCoveredPublicMethods'] != 0)&&($classData['numPublicMethods'] != 0)){
			$passPercentage = number_format(($classData['numCoveredPublicMethods']/$classData['numPublicMethods'])*100);
			$uncovered = "<p><strong>".$classData['numCoveredPublicMethods']." of ".$classData['numPublicMethods']." 
				public methods executed (".$passPercentage.'%).</strong></p>'.$uncovered;
			if ($passPercentage >= 90){
				$functionCoverageClass = 'pass';
			} else if ($passPercentage > 50){
				$functionCoverageClass = 'warning';
			} else {
				$functionCoverageClass = 'fail';
			}
			
			$functionCoverageTitle = ($caption) ? htmlspecialchars($title.$uncovered) : htmlspecialchars($uncovered);
			$functionCoverageCaption = ($caption) ? 'Suite Functional Coverage ('.$passPercentage.'%)' : NULL;
			$functionMeterText = NULL;
		} else if ($classData['numPublicMethods'] != 0){
			$uncovered = "<p><strong>0 of ".$classData['numPublicMethods']." public methods executed (0%).</strong>
				</p>".$uncovered;
			$functionCoverageTitle = ($caption) ? htmlspecialchars($title.$uncovered) : htmlspecialchars($uncovered);
			$functionCoverageCaption = ($caption) ? 'Suite Functional Coverage (0%)' : NULL;
			$functionMeterText = '0%';	
		} else {
			$functionCoverageTitle = ($caption) ? htmlspecialchars($title.$none) : htmlspecialchars($none);
			$functionCoverageCaption = ($caption) ? 'Suite Functional Coverage (N/A)' : NULL;
			$functionMeterText = 'Not Applicable';
		}
		
		if ($caption){
			$this->setData('functionCoverageStatus', $functionCoverageClass);
			$this->setData('functionCoveragePercentage', $passPercentage);
			$this->setData('functionCoverageCaption', $functionCoverageCaption);
			$this->setData('functionCoverageTitle', $functionCoverageTitle);
			$this->setData('functionMeterText', $functionMeterText);
			return FALSE;
		} else {
			return array(
				'functionCoverageStatus'=>$functionCoverageClass,
				'functionCoveragePercentage'=>$passPercentage,
				'functionCoverageCaption'=>$functionCoverageCaption,
				'functionCoverageTitle'=>$functionCoverageTitle,
				'functionMeterText'=>$functionMeterText
			);
		}
	}
	
	/**
	 * @description Calculates the statistics and titles for the statement testing coverage
	 * @param array $classData - The class or suite data
	 * @param boolean $caption - TRUE if a caption should be generated, FALSE otherwise
	 * @return An associative array of the statement coverage results if for a class, FALSE otherwise
	 */
	protected function calculateStatementCoverageBar(array $classData = array(), $caption = FALSE){
		$title = '<p>Statement coverage tests to see if each executable line of your class is run by your unit tests, 
			but it does not necessarily mean that the class has been thoroughly tested. For true quality test coverage, 
			test each possible state of your classes.</p>';
		$statementCoverageClass = NULL;
		$passPercentage = 0;
		
		if (($classData['passingStatements'] != 0)&&($classData['totalStatements'] != 0)){
			$passPercentage = number_format(($classData['passingStatements']/$classData['totalStatements'])*100);
			if ($passPercentage >= 90){
				$statementCoverageClass = 'pass';
			} else if ($passPercentage > 50){
				$statementCoverageClass = 'warning';
			} else {
				$statementCoverageClass = 'fail';
			}
			$uncovered = "<p><strong>".$classData['passingStatements']." of ".$classData['totalStatements']." 
				statements executed (".$passPercentage.'%).</strong></p>';
			
			$statementCoverageTitle = ($caption) ? htmlspecialchars($title.$uncovered) : htmlspecialchars($uncovered);
			$statementCoverageCaption = ($caption) ? 'Suite Statement Coverage ('.$passPercentage.'%)' : NULL;
			$statementMeterText = NULL;
		} else if ($classData['totalStatements'] != 0){
			$uncovered = "<p><strong>0 of ".$classData['totalStatements']." statements executed (0%).</strong></p>";
			$statementCoverageTitle = ($caption) ? htmlspecialchars($title.$uncovered) : htmlspecialchars($uncovered);
			$statementCoverageCaption = ($caption) ? 'Suite Statement Coverage (0%)' : NULL;
			$statementMeterText = '0%';					
		} else {
			$none = '<p>This class did not contain any executable statements to test, but it may extend another class.</p>';
			$statementCoverageTitle = ($caption) ? htmlspecialchars($title.$none) : htmlspecialchars($none);
			$statementCoverageCaption = ($caption) ? 'Suite Statement Coverage (N/A)' : NULL;
			$statementMeterText = 'Not Applicable';	
		}

		if ($caption){
			$this->setData('statementCoverageStatus', $statementCoverageClass);
			$this->setData('statementCoveragePercentage', $passPercentage);
			$this->setData('statementCoverageCaption', $statementCoverageCaption);
			$this->setData('statementCoverageTitle', $statementCoverageTitle);
			$this->setData('statementMeterText', $statementMeterText);
			return FALSE;
		} else {
			return array(
				'statementCoverageStatus'=>$statementCoverageClass,
				'statementCoveragePercentage'=>$passPercentage,
				'statementCoverageCaption'=>$statementCoverageCaption,
				'statementCoverageTitle'=>$statementCoverageTitle,
				'statementMeterText'=>$statementMeterText
			);
		}
	}	

	/**
	 * @description Compiles the test coverage data for each class
	 */
	protected function calculateTestsTable(){
		$suiteCoverage = array();
		foreach ($this->results as $name=>$results){
			$suiteCoverage[$name] = array(
				'functional'=>$this->calculateFunctionCoverageBar($results),
				'statement'=>$this->calculateStatementCoverageBar($results),
			);		
		}
		$this->setData('suiteCoverage', $suiteCoverage);
	}	
}