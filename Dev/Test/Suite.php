<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Builds a unit testing suite by scanning a given directory
*/
class Test_Suite {
	
	protected $authors = array();
	protected $coverage = array();
	protected $excludedAuthors = array();
	protected $excludedGroups = array();
	protected $excludedSubgroups = array();
	protected $fileNames = array();
	protected $groups = array();
	protected $includeCoverage;
	protected $includeMetrics;
	protected $numFiles = 0;
	protected $numTests = 0;
	protected $renderedTests = array();
	protected $results = array();
	protected $saveResults = FALSE;
	protected $subgroups = array();
	protected $testData = array();
	protected $testedClasses = array();
	protected $testedFileNames = array();
	protected $testNames = array();
	protected $testResults = array();
	
	/**
	 * @param string $rootDir - The root directory to be scanned, relative to the file calling this class, defaults to 'Vm'
	 * @param string $testDir - The test directory to be scanned, relative to the file calling this class, defaults to 'Tests/Vm'
	 * @param boolean $includeCoverage - optional - Whether or not to include code coverage statistics (it will run slower), defaults FALSE 
	 * @param boolean $includeMetrics - optional - Whether or not to include code analysis metrics, defaults FALSE
	 * @param boolean $saveResults - optional - Whether or not to save the test results to track over time, defaults FALSE
	 */
	function __construct($rootDir = 'Vm', $testDir = 'Tests/Vm', $includeCoverage = FALSE, $includeMetrics = FALSE, $saveResults = FALSE){
		$this->includeCoverage = $includeCoverage;
		$this->includeMetrics = $includeMetrics;
		$this->saveResults = $saveResults;
		$this->scanDirectory($rootDir, $testDir);		
	}

	/**
	 * Recursively scans the root directory and finds and categorizes both files and tests
	 * This function modified from http://ca2.php.net/manual/en/function.scandir.php#88006
	 * @param string $rootDir - The root directory to be scanned, relative to the file calling this class
	 * @param string $testDir - The test directory to be scanned, relative to the file calling this class
	 */
	protected function scanDirectory($rootDir, $testDir){
	    $dirs = array_diff(scandir($rootDir), array(".", ".."));
	    $testDirs = array_diff(scandir($testDir), array(".", ".."));
	    $dirArray = array();
	    foreach($dirs as $resource){
	        if (is_dir($rootDir."/".$resource)) {
	        	$this->scanDirectory($rootDir."/".$resource, $testDir."/".$resource);
	        } else if (substr(strrchr($resource, '.'), 1) == 'php'){
				$resourceTest = str_replace('.php', 'Test.php', $resource);
				if (is_file($testDir.'/'.$resourceTest)){
					$this->numTests = $this->numTests + 1;
	        		$testName = preg_replace('#/#', '_', $testDir);
	        		$testName = $testName.'_'.$resourceTest;
	        		$testName = preg_replace('#(\.php)$#', '', $testName);
	        		$this->testNames[] = $testName;
	        		$this->populateTestData($testName);
	        		$tested = preg_replace('#(Test.php)$#', '.php', $resource);
	        		$this->testedFileNames[] = $rootDir.'/'.$tested;
	        		$this->numFiles = $this->numFiles + 1;
	        	} else if (preg_match('#(\.php)$#', $resource)){ //Unit tests won't exist for non-php files
	        		$this->fileNames[] = $rootDir.'/'.$resource;
	        		$this->numFiles = $this->numFiles + 1;
	        	}
	        }
	    }
	}

	/**
	 * Sets the list of authors whose tests should be included
	 * @param array $authors - An array of test authors
	 */
	public function setAuthors(array $authors){
		$this->authors = $authors;
	}
	
	/**
	 * @return array - The list of authors whose tests should be included
	 */
	public function getAuthors(){
		return $this->authors;
	}
	
	/**
	 * Sets the list of authors whose tests should be excluded
	 * @param array $authors - An array of test authors
	 */
	public function setExcludedAuthors(array $authors){
		$this->excludedAuthors = $authors;
	}

	/**
	 * @return array - The list of authors whose tests should be excluded
	 */
	public function getExcludedAuthors(){
		return $this->excludedAuthors;
	}	

	/**
	 * Sets the list of groups for which tests should be included
	 * @param array $groups - An array of test groups
	 */
	public function setGroups(array $groups){
		$this->groups = $groups;
	}
	
	/**
	 * @return array - The list of groups for which tests should be included
	 */
	public function getGroups(){
		return $this->groups;
	}
	
	/**
	 * Sets the list of groups for which tests should be excluded
	 * @param array $groups - An array of test groups
	 */
	public function setExcludedGroups(array $groups){
		$this->excludedGroups = $groups;
	}

	/**
	 * @return array - The list of groups for which tests should be excluded
	 */
	public function getExcludedGroups(){
		return $this->excludedGroups;
	}	

	/**
	 * Sets the list of subgroups for which tests should be included
	 * @param array $subgroups - An array of test subgroups
	 */
	public function setSubgroups(array $subgroups){
		$this->subgroups = $subgroups;
	}
	
	/**
	 * @return array - The list of subgroups for which tests should be included
	 */
	public function getSubgroups(){
		return $this->subgroups;
	}
	
