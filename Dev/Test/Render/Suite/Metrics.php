<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Renders the unit test results for the test suite
*/
class Tests_Test_Render_Suite_Metrics {

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
		$this->testData['numMethods'] = 0;
		$this->testData['refactor'] = 0;
		$this->testData['readability'] = 0;	
		$this->testData['avgComplexity'] = 0;
		$this->testData['executableLoc'] = 0;
		$this->testData['commentsLoc'] = 0;	
		$this->testData['whitespaceLoc'] = 0;				
		
		foreach ($this->results as $name=>$results){	
			$this->testData['numMethods'] += $results['numMethods'];
			$this->testData['refactor'] += $results['refactor'];			
			$this->testData['readability'] += ($results['readability'] < 100) ? $results['readability'] : 100;
			$this->testData['avgComplexity'] += $results['avgComplexity'];
			$this->testData['executableLoc'] += $results['executableLoc'];
			$this->testData['commentsLoc'] += $results['commentsLoc'];
			$this->testData['whitespaceLoc'] += $results['whitespaceLoc'];														
		}
		
		$this->testData['refactor'] = number_format($this->testData['refactor']/sizeof($this->results), 0);
		$this->testData['readability'] = number_format($this->testData['readability']/sizeof($this->results), 0);
		$this->testData['avgComplexity'] = number_format($this->testData['avgComplexity']/sizeof($this->results), 2);
		$this->testData['totalLoc'] = $this->testData['executableLoc'] + $this->testData['commentsLoc'] + $this->testData['whitespaceLoc'];
	}
	
	/**
	 * @param
	 * @return string - The function coverage bar as an HTML string
	 */	
	protected function renderRefactorBar(array $classData = array(), $caption = FALSE){
		$title = ($caption) ? '<p>Refractor Probability attempts to analyze your code based on its length and complexity. Lower numbers are desired, higher numbers mean your code could be a candidate for refactoring.</p>' : NULL;
		if ($classData['numMethods'] > 0){
			$passPercentage = ($classData['refactor'] <= 100) ? $classData['refactor'] : 100;
			$class = ($caption) ? 'collection of classes' : 'class';
			if ($passPercentage <= 30){
				$passMeter = '<span class="pass" style="width:'.$passPercentage.'%"></span>';
				$uncovered = "<p><strong>This $class has a low Refactor Probability (".$passPercentage.'%) and does not need to be refactored.</strong></p>'; 
			} else if ($passPercentage <= 60){
				$passMeter = '<span class="warning" style="width:'.$passPercentage.'%"></span>';
				$uncovered = "<p><strong>This $class has a medium Refactor Probability (".$passPercentage.'%) and may need to be refactored.</strong></p>';
			} else {
				$passMeter = '<span class="fail" style="width:'.$passPercentage.'%"></span>';
				$uncovered = "<p><strong>This $class has a high Refactor Probability (".$passPercentage.'%) and should be refactored.</strong></p>';
			}
						
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'">'.$passMeter.'</div>';
			$view .= ($caption) ? '<span class="meterCaption tips suite" title="'.htmlspecialchars($title.$uncovered).'">Suite Refactor Probability ('.$passPercentage.'%)</span>' : NULL;		
		} else {
			$none = '<p>This class did not contain enough executable code to test, but it may extend another class.</p>';
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$none).'">Not Applicable</div>';
			$view .= ($caption) ?  '<span class="meterCaption tips suite" title="'.htmlspecialchars($title.$none).'">Suite Refactory Probability (N/A)</span>' : NULL;	
		}
		
		return $view;
	}

	/**
	 * @return array - An associative array of the test metrics
	 */
	public function getResults(){
		$data = array();
		$data['refactor'] = (int) $this->testData['refactor'];
		$data['readability'] = (int) $this->testData['readability'];
		$data['avgComplexity'] = $this->testData['avgComplexity'];
		$data['totalLoc'] = (int) $this->testData['totalLoc'];		
		return $data;
	}	
	
	/**
	 * @return string - The statement coverage bar as an HTML string
	 */	
	protected function renderReadabilityBar(array $classData = array(), $caption = FALSE){
		$title = ($caption) ? '<p>The Readability Factor attempts to analyze your code for its readability. Improve your readability score by simplifying your code and properly commenting it.</p>' : NULL;
		$passPercentage = ($classData['readability'] <= 100) ? $classData['readability'] : 100;
		$class = ($caption) ? 'collection of classes' : 'class';
		if ($passPercentage > 70){
			$passMeter = '<span class="pass" style="width:'.$passPercentage.'%"></span>';
			$uncovered = "<p><strong>This $class has high readability (".$passPercentage.'%).</strong></p>'; 
		} else if ($passPercentage >50){
			$passMeter = '<span class="warning" style="width:'.$passPercentage.'%"></span>';
			$uncovered = "<p><strong>This $class has medium readability (".$passPercentage.'%) and may benefit from cleaning up or refactoring.</strong></p>';
		} else {
			$passMeter = '<span class="fail" style="width:'.$passPercentage.'%"></span>';
			$uncovered = "<p><strong>This $class has low readability (".$passPercentage.'%) and should be refactored and/or cleaned up.</strong></p>';
		}
					
		$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'">'.$passMeter.'</div>';
		$view .= ($caption) ? '<span class="meterCaption tips suite" title="'.htmlspecialchars($title.$uncovered).'">Suite Readability Factor ('.$passPercentage.'%)</span>' : NULL;		
	
		return $view;
	}
		
	/**
	 * @return string - The statement coverage bar as an HTML string
	 */	
	protected function renderComplexity(array $classData = array(), $caption = FALSE){
		$classComplexity = ($caption) ? '<p>Class Complexity is based on the average Cyclomatic Complexity for each of the methods in the class. The more complex a class is, the higher the number. To minimize complexity, reduce the number of decision points in each method or refactor if necessary.</p>' : NULL;
		if ($classData['avgComplexity'] <=5){
			$cssClass = 'pass';
			$classComplexityRating = '<p><strong>This class has a low average complexity rating of '.number_format($classData['avgComplexity'], 2).'.</strong></p>';
		} else if ($classData['avgComplexity'] <=10){
			$cssClass = 'warning';
			$classComplexityRating = '<p><strong>This class has a medium average complexity rating of '.number_format($classData['avgComplexity'], 2).' and may need to be refactored and simplified.</strong></p>';
		} else {
			$cssClass = 'fail';
			$classComplexityRating = '<p><strong>This class has a high average complexity rating of '.number_format($classDatas['avgComplexity'], 2).' and should to be refactored and simplified.</strong></p>';
		}		

		if ($caption){
			$view = '<span class="complexity tips '.$cssClass.'" title="'.htmlspecialchars($classComplexity.$classComplexityRating).'">'.number_format($classData['avgComplexity'], 2).'</span>';
			$view .= '<span class="meterCaption tips suite" title="'.htmlspecialchars($classComplexity.$classComplexityRating).'">Suite Average Complexity</span>';		
		} else {
			$view = '<span class="complexity tips '.$cssClass.'" title="'.htmlspecialchars($classComplexity.$classComplexityRating).'">'.number_format($classData['avgComplexity'], 2).'</span>';
		}
		return $view;
	}	

	protected function renderTestsTable(){
		$view = '<table class="testsTable" cellspacing="0" cellpadding="0" width="100%">';
		$view .= '<thead><tr><th>Class</th><th>Complexity</th><th>Refactor</th><th>Readability</th></tr></thead>';
		$view .= '<tbody>';
		
		foreach ($this->results as $name=>$results){		
			$view .= '<tr><td><a class="classLink tips" title="View this class" href="#'.$name.'">'.$name.'</a></td>';
			$view .= '<td>'.$this->renderComplexity($results).'</td><td>'.$this->renderRefactorBar($results).'</td><td>'.$this->renderReadabilityBar($results).'</td></tr>';
		}
		$view .= '</tbody></table>';
		return $view;
	}
	
	/**
	 * @return string - The results container as an HTML string
	 */
	public function render(){
		$view = '<div id="metrics-for-suiteOverview" class="coverageContainer">';			
			$view .= '<div class="infoContainer">';
				$view .= $this->renderComplexity($this->testData, TRUE);
				$view .= $this->renderRefactorBar($this->testData, TRUE);
				$view .= $this->renderReadabilityBar($this->testData, TRUE);
				$view .= '<ul>';
					$view .= '<li><strong>Total Lines Of Code</strong>: '.$this->testData['totalLoc'].'</li>';
					$view .= '<li><strong>Classes</strong>: '.sizeof($this->results).'</li>';
					$view .= '<li><strong>Methods/Class</strong>: '.number_format($this->testData['numMethods']/sizeof($this->results), 2).'</li>';
					$view .= '<li><strong>LOC/Class</strong>: '.number_format($this->testData['totalLoc']/sizeof($this->results), 2).'</li>';
				$view .= '</ul>';							
			$view .= '</div>';		
			$view .= '<div class="resultsTableContainer">';
				$view .= '<h4 class="tips" title="Lines of Code Analysis">LOC Analysis</h4>';
				$view .= '<div id="suiteOverviewLocHolder" title="Lines of Code Analysis, by percentage" class="locHolder tips">';
				$view .= '<table id="suiteOverviewLoc" class="testTable locChart" cellspacing="0" cellpadding="0" width="100%">';
				$view .= '<thead><tr>';
					$view .= ($this->testData['executableLoc'] > 0) ? '<th>Executable Code</th>' : NULL;
					$view .= ($this->testData['commentsLoc'] > 0) ? '<th>Comments</th>' : NULL;
					$view .= ($this->testData['whitespaceLoc'] > 0) ? '<th>Whitespace</th>' : NULL;
				$view .= '</tr></thead>';
				$view .= '<tbody><tr>';
					$view .= ($this->testData['executableLoc'] > 0) ? '<td>'.$this->testData['executableLoc'].'</td>' : NULL;
					$view .= ($this->testData['commentsLoc'] > 0) ? '<td>'.$this->testData['commentsLoc'].'</td>' : NULL;
					$view .= ($this->testData['whitespaceLoc'] > 0) ? '<td>'.$this->testData['whitespaceLoc'].'</td>' : NULL;				
				$view .= '</tr></tbody></table></div>';
			$view .= '</div>';

			$view .= $this->renderTestsTable();
		$view .= '</div>';
		return $view;
	}
}