<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Loads the test suite data
 * @namespace Suite\Model\Tests
 */
namespace Suite\Model\Tests;

class Suite extends \Vm\Model {

	protected $authors = array();
	protected $coverage;
	protected $filterData = array();
	protected $formFilterData = array();
	protected $form;
	protected $groups = array();
	protected $history;
	protected $includeCoverage = FALSE;
	protected $includeMetrics =  FALSE;
	protected $metrics;
	protected $results;
	protected $saveResults = FALSE;
	protected $settings;
	protected $subgroups = array();
	protected $testResults = array();
	protected $testSuite;
	
	/**
	 * @param array $settings - The framework settings array. 
	 */
	function __construct(array $settings){
		$this->settings = $settings;
		$this->loadTestSuite();	
	}

	protected function loadTestSuite(){
		if ((isset($_POST['reports']))&&($_POST['reports'] == 'new')){
			$this->includeCoverage = (isset($_POST['coverage'])) ? TRUE : FALSE;
			$this->includeMetrics = (isset($_POST['metrics'])) ? TRUE : FALSE;
		} else {
			$this->loadReport();
		}
		
		$this->saveResults = (isset($_POST['saveResults'])) ? TRUE : FALSE;
		$this->testSuite = new \Test\Suite(
			'../Includes/Vm', 
			'Tests/Vm', 
			$this->includeCoverage, 
			$this->includeMetrics, 
			$this->saveResults
		);		
	}

	protected function loadReport(){
		if ((isset($_POST['reports']))&&(preg_match('/^[a-zA-Z0-9-.]*$/', $_POST['reports']))){
			if (file_exists('Test/Reports/Suite/'.$_POST['reports'])){
				$report = file('Test/Reports/Suite/'.$_POST['reports'], FILE_IGNORE_NEW_LINES);
				$this->filterData = json_decode($report[0], TRUE);
				$this->includeCoverage = $this->filterData['includeCoverage'];
				$this->includeMetrics = $this->filterData['includeMetrics'];
			}			
		} 
	}

	/**
	 * @description Sets the filter data, either from the form or a report
	 */
	protected function setFilterData(){
		$data = ((isset($_POST['reports']))&&($_POST['reports'] == 'new')) ? $this->formFilterData : $this->filterData;	
		if (sizeof($data['includedAuthors']) > 0){
			$this->testSuite->setAuthors($data['includedAuthors']);
		} else if (sizeof($data['excludedAuthors']) > 0){
			$this->testSuite->setExcludedAuthors($data['excludedAuthors']);
		}
		
		if (sizeof($data['includedGroups']) > 0){
			$this->testSuite->setGroups($data['includedGroups']);
		} else if (sizeof($data['excludedGroups']) > 0){
			$this->testSuite->setExcludedGroups($data['excludedGroups']);
		}

		if (sizeof($data['includedSubgroups']) > 0){
			$this->testSuite->setSubgroups($data['includedSubgroups']);
		} else if (sizeof($data['excludedSubgroups']) > 0){
			$this->testSuite->setExcludedSubgroups($data['excludedSubgroups']);
		}
	}	
	
	protected function saveResults(){
		$results = array_merge(
			$this->results->getViewData(), 
			$this->coverage->getViewData(), 
			$this->metrics->getViewData()
		);	
		$saveResults = new \Test\Save\Suite($results, $this->includeCoverage, $this->includeMetrics);
		$saveResults->setAuthors($this->testSuite->getAuthors());
		$saveResults->setExcludedAuthors($this->testSuite->getExcludedAuthors());
		$saveResults->setGroups($this->testSuite->getGroups());
		$saveResults->setExcludedGroups($this->testSuite->getExcludedGroups());
		$saveResults->setSubgroups($this->testSuite->getSubgroups());
		$saveResults->setExcludedSubgroups($this->testSuite->getExcludedSubgroups());
		$saveResults->save();		
	}

