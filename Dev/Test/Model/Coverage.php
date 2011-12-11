<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Models the unit test coverage results of an individual unit test case.
 * @namespace Test\Model
 */
namespace Test\Model;

class Coverage {
	
	protected $coverageResults;
	protected $coveredMethods = array();
	protected $file;
	protected $fileExists;
	protected $lineCheck = array();
	protected $numCoveredPublicMethods = 0;
	protected $numPublicMethods = 0;	
	protected $passingStatements = 0;
	protected $results = array();
	protected $testLines = array();	
	protected $testedClassName;	
	protected $totalStatements = 0;
	protected $uncoveredMethods = array();
	
	/**
	 * @param string $testedClassName - The name of the class being tested
	 * @param array $file - The contents of the file, as an array
	 * @param array $coverageResults - The xdebug coverage stats array
	 */	
	function __construct($testedClassName, array $file, array $coverageResults = array()){
		$this->testedClassName = $testedClassName;
		$this->file = $file;	
		$this->coverageResults = $coverageResults;
		$this->fileExists = (sizeof($file) > 0) ? TRUE : FALSE;
		
		$this->calculateStatementCoverage();
		$this->calculateFunctionCoverage();		
	}

	/**
	 * @description Calculates stats for which eligible lines of code are executed by a test
	 */	
	protected function calculateStatementCoverage(){
		$structures = array(
			'abstract', 'function', 'class', 'private',	'public', 'protected', 'class', 'interface', 'namespace'
		);
		$oneLiners = array('{', '}', '<?php', '?>', '');
		
		//Calculate the statement coverage
		foreach ($this->file as $lineNumber=>$code){
			$lineNumber += 1;
			$trimmedCode = trim($code);			

			if ((isset($this->coverageResults['all']))&&(is_array($this->coverageResults['all']))&&(in_array($lineNumber, array_keys($this->coverageResults['all'])))&&(!in_array($trimmedCode, $oneLiners))){
				$type = 'pass';
				$this->totalStatements++;
				$this->passingStatements++;
			} else if ((substr($trimmedCode, 0, 1) == '*')||(substr($trimmedCode, 0, 1) == '/')){  
				$type = 'comment';
			} else if ((in_array(array_pop(array_reverse(explode(' ', $trimmedCode))), $structures))||(in_array($trimmedCode, $oneLiners))){
				$type = 'structure';
			} else {
				$type = 'fail';
				$this->totalStatements++;
			}
			$this->lineCheck[$lineNumber] = $type;
		}
		
		//Assign tests to each passing line
		foreach ($this->coverageResults as $testName=>$lines){
			foreach ($lines as $line=>$executed){
				if ($testName != 'all'){
					if (isset($this->testLines[$line])){
						$this->testLines[$line][] = $testName;
					} else {
						$this->testLines[$line] = array($testName);
					}
				}
			}
		}		
	}
	
	/**
	 * @description Calculates stats for which public methods are covered by a test
	 */		
	protected function calculateFunctionCoverage(){
		$reflect = new \ReflectionClass($this->testedClassName);
		$methods = $reflect->getMethods(ReflectionMethod::IS_PUBLIC);
		$classMethods = array();
		
		foreach ($methods as $method){
			if ($method->class == $this->testedClassName){
				$classMethods[] = $method->name; 
			}
		}

		foreach ($classMethods as $methodName){
			$method = new \ReflectionMethod($this->testedClassName, $methodName);
			$startLine = $method->getStartLine();
			$lines = array($startLine, $startLine+1, $startLine+2, $startLine+3);
			$executedLines = (isset($this->coverageResults['all'])&&(is_array($this->coverageResults['all']))) 
				? array_keys($this->coverageResults['all']) 
				: array();
						
			if (sizeof(array_diff($executedLines, $lines)) != sizeof($executedLines)){
				$this->numCoveredPublicMethods += 1;
				$this->coveredMethods[] = $methodName;
			} else {
				$this->uncoveredMethods[] = $methodName;
			}			
			$this->numPublicMethods += 1;
		}				
	}	

