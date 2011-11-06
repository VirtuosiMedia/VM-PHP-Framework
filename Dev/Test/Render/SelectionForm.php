<?php
/**
* @author: Virtuosi Media Inc.
* @license: MIT License
* Description: Renders the report selection form
*/
class Test_Render_SelectionForm {
	
	protected $authors = array();
	protected $authorsData = array();
	protected $filterData = array();
	protected $form;
	protected $groups = array();
	protected $groupsData = array();
	protected $includeCoverage = FALSE;
	protected $includeMetrics = FALSE;		
	protected $isNewReport = TRUE;
	protected $subgroups = array();
	protected $subgroupsData = array();
	protected $testData;
	protected $testSuite;
	protected $xml;
	
	function __construct(Test_Suite $testSuite){
		$this->testSuite = $testSuite;
		$this->xml = new Vm_Xml();
		$this->isNewReport = ((!isset($_POST['saveResults']))&&((!isset($_POST['reports']))||(isset($_POST['reports']))&&($_POST['reports'] == 'new'))) ? TRUE : FALSE; 
		
		$this->compileFilters();
		$this->buildForm();
		$this->processForm();		
	}

	/**
	 * Compiles the data to create the test filters from the test suite
	 */
	protected function compileFilters(){
		$testData = $this->testSuite->getTestData();
	
		foreach($testData as $testName=>$data){
			$author = preg_replace('#[^a-zA-Z_0-9 ]#', '', $data['author']);
			$authorString = 'author-'.str_replace(' ', '', $author);
			$group = preg_replace('#[^a-zA-Z_0-9 ]#', '', $data['group']);
			$groupString = 'group-'.str_replace(' ', '', $group);
			$subgroup = preg_replace('#[^a-zA-Z_0-9 ]#', '', $data['subgroup']);
			$subgroupString = 'subgroup-'.str_replace(' ', '', $subgroup);

			/**
			 * The author name and subgroup name are assigned to the group so that the group can be dynamically filtered
			 * based on whether or not it is included in selected authors or subgroups. Similar logic applies to the
			 * authors and subgroups below.
			 */
			if (!array_key_exists($group, $this->groupsData)){
				$this->groupsData[$group] = array($authorString, $subgroupString);
				$this->groups[] = $data['group'];
			} else {
				if (!in_array($authorString, $this->groupsData[$group])){
					$this->groupsData[$group][] = $authorString;
				}
				if (!in_array($subgroupString, $this->groupsData[$group])){
					$this->groupsData[$group][] = $subgroupString;
				}				
			}

			if (!array_key_exists($subgroup, $this->subgroupsData)){
				$this->subgroupsData[$subgroup] = array($authorString, $groupString);
				$this->subgroups[] = $data['subgroup']; 
			} else {
				if (!in_array($authorString, $this->subgroupsData[$subgroup])){
					$this->subgroupsData[$subgroup][] = $authorString;
				}
				if (!in_array($groupString, $this->subgroupsData[$subgroup])){
					$this->subgroupsData[$subgroup][] = $groupString;
				}				
			}

			if (!array_key_exists($author, $this->authorsData)){
				$this->authorsData[$author] = array($groupString, $subgroupString);
				$this->authors[] = $data['author']; 
			} else {
				if (!in_array($groupString, $this->authorsData[$author])){
					$this->authorsData[$author][] = $groupString;
				}
				if (!in_array($subgroupString, $this->authorsData[$author])){
					$this->authorsData[$author][] = $subgroupString;
				}								
			}			
		}		
	}

	/**
	 * @return array - The report file names as keys, the report names as values
	 */
	protected function getReports(){
		$reports = array();
		$files = scandir('Tests/Test/Reports/Suite');
		foreach ($files as $file){
			if (substr(strrchr($file, '.'), 1) == 'json'){
				$data = file('Tests/Test/Reports/Suite/'.$file, FILE_IGNORE_NEW_LINES);
				$record = json_decode($data[0], TRUE);
				$reports[$record['filename']] = $record['reportName'];			
			}
		}

		if ((isset($_POST['reports']))&&($_POST['reports'] == 'new')&&(isset($_POST['saveResults']))&&(isset($_POST['saveResultsName']))){
			$filename = str_replace(' ', '-', $_POST['saveResultsName']).'.json';
			$reports[$filename] = $_POST['saveResultsName'];
		}
		asort($reports);
		
		return array_merge(array('new'=>'Run New Report'), $reports);
	}

