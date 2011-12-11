<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Models the code metrics for the test suite.
 * @extends Vm\Model
 * @namespace Test\Model\Suite
 */
namespace Test\Model\Suite;

class Metrics extends \Vm\Model {

	protected $results;
	protected $testData = array();

	/**
	 * @param array $results - The results array for the test suite
	 */
	function __construct(array $results){
		$this->results = $results;
		$this->compileStats();
		$this->calculateComplexity($this->testData, TRUE);
		$this->calculateRefactorBar($this->testData, TRUE);
		$this->calculateReadabilityBar($this->testData, TRUE);
		$this->calculateGeneralMetrics();
		$this->calculateTestsTable();		
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
	 * @description Calculates the suite cyclomatic complexity
	 * @param array $classData - The class or suite data
	 * @param boolean $caption - TRUE if a caption should be generated, FALSE otherwise
	 * @return An associative array of the complexity results if for a class, FALSE otherwise
	 */	
	protected function calculateComplexity(array $classData = array(), $caption = FALSE){
		$title = '<p>Class Complexity is based on the average Cyclomatic Complexity for each of 
			the methods in the class. The more complex a class is, the higher the number. To minimize complexity, 
			reduce the number of decision points in each method or refactor if necessary.</p>';
		if ($classData['avgComplexity'] <=5){
			$complexityStatus = 'pass';
			$rating = '<p><strong>This class has a low average complexity rating of '.
				number_format($classData['avgComplexity'], 2).'.</strong></p>';
		} else if ($classData['avgComplexity'] <=10){
			$complexityStatus = 'warning';
			$rating = '<p><strong>This class has a medium average complexity rating of '
				.number_format($classData['avgComplexity'], 2).' and may need to be refactored and 
				simplified.</strong></p>';
		} else {
			$complexityStatus = 'fail';
			$rating = '<p><strong>This class has a high average complexity rating of '.
				number_format($classData['avgComplexity'], 2).' and should be refactored and 
				simplified.</strong></p>';
		}		

		if ($caption){
			$this->setData('complexityTitle', htmlspecialchars($title.str_replace('class', 'report', $rating)));
			$this->setData('avgComplexity', number_format($classData['avgComplexity'], 2));
			$this->setData('complexityStatus', $complexityStatus);
			return FALSE;
		} else {
			return array(
				'complexityTitle'=>htmlspecialchars($rating),
				'avgComplexity'=>number_format($classData['avgComplexity'], 2),
				'complexityStatus'=>$complexityStatus,			
			);
		}
	}	
	
	/**
	 * @description Calculates the statistics and titles for the refactorability meters
	 * @param array $classData - The class or suite data
	 * @param boolean $caption - TRUE if a caption should be generated, FALSE otherwise
	 * @return An associative array of the refactorability results if for a class, FALSE otherwise.
	 */	
	protected function calculateRefactorBar(array $classData = array(), $caption = FALSE){
		$title = '<p>Refractor Probability attempts to analyze your code based on its length and complexity. Lower 
			numbers	are desired, higher numbers mean your code could be a candidate for refactoring.</p>';
		if ($classData['numMethods'] > 0){
			$passPercentage = ($classData['refactor'] <= 100) ? $classData['refactor'] : 100;
			$class = ($caption) ? 'report' : 'class';
			if ($passPercentage <= 30){
				$status = 'pass';
				$uncovered = "<p><strong>This $class has a low Refactor Probability (".$passPercentage.'%) and does not 
					need to be refactored.</strong></p>'; 
			} else if ($passPercentage <= 60){
				$status = 'warning';
				$uncovered = "<p><strong>This $class has a medium Refactor Probability (".$passPercentage.'%) and may 
					need to be refactored.</strong></p>';
			} else {
				$status = 'fail';
				$uncovered = "<p><strong>This $class has a high Refactor Probability (".$passPercentage.'%) and should 
					be refactored.</strong></p>';
			}
						
			$title = ($caption) ? htmlspecialchars($title.$uncovered) : htmlspecialchars($uncovered);
			$label = ($caption) ? 'Overall Refactor Probability ('.$passPercentage.'%)' : NULL;
			$meterText = NULL;		
		} else {
			$passPercentage = NULL;
			$status = NULL;
			$none = '<p>This class did not contain enough executable code to test, but it may extend another class.</p>';
			$title = ($caption) ? htmlspecialchars($title.$none) : htmlspecialchars($none);
			$meterText = 'Not Applicable';
			$label = ($caption) ?  'Overall Refactor Probability (N/A)' : NULL;	
		}
		
		if ($caption){
			$this->setData('refactorCaption', $label);	
			$this->setData('refactorPercentage', $passPercentage);
			$this->setData('refactorStatus', $status);
			$this->setData('refactorTitle', $title);
			$this->setData('refactorMeterText', $meterText);
			return FALSE;
		} else {
			return array(
				'refactorCaption'=>$label,
				'refactorPercentage'=>$passPercentage,
				'refactorStatus'=>$status,
				'refactorTitle'=>$title,
				'refactorMeterText'=>$meterText			
			);
		}
	}

	/**
	 * @description Calculates the statistics and titles for the readability meters
	 * @param array $classData - The class or suite data
	 * @param boolean $caption - TRUE if a caption should be generated, FALSE otherwise
	 * @return An associative array of the readability results if for a class, FALSE otherwise.
	 */	
	protected function calculateReadabilityBar(array $classData = array(), $caption = FALSE){
		$title = '<p>The Readability Factor attempts to analyze your code for its readability. Improve your readability 
			score by simplifying your code and properly commenting it.</p>';
		$passPercentage = ($classData['readability'] <= 100) ? $classData['readability'] : 100;
		$class = ($caption) ? 'report' : 'class';
		if ($passPercentage > 70){
			$status = 'pass';
			$instructions = "<p><strong>This $class has high readability (".$passPercentage.'%).</strong></p>'; 
		} else if ($passPercentage >50){
			$status = 'warning';
			$instructions = "<p><strong>This $class has medium readability (".$passPercentage.'%) and may benefit from 
				cleaning up or refactoring.</strong></p>';
		} else {
			$status = 'fail';
			$instructions = "<p><strong>This $class has low readability (".$passPercentage.'%) and should be refactored 
				and/or cleaned up.</strong></p>';
		}
					
		$title = ($caption) ? htmlspecialchars($title.$instructions) : htmlspecialchars($instructions);
		$label = ($caption) ? 'Overall Readability Factor ('.$passPercentage.'%)' : NULL;		

		if ($caption){
			$this->setData('readabilityCaption', 'Overall Readability Factor ('.$passPercentage.'%)');
			$this->setData('readabilityPercentage', $passPercentage);
			$this->setData('readabilityStatus', $status);
			$this->setData('readabilityTitle', htmlspecialchars($title.$instructions));
			return FALSE;
		} else {
			return array(
				'readabilityPercentage'=>$passPercentage,
				'readabilityStatus'=>$status,
				'readabilityTitle'=>htmlspecialchars($instructions)			
			);
		}
	}
		
	/**
	 * @description Calculates general code analysis metrics for the report.
	 * @return An associative array of the general code analysis metrics
	 */		
	protected function calculateGeneralMetrics(){
		$this->setData('avgLocClass', number_format($this->testData['totalLoc']/sizeof($this->results), 2));
		$this->setData('avgNumMethods', number_format($this->testData['numMethods']/sizeof($this->results), 2));	
		$this->setData('commentsLoc', $this->testData['commentsLoc']);
		$this->setData('executableLoc', $this->testData['executableLoc']);
		$this->setData('metricsLoc', $this->testData['totalLoc']);
		$this->setData('numClasses', sizeof($this->results));
		$this->setData('whitespaceLoc', $this->testData['whitespaceLoc']);
	}

	/**
	 * @description Calculates code analysis metrics for each class in the report.
	 * @return An associative array of the class code analysis metrics
	 */			
	protected function calculateTestsTable(){
		$suiteMetrics = array();
		foreach ($this->results as $name=>$results){
			$suiteMetrics[$name] = array(
				'complexity'=>$this->calculateComplexity($results),
				'refactor'=>$this->calculateRefactorBar($results),
				'readability'=>$this->calculateReadabilityBar($results),
			);		
		}
		$this->setData('suiteMetrics', $suiteMetrics);		
	}			
}