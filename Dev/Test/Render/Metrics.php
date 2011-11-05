<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Renders the code metrics for a class
*/
class Tests_Test_Render_Metrics {
	
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
	 * Analyzes the tested class and calculates various code metrics
	 */			
	protected function calculateMetrics(){
		$this->calculateMethodMetrics();
		
		$class = new ReflectionClass($this->testedClassName);
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
		
		$this->classMetrics['avgComplexity'] = (sizeof($this->methodMetrics)>0) ? $complexity/sizeof($this->methodMetrics) : 1;
		$this->classMetrics['totalComplexity'] = (sizeof($this->methodMetrics)>0) ? $complexity : 1;
		$this->classMetrics['readability'] = (int) number_format((($this->commentsLoc + $this->whitespaceLoc)/$this->classMetrics['totalLoc']) * 200 - ($this->classMetrics['avgComplexity']*2));
		$refactor = ($this->classMetrics['numMethods'] > 0) ? $refactor/$this->classMetrics['numMethods'] : 1;
		$refactorFormula = number_format((($refactor)+($this->classMetrics['avgComplexity'] * 10) + ($this->classMetrics['numMethods']) + ($this->classMetrics['totalLoc']/25))/4);
		$this->classMetrics['refactor'] = (sizeof($this->methodMetrics)>0) ? (int) $refactorFormula : 0;		
	}