	/**
	 * @return array - The selected report, in an array
	 */	
	protected function getSelectedReport(){
		if ((isset($_POST['reports']))&&($_POST['reports'] != 'new')){
			$selected = array($_POST['reports']);
		} else if ((isset($_POST['reports']))&&($_POST['reports'] == 'new')&&(isset($_POST['saveResults']))&&(isset($_POST['saveResultsName']))){
			$selected = array(str_replace(' ', '-', $_POST['saveResultsName']).'.json');
		} else {
			$selected = array();
		}
		return $selected;
	}
	
	protected function buildForm(){
		$coverageAttributes = (isset($_POST['coverage'])) ? array('id'=>'coverage', 'value'=>'TRUE', 'checked'=>'checked') : array('id'=>'coverage', 'value'=>'TRUE');
		$metricsAttributes = (isset($_POST['metrics'])) ? array('id'=>'metrics', 'value'=>'TRUE', 'checked'=>'checked') : array('id'=>'metrics', 'value'=>'TRUE');
		$saveResultsAttributes = (isset($_POST['saveResults'])) ? array('id'=>'saveResults', 'value'=>'TRUE', 'checked'=>'checked') : array('id'=>'saveResults', 'value'=>'TRUE');
		$selectedInclude = (isset($_POST['include'])) ? array($_POST['include']) : array(); 
		
		$this->form = new Vm_Form(array('id'=>'testForm'));		
		$this->form->select('reports', array(
			'attributes'=>array('id'=>'reports', 'class'=>'reports'),
			'selected'=>$this->getSelectedReport(),
			'selectOptions'=>$this->getReports()
		));
		$this->form->startTag('div', array('class'=>'newReport'));
		if (function_exists('xdebug_start_code_coverage')){
			$this->form->checkbox('coverage', array(
				'attributes'=>$coverageAttributes,	
				'labelPosition'=>'afterInput',
				'label' => array('innerHtml'=>'coverage analysis (slower)', 'id'=>'coverageLabel'),
				'wrapperElement'=>'span',
				'wrapperAttributes' => array(
					'class'=>'checkContainer tips',
					'title'=>'Code Coverage',
					'rel'=>'Selecting this box will enable code coverage analysis. Your unit tests will run slightly slower,'.
							' but you will be able to see which code was executed by your tests and which code was not. To access the results, click on the'.
							' coverage tab for each test.'
				)			
			));
		}
		$this->form->checkbox('metrics', array(
			'attributes'=>$metricsAttributes,	
			'labelPosition'=>'afterInput',
			'label' => array('innerHtml'=>'code metrics', 'id'=>'metricsLabel'),
			'wrapperElement'=>'span',
			'wrapperAttributes' => array(
				'class'=>'checkContainer tips',
				'title'=>'Code Metrics',
				'rel'=>'Selecting this box will generate a code metrics report for a variety of factors on both a class and method level.'
			)			
		));
		$this->form->endTag('div');
		$this->form->checkbox('saveResults', array(
			'attributes'=>$saveResultsAttributes,	
			'labelPosition'=>'afterInput',
			'label' => array('innerHtml'=>'save results', 'id'=>'saveResultsLabel'),
			'wrapperElement'=>'span',
			'wrapperAttributes' => array(
				'class'=>'checkContainer tips',
				'title'=>'Save Results',
				'rel'=>'Selecting this box will save the results of the test suite, to be tracked over time. It\'s recommended that you also enable both '.
						'code coverage and code metrics to ensure the collection of useful statistics.'		
			)			
		));
					
		$this->form->startTag('div', array('class'=>'newReport'));
		$this->form->text('saveResultsName', array(
			'attributes'=>array('class'=>'tips mask', 'id'=>'saveResultsName', 'title'=>'Report Name', 'rel'=>'Only letters, numbers, and spaces are allowed.'),
			'label' => array('innerHtml'=>'Report Name', 'id'=>'saveResultsNameLabel'),
			'validators' => array('AlnumSpace'=>'Letters, numbers, and spaces only.')
		));	
		$this->form->select('include', array(
			'attributes'=>array('class'=>'includeSelect', 'id'=>'includeSelect'),
			'selected'=>$selectedInclude,
			'selectOptions'=>array('include'=>'include', 'exclude'=>'exclude'),
			'validators' => array('Alpha'=>'Letters only.')
		));
		
		if (sizeof($this->groupsData) > 1){
			$this->form->startTag('div', array('class'=>'multiSelect', 'id'=>'groups'));
			$this->form->append($this->xml->h3('Groups', array('class'=>'testSelectTitle')));
			foreach ($this->groupsData as $group=>$rel){
				$groupString = 'group-'.str_replace(' ', '', $group);
				$attributes = (isset($_POST[$groupString])) ? array('id'=>$groupString, 'value'=>'TRUE', 'checked'=>'checked') : array('id'=>$groupString, 'value'=>'TRUE');
				$this->form->checkbox($groupString, array(
					'attributes'=>$attributes,
					'labelPosition'=>'afterInput',
					'label' => array('innerHtml'=>$group),
					'wrapperElement'=>'span',
					'wrapperAttributes' => array(
						'class'=>'checkContainer',
						'rel'=>implode(' ', $rel)
					)			
				));
			}
			$this->form->endTag('div');
		}
		
		if (sizeof($this->subgroupsData) > 1){
			$this->form->startTag('div', array('class'=>'multiSelect', 'id'=>'subgroups'));
			$this->form->append($this->xml->h3('Subgroups', array('class'=>'testSelectTitle')));
			foreach ($this->subgroupsData as $subgroup=>$rel){
				$subgroupString = 'subgroup-'.str_replace(' ', '', $subgroup);
				$attributes = (isset($_POST[$subgroupString])) ? array('id'=>$subgroupString, 'value'=>'TRUE', 'checked'=>'checked') : array('id'=>$subgroupString, 'value'=>'TRUE');
				$this->form->checkbox($subgroupString, array(
					'attributes'=>$attributes,	
					'labelPosition'=>'afterInput',
					'label' => array('innerHtml'=>$subgroup),
					'wrapperElement'=>'span',
					'wrapperAttributes' => array(
						'class'=>'checkContainer',
						'rel'=>implode(' ', $rel)
					)			
				));
			}
			$this->form->endTag('div');
		}

		if (sizeof($this->authorsData) > 1){
			$this->form->startTag('div', array('class'=>'multiSelect', 'id'=>'authors'));
			$this->form->append($this->xml->h3('Authors', array('class'=>'testSelectTitle')));
			foreach ($this->authorsData as $author=>$rel){
				$authorString = 'author-'.str_replace(' ', '', $author);
				$attributes = (isset($_POST[$authorString])) ? array('id'=>$authorString, 'value'=>'TRUE', 'checked'=>'checked') : array('id'=>$authorString, 'value'=>'TRUE');
				$this->form->checkbox($authorString, array(
					'attributes'=>$attributes,	
					'labelPosition'=>'afterInput',
					'label' => array('innerHtml'=>$author),
					'wrapperElement'=>'span',
					'wrapperAttributes' => array(
						'class'=>'checkContainer',
						'rel'=>implode(' ', $rel)
					)			
				));
			}
			$this->form->endTag('div');
		}		
		$this->form->endTag('div');
		$this->form->submit(array('value'=>'Run Report', 'class'=>'submit', 'id'=>"launch"));
	}