	protected function processForm(){
		$isNewReport = ((!isset($_POST['saveResults']))&&((!isset($_POST['reports']))||(isset($_POST['reports']))&&($_POST['reports'] == 'new'))) ? TRUE : FALSE;
		if (($isNewReport)||((isset($_POST['saveResults']))&&($_POST['reports'] == 'new'))){
			$excludedAuthors = array();
			$excludedGroups = array();
			$excludedSubgroups = array();
			$includedAuthors = array();
			$includedGroups = array();
			$includedSubgroups = array();

			$include = ($this->form->getValue('include') == 'include') ? TRUE : FALSE;
			
			foreach ($this->authors as $author){
				$authorString = 'author-'.preg_replace('#[^a-zA-Z_0-9]#', '', $author);
				if ($this->form->getValue($authorString)){
					if ($include){
						$includedAuthors[] = $author;
					} else {
						$excludedAuthors[] = $author;
					}
				}
			}

			foreach ($this->groups as $group){
				$groupString = 'group-'.preg_replace('#[^a-zA-Z_0-9]#', '', $group);
				if ($this->form->getValue($groupString)){
					if ($include){
						$includedGroups[] = $group;
					} else {
						$excludedGroups[] = $group;
					}
				}
			}				
			
			foreach ($this->subgroups as $subgroup){
				$subgroupString = 'subgroup-'.preg_replace('#[^a-zA-Z_0-9]#', '', $subgroup);
				if ($this->form->getValue($subgroupString)){
					if ($include){
						$includedSubgroups[] = $subgroup;
					} else {
						$excludedSubgroups[] = $subgroup;
					}
				}
			}
			
			//Reverse the filters if any are marked unspecified
			if (in_array('Unspecified', $includedAuthors)){
				$excludedAuthors = array_diff($this->authors, $includedAuthors);
				$includedAuthors = array();
			} else if (in_array('Unspecified', $excludedAuthors)){
				$includedAuthors = array_diff($this->authors, $excludedAuthors);
				$excludedAuthors = array();
			}

			if (in_array('Unspecified', $includedGroups)){
				$excludedGroups = array_diff($this->groups, $includedGroups);
				$includedGroups = array();
			} else if (in_array('Unspecified', $excludedGroups)){
				$includedGroups = array_diff($this->groups, $excludedGroups);
				$excludedGroups = array();
			}

			if (in_array('Unspecified', $includedSubgroups)){
				$excludedSubgroups = array_diff($this->subgroups, $includedSubgroups);
				$includedSubgroups = array();
			} else if (in_array('Unspecified', $excludedSubgroups)){
				$includedSubgroups = array_diff($this->subgroups, $excludedSubgroups);
				$excludedSubgroups = array();
			} 			

			$this->formFilterData = array(
				'includedAuthors'=>$includedAuthors,
				'excludedAuthors'=>$excludedAuthors,
				'includedGroups'=>$includedGroups,
				'excludedGroups'=>$excludedGroups,
				'includedSubgroups'=>$includedSubgroups,
				'excludedSubgroups'=>$excludedSubgroups,						
			);
		}	
	}

	/**
	 * @description Sets the form object so the suite has access to its data.
	 * @param Test_SelectionForm $form
	 */
	public function setForm(\Test\SelectionForm $form){
		$this->authors = $form->getAuthors();
		$this->groups = $form->getGroups();
		$this->subgroups = $form->getSubgroups();
		$this->form = $form->getForm();
	}
	
	/**
	 * @description Runs the tests
	 * @return mixed - The rendered test results if they should be displayed, otherwise NULL
	 */	
	public function run(){
		if ($this->displayResults()){
			$this->processForm();
			$this->setFilterData();
			$this->testSuite->runAllTests();
			$this->testResults = $this->testSuite->getResults();
			$this->compileData();
		} 
	}
	
	/**
	 * @description Compiles the results of all selected tests
	 */	
	protected function compileData(){
		$testResults = array();
		$testData = $this->testSuite->getTestData();
		$testedClasses = $this->testSuite->getTestedClasses();
		$coverage = $this->testSuite->getCoverage();
		
		foreach ($this->testResults as $name=>$results){
			$test = new Tests(
				$testedClasses[$name]['name'], 
				$testData[$name], 
				$results, 
				array($this->includeCoverage, $this->includeMetrics), 
				$coverage[$name], 
				$this->settings, 
				$this->saveResults
			);
		
			$testResults[$testedClasses[$name]['name']] = $test->getResults();
		}
		
		$this->results = new \Test\Model\Suite\Results($testResults);
		$includeData = $this->results->getViewData(); 
		
		if ($this->includeCoverage){
			$this->coverage = new \Test\Model\Suite\Coverage($testResults);
			$includeData = array_merge($includeData, $this->coverage->getViewData());
		}
		
		if ($this->includeMetrics){
			$this->metrics = new \Test\Model\Suite\Metrics($testResults);
			$includeData = array_merge($includeData, $this->metrics->getViewData());
		}

		if (isset($_POST['saveResults'])){			
			$this->saveResults();
		}			
		
		$this->history = new \Test\Model\Suite\History();
		$includeData = array_merge($includeData, $this->history->getViewData());				
		
		$this->setData('tests', $testResults);
		$this->setData('includeMetrics', $this->includeMetrics);
		$this->setData('includeCoverage', $this->includeCoverage);
		
		foreach ($includeData as $var=>$value){
			$this->setData($var, $value);
		}
	}
		
	/**
	 * @description Checks to see if the form has been submitted and if test results should be displayed.
	 * @return Returns boolean. TRUE if the test results should be displayed, FALSE otherwise.
	 */
	public function displayResults(){
		return (($this->form->submitted()) && (!$this->form->errorsExist())) ? TRUE : FALSE;
	}
	
	/**
	 * @return string - The rendered test suite and results
	 */
	public function getSuite(){
		return $this->testSuite;
	}	
}