	/**
	 * Sets the list of subgroups for which tests should be excluded
	 * @param array $subgroups - An array of test subgroups
	 */
	public function setExcludedSubgroups(array $subgroups){
		$this->excludedSubgroups = $subgroups;
	}

	/**
	 * @return array - The list of subgroups for which tests should be excluded
	 */
	public function getExcludedSubgroups(){
		return $this->excludedSubgroups;
	}	
	
	/**
	 * Gets the files that have a unit test associated to them
	 * @return array - Unit tested file names
	 */
	public function getTestedFiles(){
		return $this->testedFileNames;
	}

	/**
	 * Gets the files that do not have a unit test associated to them
	 * @return array - Non-unit tested file names
	 */
	public function getUntestedFiles(){
		return array_diff($this->fileNames, $this->testedFileNames);
	}
	
	/**
	 * Gets the metadata for all tests
	 * @return array - A multi-dimensional array: array(testName=>array(author=>authorName, group=>groupName, subgroup=>subgroupName, description=>description))
	 */
	public function getTestData(){
		return $this->testData;
	}	
	
	/**
	 * Runs a test and logs its results
	 * @param string $testName - The name of the test to be run
	 */
	protected function runTest($testName){
		$test = new $testName($this->includeCoverage);
		$test->runAllTests();
		
		$this->coverage[$testName] = $test->getCoverage();	
		$this->testResults[$testName] = $test->getResults();
		$this->testedClasses[$testName] = array('name'=>$test->getTestedClassName(), 'numMethods'=>$test->getNumMethodsTestedClass()); 
	}
	
	/**
	 * Runs all tests in indicated directory
	 */
	public function runAllTests(){
		foreach ($this->testNames as $testName){
			if ($this->testShouldBeRun($testName)){
				$this->runTest($testName);
			}
		}
		$this->compileResults();
	}

	/**
	 * Retrieves test meta data from the test class
	 * @param string $testName - The name of the test to be run
	 */
	protected function populateTestData($testName){
		$testClass = new ReflectionClass($testName);
		$testData = $testClass->getDocComment();
		$testData = explode('*', $testData);
		$this->testData[$testName] = array();
		
		foreach ($testData as $data){
			if (strpos($data, '@author')){
				$data = trim(str_replace(':', '', str_replace('@author', '', $data)));
				$this->testData[$testName]['author'] = (!empty($data))? $data : 'Unspecified';
			} else if (strpos($data, '@group')){
				$data = trim(str_replace(':', '', str_replace('@group', '', $data)));
				$this->testData[$testName]['group'] = (!empty($data))? $data : 'Unspecified';
			} else if (strpos($data, '@subgroup')){
				$data = trim(str_replace(':', '', str_replace('@subgroup', '', $data)));
				$this->testData[$testName]['subgroup'] = (!empty($data))? $data : 'Unspecified';
			} else if (strpos($data, '@description')){
				$data = trim(str_replace(':', '', str_replace('@description', '', $data)));
				$this->testData[$testName]['description'] = (!empty($data))? $data : 'No description available';
			}
		}
		
		$testData = implode(' ', $testData);

		if (!strpos($testData, '@author')){
			$this->testData[$testName]['author'] = 'Unspecified';
		}

		if (!strpos($testData, '@group')){
			$this->testData[$testName]['group'] = 'Unspecified';
		}		

		if (!strpos($testData, '@subgroup')){
			$this->testData[$testName]['subgroup'] = 'Unspecified';
		}
		
		if (!strpos($testData, '@description')){
			$this->testData[$testName]['description'] = 'No description available';
		}							
	}
	
	/**
	 * Determines whether or not a test should be run
	 * @param string $testName - The name of the test to be run
	 * @return boolean - TRUE if the test should be run, FALSE otherwise
	 */
	protected function testShouldBeRun($testName){
		if ((in_array($this->testData[$testName]['author'], $this->excludedAuthors))
			||(in_array($this->testData[$testName]['group'], $this->excludedGroups))
			||(in_array($this->testData[$testName]['subgroup'], $this->excludedSubgroups))){
			return FALSE;
		}
		
		if (((!in_array($this->testData[$testName]['author'], $this->authors)) && (sizeof($this->authors) > 0))
			||((!in_array($this->testData[$testName]['group'], $this->groups)) && (sizeof($this->groups) > 0))
			||((!in_array($this->testData[$testName]['subgroup'], $this->subgroups)) && (sizeof($this->subgroups) > 0))){
			return FALSE;
		}			
		return TRUE;
	}

	/**
	 * Compiles the results of all selected tests
	 */
	protected function compileResults(){
		foreach ($this->testResults as $name=>$results){
			$test = new Test_Render_Test($this->testedClasses[$name]['name'], $this->testData[$name], $results, $this->includeCoverage, $this->coverage[$name], $this->saveResults);
			$test->includeMetrics($this->includeMetrics);			
			$this->results[$this->testedClasses[$name]['name']] = $test->getResults();
			$this->renderedTests[] = $test->render();			
		}		
	}

	/**
	 * @return array - An associative array of test results
	 */
	public function getResults(){
		return $this->results;
	}

	/**
	 * @return array - The rendered tests as an array of strings, each value a single test
	 */
	public function getRenderedTests(){
		return $this->renderedTests;
	}
}