	protected function processForm(){
		if (($this->form->submitted()) && (!$this->form->errorsExist())){ 
			if (($this->isNewReport)||((isset($_POST['saveResults']))&&($_POST['reports'] == 'new'))){
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
	
				$this->filterData = array(
					'includedAuthors'=>$includedAuthors,
					'excludedAuthors'=>$excludedAuthors,
					'includedGroups'=>$includedGroups,
					'excludedGroups'=>$excludedGroups,
					'includedSubgroups'=>$includedSubgroups,
					'excludedSubgroups'=>$excludedSubgroups,						
				);
			}	
		}
	}

	/**
	 * @return array - An associative array of the filter data for the test suite
	 */
	public function getFilterData(){
		return $this->filterData;	
	}
	
	/**
	 * @return boolean - TRUE if the results of the test suite should be displayed after the form has been submitted without errors, FALSE otherwise
	 */
	public function displayResults(){
		return (($this->form->submitted()) && (!$this->form->errorsExist())) ? TRUE : FALSE;
	}
	
	/**
	 * @return string - The rendered form and content area
	 */
	public function render(){
		if (file_exists('includes/Version.php')){ 
			$version = new Version();
			$title = $version->get('name').' Test Suite';	
		} else {
			$title = 'VM PHP Framework Test Suite';
		}		
		
		return $this->xml->div($this->xml->h3($title, array('class'=>'title')).$this->form->render(), array('id'=>'reportGenerator'));		
	}
}