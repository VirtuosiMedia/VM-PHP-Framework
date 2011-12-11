<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Renders the code metrics for a class
 * @namespace Test\Model
 */
namespace Test\Model;

class Metrics {
	
	protected $classMetrics;
	protected $commentsLoc;
	protected $coveredMethods;
	protected $executableLoc;
	protected $file;
	protected $methodMetrics = array();
	protected $parents;
	protected $testedClassName;
	protected $usesClass = array();
	protected $whitespaceLoc;

	/**
	 * @param string $testedClassName - The name of the class being tested
	 * @param array $file - The contents of the file, as an array
	 * @param array $coveredMethods - An array of method names that have been covered by tests
	 */		
	function __construct($testedClassName, array $file, array $coveredMethods){
		$this->testedClassName = $testedClassName;
		$this->file = $file;
		$this->coveredMethods = $coveredMethods;
		$this->calculateMetrics();				
	}

	/**
	 * @description Analyzes the tested class and calculates various code metrics
	 */			
	protected function calculateMetrics(){
		$this->calculateMethodMetrics();
		
		$class = new \ReflectionClass($this->testedClassName);
		$this->getParentClass($class);
		
		$this->classMetrics['parent'] = $this->parents;
		$this->classMetrics['totalLoc'] = $this->commentsLoc + $this->whitespaceLoc + $this->executableLoc;
		$this->classMetrics['commentsLoc'] = $this->commentsLoc;
		$this->classMetrics['whitespaceLoc'] = $this->whitespaceLoc;
		$this->classMetrics['executableLoc'] = $this->executableLoc;
		$this->classMetrics['numMethods'] = sizeof($this->methodMetrics);
		
		$complexity = 0;
		$refactor = 0;
				
		foreach ($this->methodMetrics as $method=>$metrics){
			$complexity += $metrics['complexity'];
			$refactor += $metrics['refactor'];
		}
		
		$this->classMetrics['avgComplexity'] = (sizeof($this->methodMetrics)>0) 
			? $complexity/sizeof($this->methodMetrics) 
			: 1;
		$this->classMetrics['totalComplexity'] = (sizeof($this->methodMetrics)>0) ? $complexity : 1;
		$this->classMetrics['readability'] = (int) number_format(
			(($this->commentsLoc + $this->whitespaceLoc)/$this->classMetrics['totalLoc']) * 200 - 
			($this->classMetrics['avgComplexity']*2)
		);
		$refactor = ($this->classMetrics['numMethods'] > 0) ? $refactor/$this->classMetrics['numMethods'] : 1;
		$refactorFormula = number_format(
			(
				($refactor)+($this->classMetrics['avgComplexity'] * 10) + 
				($this->classMetrics['numMethods']) + 
				($this->classMetrics['totalLoc']/25)
			)/4
		);
		$this->classMetrics['refactor'] = (sizeof($this->methodMetrics)>0) ? (int) $refactorFormula : 0;		
	}

	/**
	 * @param ReflectionClass $class - The ReflectionClass object
	 */
	protected function getParentClass(\ReflectionClass $class){
		$parent = $class->getParentClass();
		$parentName = is_object($parent) ? $parent->getName() : FALSE;
		if ($parentName){
			$this->parents[] = $parentName;
			$grandParent = $parent->getParentClass();
			if (is_object($grandParent)){
				$this->getParentClass($grandParent);	
			}			
		} else if ($class->getName() != $this->testedClassName){
			$this->parents[] = $class->getName();
		}
	}
	
