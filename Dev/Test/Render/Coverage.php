<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Renders the unit test coverage results of an individual unit test case
*/
class Test_Render_Coverage {
	
	protected $coverageResults;
	protected $coveredMethods = array();
	protected $file;
	protected $fileExists;
	protected $includeCoverage;	
	protected $lineCheck = array();
	protected $numCoveredPublicMethods = 0;
	protected $numPublicMethods = 0;	
	protected $passingStatements = 0;
	protected $testLines = array();	
	protected $testedClassName;	
	protected $totalStatements = 0;
	protected $uncoveredMethods = array();
	
	/**
	 * @param string $testedClassName - The name of the class being tested
	 * @param array $file - The contents of the file, as an array
	 * @param boolean $includeCoverage - Whether or not to include test coverage
	 * @param array $coverageResults - The xdebug coverage stats array
	 */	
	function __construct($testedClassName, array $file, $includeCoverage = FALSE, array $coverageResults = array()){
		$this->testedClassName = $testedClassName;
		$this->file = $file;
		$this->includeCoverage = $includeCoverage;		
		$this->coverageResults = $coverageResults;
		$this->fileExists = (sizeof($file) > 0) ? TRUE : FALSE;
		
		$this->calculateStatementCoverage();
		$this->calculateFunctionCoverage();		
	}