	/**
	 * @description Calculates the data for the functional coverage bar.
	 */	
	protected function calculateFunctionalBarCoverage(){
		$title = '<p>Functional coverage tests to see if each public method of your class is executed by your unit 
			tests, but it does not necessarily mean that the class has been thoroughly tested. For true quality test 
			coverage, test each possible state of your classes.</p>';
		$uncovered = (sizeof($this->uncoveredMethods) > 0) ? '<p>Uncovered methods for this class:</p><ul>
			<li>'.implode('</li><li>', $this->uncoveredMethods).'</li></ul>' : NULL;
		$none = (($this->numCoveredPublicMethods == 0)&&($this->numPublicMethods == 0)) ? '<p>This class did not contain 
			any public methods to test, but it may extend another class.</p>' : NULL;
		
		if (($this->numCoveredPublicMethods != 0)&&($this->numPublicMethods != 0)){
			$passPercentage = number_format(($this->numCoveredPublicMethods/$this->numPublicMethods)*100);
			$uncovered = "<p><strong>$this->numCoveredPublicMethods of $this->numPublicMethods public methods executed 
				(".$passPercentage.'%).</strong></p>'.$uncovered;
			if ($passPercentage >= 90){
				$this->results['functionalBarStatus'] = 'pass'; 
			} else if ($passPercentage > 50){
				$this->results['functionalBarStatus'] = 'warning'; 
			} else {
				$this->results['functionalBarStatus'] = 'fail'; 
			}
			
			$this->results['functionalBarTitle'] = htmlspecialchars($title.$uncovered);
			$this->results['functionalBarText'] = NULL;
			$this->results['functionalBarCaption'] = 'Functional Coverage ('.$passPercentage.'%)';
			$this->results['functionalBarPercentage'] = $passPercentage;
		} else if ($this->numPublicMethods != 0){
			$uncovered = "<p><strong>0 of $this->numPublicMethods public methods executed (0%).</strong></p>".$uncovered;
			$this->results['functionalBarStatus'] = 'fail';
			$this->results['functionalBarText'] = NULL;
			$this->results['functionalBarTitle'] = htmlspecialchars($title.$uncovered);
			$this->results['functionalBarCaption'] = 'Functional Coverage (0%)';
			$this->results['functionalBarPercentage'] = 0;				
		} else {
			$this->results['functionalBarStatus'] = 'nA';
			$this->results['functionalBarText'] = 'Not Applicable';
			$this->results['functionalBarTitle'] = htmlspecialchars($title.$none);
			$this->results['functionalBarCaption'] = 'Functional Coverage (N/A)';
			$this->results['functionalBarPercentage'] = 0;	
		}
	}
	
	/**
	 * @description Calculates the data for the statement coverage bar.
	 */		
	protected function calculateStatementBarCoverage(){
		$title = '<p>Statement coverage tests to see if each executable line of your class is run by your unit tests, 
			but it does not necessarily mean that the class has been thoroughly tested. For true quality test coverage, 
			test each possible state of your classes.</p>';
		
		if (($this->passingStatements != 0)&&($this->totalStatements != 0)){
			$passPercentage = number_format(($this->passingStatements/$this->totalStatements)*100);
			if ($passPercentage >= 90){
				$this->results['statementBarStatus'] = 'pass';  
			} else if ($passPercentage > 50){
				$this->results['statementBarStatus'] = 'warning'; 
			} else {
				$this->results['statementBarStatus'] = 'fail'; 
			}
			$uncovered = "<p><strong>$this->passingStatements of $this->totalStatements statements executed 
				(".$passPercentage.'%).</strong></p>';
		
			$this->results['statementBarTitle'] = htmlspecialchars($title.$uncovered);
			$this->results['statementBarText'] = NULL;
			$this->results['statementBarCaption'] = 'Statement Coverage ('.$passPercentage.'%)';
			$this->results['statementBarPercentage'] = $passPercentage;			
		} else if ($this->totalStatements != 0){
			$uncovered = "<p><strong>0 of $this->totalStatements statements executed (0%).</strong></p>";
			$this->results['statementBarStatus'] = 'fail';
			$this->results['statementBarText'] = NULL;
			$this->results['statementBarTitle'] = htmlspecialchars($title.$uncovered);
			$this->results['statementBarCaption'] = 'Statement Coverage (0%)';
			$this->results['statementBarPercentage'] = 0;								
		} else {
			$none = '<p>This class did not contain any executable statements to test, but it may extend another 
				class.</p>';
			$this->results['statementBarStatus'] = 'nA';
			$this->results['statementBarText'] = 'Not Applicable';
			$this->results['statementBarTitle'] = htmlspecialchars($title.$none);
			$this->results['statementBarCaption'] = 'Statement Coverage (N/A)';
			$this->results['statementBarPercentage'] = 0;		
		}
	}

	/**
	 * @description Calculates the data for the file source code.
	 */	
	protected function calculateCodeCoverage(){
		$this->results['code'] = array();
		foreach ($this->file as $lineNumber=>$code){
			$lineNumber += 1;
			$trimmedCode = trim($code);			
			switch ($this->lineCheck[$lineNumber]){
				case 'pass':
					$tests = ($this->coverageResults['all'][$lineNumber] > 1) ? 'tests' : 'test';
					if ((sizeof($this->testLines[$lineNumber]) == (sizeof($this->coverageResults) - 1))&&($tests = 'tests')){
						$title =  'This line of code has been executed by all '
							.$this->coverageResults['all'][$lineNumber].' passing unit tests for this test case.';
						$executions = $this->coverageResults['all'][$lineNumber]; 
					} else {
						$title =  'This line of code has been executed by the 
							following '.$this->coverageResults['all'][$lineNumber].' passing unit '.$tests.': 
							&lt;ul&gt;&lt;li&gt;'.implode('&lt;/li&gt;&lt;li&gt;', $this->testLines[$lineNumber]).
							'&lt;/li&gt;&lt;/ul&gt;';
						$executions = $this->coverageResults['all'][$lineNumber];
					}
					break;
				case 'fail':
					$title =  'This line of code has &lt;em&gt;not&lt;/em&gt; been executed by a passing unit test.';
					$executions = ' 0';
					break;
				default:
					$title =  NULL;
					$executions = NULL;
					break;															
			}
			
			$this->results['codeCoverage'][$lineNumber] = array();
			$this->results['codeCoverage'][$lineNumber]['code'] = str_replace(
				"\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", str_replace(" ", "&nbsp;", htmlspecialchars($code))
			);
			$this->results['codeCoverage'][$lineNumber]['type'] = $this->lineCheck[$lineNumber];
			$this->results['codeCoverage'][$lineNumber]['title'] = $title;
			$this->results['codeCoverage'][$lineNumber]['executions'] = $executions;					 
		}		
	}
	
	/**
	 * @return array - The names of the covered methods
	 */
	public function getCoveredMethods(){
		return $this->coveredMethods;
	}

	/**
	 * @return array - The coverage data
	 */	
	public function getData(){
		$this->calculateFunctionalBarCoverage();
		$this->calculateStatementBarCoverage();
		$this->calculateCodeCoverage();
		
		$this->results['coveredMethods'] = $this->coveredMethods;
		$this->results['functionalCoverage'] = (($this->numCoveredPublicMethods != 0)&&($this->numPublicMethods != 0)) 
			? (int) number_format(($this->numCoveredPublicMethods/$this->numPublicMethods)*100) 
			: 0;
		$this->results['numCoveredPublicMethods'] = $this->numCoveredPublicMethods;
		$this->results['numPublicMethods'] = $this->numPublicMethods;
		$this->results['passingStatements'] = $this->passingStatements;
		$this->results['statementCoverage'] = (($this->passingStatements != 0)&&($this->totalStatements != 0)) 
			? (int) number_format(($this->passingStatements/$this->totalStatements)*100) 
			: 0;
		$this->results['totalStatements'] = $this->totalStatements;
		$this->results['uncoveredMethods'] = $this->uncoveredMethods;
		return $this->results;								
	}
}