	/**
	 * @description Analyzes the tested class methods and calculates various code metrics
	 */	
	protected function calculateMethodMetrics(){
		$reflect = new \ReflectionClass($this->testedClassName);
		$methods = $reflect->getMethods();
		$classMethods = array();
		$methodLines = array();
		
		foreach ($methods as $method){
			if ($method->class == $this->testedClassName){
				$classMethods[] = $method->name; 
			}
		}

		foreach ($classMethods as $methodName){
			$method = new \ReflectionMethod($this->testedClassName, $methodName);
			$methodLines[$method->getStartLine()] = $methodName;
			$this->methodMetrics[$methodName]['modifiers'] = implode(', ', \Reflection::getModifierNames($method->getModifiers()));
			$this->methodMetrics[$methodName]['params'] = $method->getNumberOfParameters();
			$this->methodMetrics[$methodName]['startLine'] = $method->getStartLine();
			$this->methodMetrics[$methodName]['endLine'] = $method->getEndLine();
			$this->methodMetrics[$methodName]['loc'] = $method->getEndLine() - $method->getStartLine() + 1;
			$this->methodMetrics[$methodName]['docs'] = ($method->getDocComment()) ? 'Yes' : 'Undocumented';
			$this->methodMetrics[$methodName]['complexity'] = 1;			
		}
				
		/**
		 * @note Strings or heredoc syntax containing the following could provide false positives and as a result, the 
		 * 		complexity will be higher
		 */
		$conditionalsStart = array('?', 'case', 'catch', 'for', 'foreach', 'if', 'while');
		$conditionalsInline = array(')?', ') ?', 'elseif', 'else if', '&&', '||', ')and', ') and', ')or', ') or', ' xor '); 
		$currentMethod = NULL;
		
		foreach ($this->file as $lineNumber=>$code){
			$lineNumber += 1;
			$trimmedCode = trim($code);
			if ((substr($trimmedCode, 0, 1) == '*')||(substr($trimmedCode, 0, 1) == '/')){
				$this->commentsLoc += 1;
			} else if ($trimmedCode == ''){
				$this->whitespaceLoc += 1;
			} else {
				$this->executableLoc += 1;
				if (in_array($lineNumber, array_keys($methodLines))){			
					$currentMethod = $methodLines[$lineNumber];
				} else {
					//Scan for external classes being used by this class
					if ((strpos($code, ' new '))&&(strpos($code, '('))&&(strpos($code, ')'))){
						$class = explode('new', $code);
						$className = explode('(', $class[1]);
						$className = trim($className[0]);
						$className = (strstr($className, '$')) ? $className.' (dynamic)' : $className;
						if (!in_array($className, $this->usesClass)){
							$this->usesClass[] = $className;
						}
					}		
					
					//Scan for method complexity
					if ($currentMethod){
						$words = preg_split('#[\s:\(\{]+#', $trimmedCode);
						if (in_array($words[0], $conditionalsStart)){
							$this->methodMetrics[$currentMethod]['complexity'] += 1;
						} else {
							foreach($conditionalsInline as $condition){
								if (strpos($code, $condition)){
									$this->methodMetrics[$currentMethod]['complexity'] += 1;
								}
							}
						}
					}
				}
			}
		}

		foreach ($this->methodMetrics as $method=>$metrics){
			$this->methodMetrics[$method]['refactor'] = number_format(
				(
					(
						($metrics['loc']/200) +
						($metrics['complexity']/10) +
						($metrics['params']/10)
					)/3
				)*100
			);
		}
	}	

	/**
	 * @description Calculates cyclomatic complexity
	 * @param array $data - The class or method data
	 * @param boolean $caption - TRUE if a caption should be generated, FALSE otherwise
	 * @return An associative array of the complexity results if for a class, FALSE otherwise
	 */	
	protected function calculateComplexity($data, $caption = FALSE){
		$title = ($caption) 
			? '<p>Method Complexity is based on the average Cyclomatic Complexity for each of 
				the methods in the class. The more complex a method is, the higher the number. To minimize complexity, 
				reduce the number of decision points in each method or refactor if necessary.</p>'
			: NULL;
		$complexity = ($caption) ? $this->classMetrics['avgComplexity'] : $data['complexity'];
		$class = ($caption) ? 'class' : 'method';
		$average = ($caption) ? 'average method' : NULL;
		if ($complexity <=5){
			$complexityStatus = 'pass';
			$rating = $title."<p><strong>This $class has a low $average complexity rating of ".
				number_format($complexity, 2).'.</strong></p>';
		} else if ($complexity <=10){
			$complexityStatus = 'warning';
			$rating = $title."<p><strong>This $class has a medium $average complexity rating of "
				.number_format($complexity, 2).' and may need to be refactored and 
				simplified.</strong></p>';
		} else {
			$complexityStatus = 'fail';
			$rating = $title."<p><strong>This $class has a high $average complexity rating of ".
				number_format($complexity, 2).' and should be refactored and 
				simplified.</strong></p>';
		}		

		return array(
			'complexityTitle'=>htmlspecialchars($rating),
			'avgComplexity'=>number_format($complexity, 2),
			'complexityStatus'=>$complexityStatus,			
		);
	}	

	/**
	 * @description Calculates the statistics and titles for the refactorability meters
	 * @param array $data - The class or method data
	 * @param boolean $caption - TRUE if a caption should be generated, FALSE otherwise
	 * @return An associative array of the refactorability results if for a class, FALSE otherwise.
	 */	
	protected function calculateRefactorData(array $data = array(), $caption = FALSE){
		$title = '<p>Refractor Probability attempts to analyze your code based on its length and complexity. Lower 
			numbers	are desired, higher numbers mean your code could be a candidate for refactoring.</p>';
		if (((isset($data['numMethods']))&&($data['numMethods'] > 0)) || (!$caption)){
			$passPercentage = ($data['refactor'] <= 100) ? $data['refactor'] : 100;
			$class = ($caption) ? 'class' : 'method';
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
			$label = ($caption) ? 'Class Refactor Probability ('.$passPercentage.'%)' : NULL;
			$meterText = NULL;		
		} else {
			$passPercentage = NULL;
			$status = NULL;
			$none = '<p>This class did not contain enough executable code to test, but it may extend another class.</p>';
			$title = ($caption) ? htmlspecialchars($title.$none) : htmlspecialchars($none);
			$meterText = 'Not Applicable';
			$label = ($caption) ?  'Class Refactor Probability (N/A)' : NULL;	
		}
		
		return array(
			'refactorCaption'=>$label,
			'refactorPercentage'=>$passPercentage,
			'refactorStatus'=>$status,
			'refactorTitle'=>$title,
			'refactorMeterText'=>$meterText			
		);
	}	