	/**
	 * Calculates stats for which eligible lines of code are executed by a test
	 */	
	protected function calculateStatementCoverage(){
		$structures = array('abstract', 'function', 'class', 'private', 'public', 'protected', 'class', 'interface', 'namespace');
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
	 * Calculates stats for which public methods are covered by a test
	 */		
	protected function calculateFunctionCoverage(){
		$reflect = new ReflectionClass($this->testedClassName);
		$methods = $reflect->getMethods(ReflectionMethod::IS_PUBLIC);
		$classMethods = array();
		
		foreach ($methods as $method){
			if ($method->class == $this->testedClassName){
				$classMethods[] = $method->name; 
			}
		}

		foreach ($classMethods as $methodName){
			$method = new ReflectionMethod($this->testedClassName, $methodName);
			$startLine = $method->getStartLine();
			$lines = array($startLine, $startLine+1, $startLine+2, $startLine+3);
			$executedLines = (isset($this->coverageResults['all'])&&(is_array($this->coverageResults['all']))) ? array_keys($this->coverageResults['all']) : array();
						
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
	 * @return string - The function coverage bar as an HTML string
	 */	
	protected function renderFunctionCoverageBar(){
		$title = '<p>Functional coverage tests to see if each public method of your class is executed by your unit tests, but it does not necessarily mean that the class has been thoroughly tested. For true quality test coverage, test each possible state of your classes.</p>';
		$uncovered = (sizeof($this->uncoveredMethods) > 0) ? '<p>Uncovered methods for this class:</p><ul><li>'.implode('</li><li>', $this->uncoveredMethods).'</li></ul>' : NULL;
		$none = (($this->numCoveredPublicMethods == 0)&&($this->numPublicMethods == 0)) ? '<p>This class did not contain any public methods to test, but it may extend another class.</p>' : NULL;
		
		if (($this->numCoveredPublicMethods != 0)&&($this->numPublicMethods != 0)){
			$passPercentage = number_format(($this->numCoveredPublicMethods/$this->numPublicMethods)*100);
			$uncovered = "<p><strong>$this->numCoveredPublicMethods of $this->numPublicMethods public methods executed (".$passPercentage.'%).</strong></p>'.$uncovered;
			if ($passPercentage >= 90){
				$passMeter = '<span class="pass" style="width:'.$passPercentage.'%"></span>'; 
			} else if ($passPercentage > 50){
				$passMeter = '<span class="warning" style="width:'.$passPercentage.'%"></span>';
			} else {
				$passMeter = '<span class="fail" style="width:'.$passPercentage.'%"></span>';
			}
			
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'">'.$passMeter.'</div>';
			$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($title.$uncovered).'">Functional Coverage ('.$passPercentage.'%)</span>';
		} else if ($this->numPublicMethods != 0){
			$uncovered = "<p><strong>0 of $this->numPublicMethods public methods executed (0%).</strong></p>".$uncovered;
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'"></div>';
			$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($title.$uncovered).'">Functional Coverage (0%)</span>';				
		} else {
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$none).'">Not Applicable</div>';
			$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($title.$none).'">Functional Coverage (N/A)</span>';	
		}
		
		return $view;
	}
	
	/**
	 * @return string - The statement coverage bar as an HTML string
	 */	
	protected function renderStatementCoverageBar(){
		$title = '<p>Statement coverage tests to see if each executable line of your class is run by your unit tests, but it does not necessarily mean that the class has been thoroughly tested. For true quality test coverage, test each possible state of your classes.</p>';
		
		if (($this->passingStatements != 0)&&($this->totalStatements != 0)){
			$passPercentage = number_format(($this->passingStatements/$this->totalStatements)*100);
			if ($passPercentage >= 90){
				$passMeter = '<span class="pass" style="width:'.$passPercentage.'%"></span>'; 
			} else if ($passPercentage > 50){
				$passMeter = '<span class="warning" style="width:'.$passPercentage.'%"></span>';
			} else {
				$passMeter = '<span class="fail" style="width:'.$passPercentage.'%"></span>';
			}
			$uncovered = "<p><strong>$this->passingStatements of $this->totalStatements statements executed (".$passPercentage.'%).</strong></p>';
			
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'">'.$passMeter.'</div>';
			$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($title.$uncovered).'">Statement Coverage ('.$passPercentage.'%)</span>';
		} else if ($this->totalStatements != 0){
			$uncovered = "<p><strong>0 of $this->totalStatements statements executed (0%).</strong></p>";
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'"></div>';
			$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($title.$uncovered).'">Statement Coverage (0%)</span>';				
		} else {
			$none = '<p>This class did not contain any executable statements to test, but it may extend another class.</p>';
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$none).'">Not Applicable</div>';
			$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($title.$none).'">Statement Coverage (N/A)</span>';	
		}
				
		return $view;
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
	public function getCoverage(){
		$coverage = array();
		$coverage['coveredMethods'] = $this->coveredMethods;
		$coverage['functionalCoverage'] = (($this->numCoveredPublicMethods != 0)&&($this->numPublicMethods != 0)) ? (int) number_format(($this->numCoveredPublicMethods/$this->numPublicMethods)*100) : 0;
		$coverage['numCoveredPublicMethods'] = $this->numCoveredPublicMethods;
		$coverage['numPublicMethods'] = $this->numPublicMethods;
		$coverage['passingStatements'] = $this->passingStatements;
		$coverage['statementCoverage'] = (($this->passingStatements != 0)&&($this->totalStatements != 0)) ? (int) number_format(($this->passingStatements/$this->totalStatements)*100) : 0;
		$coverage['totalStatements'] = $this->totalStatements;
		$coverage['uncoveredMethods'] = $this->uncoveredMethods;
		return $coverage;								
	}
	
	/**
	 * @return string - The coverage container as an HTML string
	 */	
	public function render(){
		$view = '<div id="coverage-for-'.$this->testedClassName.'" class="coverageContainer">';			
		if ($this->includeCoverage){			
			if ($this->fileExists){
				$view .= '<div class="infoContainer">';
					$view .= '<div class="coverageBar functionalCoverage">'.$this->renderFunctionCoverageBar().'</div>';
					$view .= '<div class="coverageBar">'.$this->renderStatementCoverageBar().'</div>';
				$view .= '</div>';
				$view .= '<div class="resultsTableContainer">';
					$view .= '<p>Note that even 100% coverage does not necessarily mean that the tested class is thoroughly tested, it only means that each line of code is executed at least once. The coverage illustration below is only meant as a starting point for your test coverage. For true quality test coverage, test each possible state of your classes.</p>';
				$view .= '</div>';
				
				$view .= '<ul class="codeCoverage">';
				
				foreach ($this->file as $lineNumber=>$code){
					$lineNumber += 1;
					$trimmedCode = trim($code);			
					switch ($this->lineCheck[$lineNumber]){
						case 'pass':
							$tests = ($this->coverageResults['all'][$lineNumber] > 1) ? 'tests' : 'test';
							if ((sizeof($this->testLines[$lineNumber]) == (sizeof($this->coverageResults) - 1))&&($tests = 'tests')){
								$executed =  '<span class="execute tips" title="This line of code has been executed by all '.$this->coverageResults['all'][$lineNumber].' passing unit tests for this test case." >'.$this->coverageResults['all'][$lineNumber].'</span>';
							} else {
								$executed =  '<span class="execute tips" title="This line of code has been executed by the following '.$this->coverageResults['all'][$lineNumber].' passing unit '.$tests.': &lt;ul&gt;&lt;li&gt;'.implode('&lt;/li&gt;&lt;li&gt;', $this->testLines[$lineNumber]).'&lt;/li&gt;&lt;/ul&gt;" >'.$this->coverageResults['all'][$lineNumber].'</span>';
							}
							break;
						case 'fail':
							$executed =  '<span class="execute tips" title="This line of code has &lt;em&gt;not&lt;/em&gt; been executed by a passing unit test.">0</span>';
							break;
						default:
							$executed =  '<span class="execute"></span>';
							break;															
					}
					
					$code = str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", str_replace(" ", "&nbsp;", htmlspecialchars($code)));
					$view .= '<li class="'.$this->lineCheck[$lineNumber].'"><span class="lineNumber">'.$lineNumber.'</span>'.$executed.'<span class="code">'.$code.'</span></span>';					 
				} 
				$view .= '</ul>';
			} else {
				$fileName = str_replace('_', '/', $this->testedClassName).'.php';
				$view .= "<p>The tested class file ($fileName) could not be loaded. Please verify that the class name matches the directory and file name structure.</p>";
			}
		} else {
			$view .= '<p>Code coverage analysis is not available. Please rerun the test suite with coverage analysis enabled.</p>';
		}		
		$view .= '</div>';
		return $view;		
	}
}