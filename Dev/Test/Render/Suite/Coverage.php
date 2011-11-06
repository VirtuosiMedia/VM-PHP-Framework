<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Renders the test coverage results for the test suite
*/
class Test_Render_Suite_Coverage {

	protected $results;
	protected $testData = array();

	/**
	 * @param array $results - The results array for the test suite
	 */
	function __construct(array $results){
		$this->results = $results;
		$this->compileStats();
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
	 * @param
	 * @return string - The function coverage bar as an HTML string
	 */	
	protected function renderFunctionCoverageBar(array $classData = array(), $caption = FALSE){
		$title = '<p>Functional coverage tests to see if each public method of your class is executed by your unit tests, but it does not necessarily mean that the class has been thoroughly tested. For true quality test coverage, test each possible state of your classes.</p>';
		$uncovered = (sizeof($classData['uncoveredMethods']) > 0) ? '<p>Uncovered methods for this class:</p><ul><li>'.implode('</li><li>', $classData['uncoveredMethods']).'</li></ul>' : NULL;
		$none = (($classData['numCoveredPublicMethods'] == 0)&&($classData['numPublicMethods'] == 0)) ? '<p>This class did not contain any public methods to test, but it may extend another class.</p>' : NULL;
		
		if (($classData['numCoveredPublicMethods'] != 0)&&($classData['numPublicMethods'] != 0)){
			$passPercentage = number_format(($classData['numCoveredPublicMethods']/$classData['numPublicMethods'])*100);
			$uncovered = "<p><strong>".$classData['numCoveredPublicMethods']." of ".$classData['numPublicMethods']." public methods executed (".$passPercentage.'%).</strong></p>'.$uncovered;
			if ($passPercentage >= 90){
				$passMeter = '<span class="pass" style="width:'.$passPercentage.'%"></span>'; 
			} else if ($passPercentage > 50){
				$passMeter = '<span class="warning" style="width:'.$passPercentage.'%"></span>';
			} else {
				$passMeter = '<span class="fail" style="width:'.$passPercentage.'%"></span>';
			}
			
			$view = ($caption) ? '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'">'.$passMeter.'</div>' : '<div class="meterContainer tips" title="'.htmlspecialchars($uncovered).'">'.$passMeter.'</div>';
			$view .= ($caption) ? '<span class="meterCaption tips suite" title="'.htmlspecialchars($title.$uncovered).'">Suite Functional Coverage ('.$passPercentage.'%)</span>' : NULL;
		} else if ($classData['numPublicMethods'] != 0){
			$uncovered = "<p><strong>0 of ".$classData['numPublicMethods']." public methods executed (0%).</strong></p>".$uncovered;
			$view = ($caption) ? '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'"></div>' : '<div class="meterContainer tips" title="'.htmlspecialchars($uncovered).'"></div>';
			$view .= ($caption) ? '<span class="meterCaption tips suite" title="'.htmlspecialchars($title.$uncovered).'">Suite Functional Coverage (0%)</span>' : NULL;		
		} else {
			$view = ($caption) ? '<div class="meterContainer tips" title="'.htmlspecialchars($title.$none).'">Not Applicable</div>' : '<div class="meterContainer tips" title="'.htmlspecialchars($none).'">Not Applicable</div>';
			$view .= ($caption) ? '<span class="meterCaption tips suite" title="'.htmlspecialchars($title.$none).'">Suite Functional Coverage (N/A)</span>' : NULL;
		}
		
		return $view;
	}
	
	/**
	 * @return string - The statement coverage bar as an HTML string
	 */	
	protected function renderStatementCoverageBar(array $classData = array(), $caption = FALSE){
		$title = '<p>Statement coverage tests to see if each executable line of your class is run by your unit tests, but it does not necessarily mean that the class has been thoroughly tested. For true quality test coverage, test each possible state of your classes.</p>';
		
		if (($classData['passingStatements'] != 0)&&($classData['totalStatements'] != 0)){
			$passPercentage = number_format(($classData['passingStatements']/$classData['totalStatements'])*100);
			if ($passPercentage >= 90){
				$passMeter = '<span class="pass" style="width:'.$passPercentage.'%"></span>'; 
			} else if ($passPercentage > 50){
				$passMeter = '<span class="warning" style="width:'.$passPercentage.'%"></span>';
			} else {
				$passMeter = '<span class="fail" style="width:'.$passPercentage.'%"></span>';
			}
			$uncovered = "<p><strong>".$classData['passingStatements']." of ".$classData['totalStatements']." statements executed (".$passPercentage.'%).</strong></p>';
			
			$view = ($caption) ? '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'">'.$passMeter.'</div>' : '<div class="meterContainer tips" title="'.htmlspecialchars($uncovered).'">'.$passMeter.'</div>';
			$view .= ($caption) ? '<span class="meterCaption tips suite" title="'.htmlspecialchars($title.$uncovered).'">Suite Statement Coverage ('.$passPercentage.'%)</span>' : NULL;
		} else if ($classData['totalStatements'] != 0){
			$uncovered = "<p><strong>0 of ".$classData['totalStatements']." statements executed (0%).</strong></p>";
			$view = ($caption) ? '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'"></div>' : '<div class="meterContainer tips" title="'.htmlspecialchars($uncovered).'"></div>';
			$view .= ($caption) ? '<span class="meterCaption tips suite" title="'.htmlspecialchars($title.$uncovered).'">Suite Statement Coverage (0%)</span>' : NULL;				
		} else {
			$none = '<p>This class did not contain any executable statements to test, but it may extend another class.</p>';
			$view = ($caption) ? '<div class="meterContainer tips" title="'.htmlspecialchars($title.$none).'">Not Applicable</div>' : '<div class="meterContainer tips" title="'.htmlspecialchars($none).'">Not Applicable</div>';
			$view .= ($caption) ? '<span class="meterCaption tips suite" title="'.htmlspecialchars($title.$none).'">Suite Statement Coverage (N/A)</span>' : NULL;	
		}
				
		return $view;
	}	

	protected function renderTestsTable(){
		$view = '<table class="testsTable" cellspacing="0" cellpadding="0" width="100%">';
		$view .= '<thead><tr><th>Class</th><th>Functional</th><th>Statement</th></tr></thead>';
		$view .= '<tbody>';
		
		foreach ($this->results as $name=>$results){		
			$view .= '<tr><td><a class="classLink tips" title="View this class" href="#'.$name.'">'.$name.'</a></td>';
			$view .= '<td>'.$this->renderFunctionCoverageBar($results).'</td><td>'.$this->renderStatementCoverageBar($results).'</td></tr>';
		}
		$view .= '</tbody></table>';
		return $view;
	}
	
	/**
	 * @return string - The results container as an HTML string
	 */
	public function render(){
		$view = '<div id="coverage-for-suiteOverview" class="coverageContainer">';			
			$view .= '<div class="infoContainer">';
				$view .= $this->renderFunctionCoverageBar($this->testData, TRUE);
				$view .= $this->renderStatementCoverageBar($this->testData, TRUE);			
			$view .= '</div>';		
			$view .= '<div class="resultsTableContainer">';
				$view .= '<p>Note that even 100% coverage does not necessarily mean that the tested class is thoroughly tested, it only means that each line of code is executed at least once. The coverage illustration below is only meant as a starting point for your test coverage. For true quality test coverage, test each possible state of your classes.</p>';
			$view .= '</div>';

			$view .= $this->renderTestsTable();
		$view .= '</div>';
		return $view;
	}
}