	/**
	 * @param ReflectionClass $class - The ReflectionClass object
	 */
	protected function getParentClass(ReflectionClass $class){
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
	 * Analyzes the tested class methods and calculates various code metrics
	 */	
	protected function calculateMethodMetrics(){
		$reflect = new ReflectionClass($this->testedClassName);
		$methods = $reflect->getMethods();
		$classMethods = array();
		$methodLines = array();
		
		foreach ($methods as $method){
			if ($method->class == $this->testedClassName){
				$classMethods[] = $method->name; 
			}
		}

		foreach ($classMethods as $methodName){
			$method = new ReflectionMethod($this->testedClassName, $methodName);
			$methodLines[$method->getStartLine()] = $methodName;
			$this->methodMetrics[$methodName]['modifiers'] = implode(', ', Reflection::getModifierNames($method->getModifiers()));
			$this->methodMetrics[$methodName]['params'] = $method->getNumberOfParameters();
			$this->methodMetrics[$methodName]['startLine'] = $method->getStartLine();
			$this->methodMetrics[$methodName]['endLine'] = $method->getEndLine();
			$this->methodMetrics[$methodName]['loc'] = $method->getEndLine() - $method->getStartLine() + 1;
			$this->methodMetrics[$methodName]['docs'] = ($method->getDocComment()) ? 'Yes' : 'Undocumented';
			$this->methodMetrics[$methodName]['complexity'] = 1;			
		}
				
		/**
		 * Note that strings or heredoc syntax containing the following could provide false positives and as a result, the complexity will be higher
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
			$this->methodMetrics[$method]['refactor'] = number_format(((($metrics['loc']/200)+($metrics['complexity']/10)+($metrics['params']/10))/3)*100);
		}
	}	

	/**
	 * @return array - The associative class metrics array
	 */
	public function getClassMetrics(){
		return $this->classMetrics;
	}
	
	/**
	 * @return string - The refactor coverage bar as an HTML string
	 */	
	protected function renderRefactorBar(){
		$title = '<p>Refractor Probability attempts to analyze your code based on its length and complexity. Lower numbers are desired, higher numbers mean your code could be a candidate for refactoring.</p>';
		if ($this->classMetrics['numMethods'] > 0){
			$passPercentage = ($this->classMetrics['refactor'] <= 100) ? $this->classMetrics['refactor'] : 100;
			if ($passPercentage <= 30){
				$passMeter = '<span class="pass" style="width:'.$passPercentage.'%"></span>';
				$uncovered = "<p><strong>This class has a low Refactor Probability (".$passPercentage.'%) and does not need to be refactored.</strong></p>'; 
			} else if ($passPercentage <= 60){
				$passMeter = '<span class="warning" style="width:'.$passPercentage.'%"></span>';
				$uncovered = "<p><strong>This class has a medium Refactor Probability (".$passPercentage.'%) and may need to be refactored.</strong></p>';
			} else {
				$passMeter = '<span class="fail" style="width:'.$passPercentage.'%"></span>';
				$uncovered = "<p><strong>This class has a high Refactor Probability (".$passPercentage.'%) and should be refactored.</strong></p>';
			}
						
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'">'.$passMeter.'</div>';
			$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($title.$uncovered).'">Refactor Probability ('.$passPercentage.'%)</span>';		
		} else {
			$none = '<p>This class did not contain enough executable code to test, but it may extend another class.</p>';
			$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$none).'">Not Applicable</div>';
			$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($title.$none).'">Refactory Probability (N/A)</span>';	
		}
		
		return $view;
	}

	/**
	 * @return string - The readability factor bar as an HTML string
	 */	
	protected function renderReadabilityBar(){
		$title = '<p>The Readability Factor attempts to analyze your code for its readability. Improve your readability score by simplifying your code and properly commenting it.</p>';
		$passPercentage = ($this->classMetrics['readability'] <= 100) ? $this->classMetrics['readability'] : 100;
		if ($passPercentage > 70){
			$passMeter = '<span class="pass" style="width:'.$passPercentage.'%"></span>';
			$uncovered = "<p><strong>This class has high readability (".$passPercentage.'%).</strong></p>'; 
		} else if ($passPercentage >50){
			$passMeter = '<span class="warning" style="width:'.$passPercentage.'%"></span>';
			$uncovered = "<p><strong>This class has medium readability (".$passPercentage.'%) and may benefit from cleaning up or refactoring.</strong></p>';
		} else {
			$passMeter = '<span class="fail" style="width:'.$passPercentage.'%"></span>';
			$uncovered = "<p><strong>This class has low readability (".$passPercentage.'%) and should be refactored and/or cleaned up.</strong></p>';
		}
					
		$view = '<div class="meterContainer tips" title="'.htmlspecialchars($title.$uncovered).'">'.$passMeter.'</div>';
		$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($title.$uncovered).'">Readability Factor ('.$passPercentage.'%)</span>';		
	
		return $view;
	}	

	/**
	 * @return string - The method data table as an HTML string
	 */	
	protected function renderMethodsTable(){
		if (sizeof($this->methodMetrics) > 0){
			$view = '<h4>Method Data</h4>';
			$view .= '<table class="testTable" cellspacing="0" cellpadding="0" width="100%">';
			$view .= '<thead><tr><th>Method Name</th><th>Complexity</th><th class="tips" title="Lines of Code">LOC</th></tr></thead>';
			$view .= '<tbody>';
			
			foreach ($this->methodMetrics as $method=>$metrics){
				if ($metrics['loc'] < 100){
					$locClass = 'pass';
					$locTitle = 'This method is an appropriate length and probably doesn\'t need to be refactored';
				} else {
					$locClass = ($metrics['loc'] < 200) ? 'warning' : 'fail';
					$locTitle = ($metrics['loc'] < 200) ? 'This method is getting large and may require refactoring.' : 'This method is large and should be refactored.';
				}
				
				if ($metrics['complexity'] <= 7){
					$complexityClass = 'pass';
					$complexityTitle = 'This method is appropriately simple and probably doesn\'t need to be refactored';
				} else {
					$complexityClass = ($metrics['complexity'] <= 14) ? 'warning' : 'fail';
					$complexityTitle = ($metrics['complexity'] <= 14) ? 'This method is getting complex and may require refactoring.' : 'This method is too complex and should be refactored.';
				}

				if ($metrics['params'] <= 5){
					$paramsClass = 'pass';
				} else {
					$paramsClass = ($metrics['params'] <= 7) ? 'warning' : 'fail';
				}

				$docsClass = ($metrics['docs'] == 'Yes') ? 'pass' : 'fail';
				
				$info = '<span><strong>Method Name</strong>: '.$method.'</span><br/>';
				$info .= '<span><strong>Modifiers/Scope</strong>: '.$metrics['modifiers'].'</span><br/>';
				$info .= '<span><strong>Lines of Code</strong>: <span class="'.$locClass.'">'.$metrics['loc'].'</span></span><br/>';
				$info .= '<span><strong>Number of Parameters</strong>: <span class="'.$paramsClass.'">'.$metrics['params'].'</span></span><br/>';
				$info .= '<span><strong>Documented</strong>: <span class="'.$docsClass.'">'.$metrics['docs'].'</span></span><br/>';

				if ((sizeof($this->coveredMethods) > 0)&&(!in_array($method, $this->coveredMethods))){
					$info .= '<span><strong>Covered By At Least 1 Unit Test</strong>: <span class="fail">No</span></span><br/>';
				} else if (sizeof($this->coveredMethods) > 0){
					$info .= '<span><strong>Covered By At Least 1 Unit Test</strong>: <span class="pass">Yes</span></span><br/>';
				}
					
				$view .= '<tr><td class="tips" title="'.htmlspecialchars($info).'">'.$method.'</td>';
				$view .= '<td class="tips '.$complexityClass.'" title="'.$complexityTitle.'">'.number_format($metrics['complexity'], 2).'</td>';
				$view .= '<td class="tips '.$locClass.'" title="'.$locTitle.'">'.$metrics['loc'].'</td></tr>';
			}
			
			$view .= '</tbody></table>';
			return $view;
		} else {
			return '<p>This class did not contain any methods, perhaps because it may extend another class.</p>';
		}
	}	

	/**
	 * @return string - The metrics container as an HTML string
	 */	
	public function render(){
		$classComplexity = '<p>Class Complexity is based on the average Cyclomatic Complexity for each of the methods in the class. The more complex a class is, the higher the number. To minimize complexity, reduce the number of decision points in each method or refactor if necessary.</p>';
		if ($this->classMetrics['avgComplexity'] <=5){
			$cssClass = 'pass';
			$classComplexityRating = '<p><strong>This class has a low average complexity rating of '.number_format($this->classMetrics['avgComplexity'], 2).'.</strong></p>';
		} else if ($this->classMetrics['avgComplexity'] <=10){
			$cssClass = 'warning';
			$classComplexityRating = '<p><strong>This class has a medium average complexity rating of '.number_format($this->classMetrics['avgComplexity'], 2).' and may need to be refactored and simplified.</strong></p>';
		} else {
			$cssClass = 'fail';
			$classComplexityRating = '<p><strong>This class has a high average complexity rating of '.number_format($this->classMetrics['avgComplexity'], 2).' and should to be refactored and simplified.</strong></p>';
		}
		
		$parents = (sizeof($this->parents) > 0) ? '<ul><li>'.implode('</li><li>', $this->parents).'</li></ul>' : 'None';

		$usesClass = (sizeof($this->usesClass) > 0) ? '<ul><li>'.implode('</li><li>', $this->usesClass).'</li></ul>' : 'None';
		
		$view = '<div id="metrics-for-'.$this->testedClassName.'" class="metricsContainer">';			
			$view .= '<div class="infoContainer">';
				$view .= '<span class="complexity tips '.$cssClass.'" title="'.htmlspecialchars($classComplexity.$classComplexityRating).'">'.number_format($this->classMetrics['avgComplexity'], 2).'</span>';
				$view .= '<span class="meterCaption tips" title="'.htmlspecialchars($classComplexity.$classComplexityRating).'">Class Complexity</span>';
				$view .= $this->renderRefactorBar();
				$view .= $this->renderReadabilityBar();
				$view .= '<ul>';
					$view .= '<li class="tips" title="A list of the classes extended by '.$this->testedClassName.', in order of inheritance."><strong>Extends</strong>: '.$parents.'</li>';
					$view .= '<li class="tips" title="A list of the classes used or called internally by '.$this->testedClassName.'. This does not include parent classes."><strong>Uses</strong>: '.$usesClass.'</li>';
					$view .= '<li><strong>Total Lines Of Code</strong>: '.$this->classMetrics['totalLoc'].'</li>';
					$view .= '<li><strong>Number of Methods</strong>: '.$this->classMetrics['numMethods'].'</li>';
				$view .= '</ul>';
			$view .= '</div>';
			$view .= '<div class="resultsTableContainer">';
				$view .= '<h4 class="tips" title="Lines of Code Analysis">LOC Analysis</h4>';
				$view .= '<div id="'.$this->testedClassName.'LocHolder" title="Lines of Code Analysis, by percentage" class="locHolder tips">';
				$view .= '<table id="'.$this->testedClassName.'Loc" class="testTable locChart" cellspacing="0" cellpadding="0" width="100%">';
				$view .= '<thead><tr>';
					$view .= ($this->classMetrics['executableLoc'] > 0) ? '<th>Executable Code</th>' : NULL;
					$view .= ($this->classMetrics['commentsLoc'] > 0) ? '<th>Comments</th>' : NULL;
					$view .= ($this->classMetrics['whitespaceLoc'] > 0) ? '<th>Whitespace</th>' : NULL;
				$view .= '</tr></thead>';
				$view .= '<tbody><tr>';
					$view .= ($this->classMetrics['executableLoc'] > 0) ? '<td>'.$this->classMetrics['executableLoc'].'</td>' : NULL;
					$view .= ($this->classMetrics['commentsLoc'] > 0) ? '<td>'.$this->classMetrics['commentsLoc'].'</td>' : NULL;
					$view .= ($this->classMetrics['whitespaceLoc'] > 0) ? '<td>'.$this->classMetrics['whitespaceLoc'].'</td>' : NULL;				
				$view .= '</tr></tbody></table></div>';
				
				$view .= $this->renderMethodsTable();
			$view .= '</div>';			
		$view .= '</div>';
		return $view;			
	}
}