	/**
	 * @description Calculates the statistics and titles for the readability meters
	 * @param array $data - The class or suite data
	 * @param boolean $caption - TRUE if a caption should be generated, FALSE otherwise
	 * @return An associative array of the readability results if for a class, FALSE otherwise.
	 */	
	protected function calculateReadabilityData(array $data = array(), $caption = FALSE){
		$title = '<p>The Readability Factor attempts to analyze your code for its readability. Improve your readability 
			score by simplifying your code and properly commenting it.</p>';
		$passPercentage = ($data['readability'] <= 100) ? $data['readability'] : 100;
		$class = ($caption) ? 'class' : 'method';
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
		$label = ($caption) ? 'Class Readability Factor ('.$passPercentage.'%)' : NULL;		

		return array(
			'readabilityCaption'=>$label,
			'readabilityPercentage'=>$passPercentage,
			'readabilityStatus'=>$status,
			'readabilityTitle'=>$title			
		);
	}	
	
	/**
	 * @return Returns an associative array of the class metrics for the current class.
	 */
	public function getData(){
		$complexity = $this->calculateComplexity($this->classMetrics, TRUE);
		$refactor = $this->calculateRefactorData($this->classMetrics, TRUE);
		$readability = $this->calculateReadabilityData($this->classMetrics, TRUE);
		$metrics = array(
			'parents'=>$this->parents,
			'uses'=>$this->usesClass,
			'methods'=>$this->calculateMethodData()
		);
		return array_merge($this->classMetrics, $complexity, $refactor, $readability, $metrics);
	}	
	
	/**
	 * @return string - The method data table as an HTML string
	 */	
	protected function calculateMethodData(){
		$methods = array();
		foreach ($this->methodMetrics as $method=>$metrics){
			$methods[$method] = array();
			
			if ($metrics['loc'] < 100){
				$locStatus = 'passText tips';
				$locTitle = 'This method is an appropriate length and probably doesn\'t need to be refactored';
			} else {
				$locStatus = ($metrics['loc'] < 200) ? 'warningText tips' : 'failText tips';
				$locTitle = ($metrics['loc'] < 200) 
					? 'This method is getting large and may require refactoring.' 
					: 'This method is large and should be refactored.';
			}
			
			$complexity = $this->calculateComplexity($metrics);
			$refactor = $this->calculateRefactorData($metrics);
			
			if ($metrics['params'] <= 5){
				$paramsClass = 'passText';
			} else {
				$paramsClass = ($metrics['params'] <= 7) ? 'warningText' : 'failText';
			}

			$docsClass = ($metrics['docs'] == 'Yes') ? 'passText' : 'failText';
			
			$data = '<span><strong>Method Name</strong>: '.$method.'</span><br/>';
			$data .= '<span><strong>Modifiers/Scope</strong>: '.$metrics['modifiers'].'</span><br/>';
			$data .= '<span><strong>Lines of Code</strong>: <span class="'.$locStatus.'">'.$metrics['loc'].'</span></span><br/>';
			$data .= '<span><strong>Number of Parameters</strong>: <span class="'.$paramsClass.'">'.$metrics['params'].'</span></span><br/>';
			$data .= '<span><strong>Documented</strong>: <span class="'.$docsClass.'">'.$metrics['docs'].'</span></span><br/>';

			if ((sizeof($this->coveredMethods) > 0)&&(!in_array($method, $this->coveredMethods))){
				$data .= '<span><strong>Covered By At Least 1 Unit Test</strong>: <span class="failText">No</span></span><br/>';
			} else if (sizeof($this->coveredMethods) > 0){
				$data .= '<span><strong>Covered By At Least 1 Unit Test</strong>: <span class="passText">Yes</span></span><br/>';
			}

			$methods[$method]['data'] = htmlspecialchars($data);
			$methods[$method]['complexity'] = $complexity['avgComplexity'];
			$methods[$method]['complexityTitle'] = $complexity['complexityTitle'];
			$methods[$method]['complexityStatus'] = $complexity['complexityStatus'].'Text tips';
			$methods[$method]['refactorPercentage'] = $refactor['refactorPercentage'];
			$methods[$method]['refactorTitle'] = $refactor['refactorTitle'];
			$methods[$method]['refactorStatus'] = $refactor['refactorStatus'].'Text tips';						
			$methods[$method]['loc'] = $metrics['loc'];
			$methods[$method]['locTitle'] = $locTitle;
			$methods[$method]['locStatus'] = $locStatus;
		}
		return $methods;
	}	
}