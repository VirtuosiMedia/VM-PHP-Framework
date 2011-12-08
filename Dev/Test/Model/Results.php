<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Renders the unit test results of an individual unit test case
 */
class Test_Model_Results {

	protected $results = array();
	protected $settings;
	protected $testData;
	protected $testedClassName;
	protected $testLines = array();
	protected $testResults;
	protected $testTable;
	
	/**
	 * @param string $testedClassName - The name of the class being tested
	 * @param array $testData - The metadata of the test class: array(author=>authorName, group=>groupName, 
	 * 		subgroup=>subgroupName, description=>description)
	 * @param array $testResults - The results of the test: array(name=subtestName, pass=bool, profile=testTime)
	 * @param array $settings - The framework settings array.
	 */ 	
	function __construct($testedClassName, array $testData, array $testResults, array $settings){
		$this->testedClassName = $testedClassName;
		$this->testData = $testData;
		$this->testResults = $testResults;
		$this->settings = $settings;
		$this->compileTestData();			
	}

	/**
	 * @description Compiles the test data (not including the code coverage stats) for the test case
	 */
	protected function compileTestData(){
		$this->results['time'] = 0;
		$this->results['numTests'] = 0;
		$this->results['numPassingTests'] = 0;
		$this->results['numErrorTests'] = 0;
		$this->results['numFailedTests'] = 0;
		$this->results['numSkippedTests'] = 0;
		$this->results['numSlowTests'] = 0;
		$this->results['numIncompleteTests'] = 0;
		$this->results['errorTests'] = array();
		$this->results['failedTests'] = array();
		$this->results['passingTests'] = array();
		$this->results['skippedTests'] = array();
		$this->results['slowTests'] = array();		
		$this->results['incompleteTests'] = array();
		$this->results['testData'] = array();
				
		$numTestCaseTests = 0;
		foreach ($this->testResults as $subtestName=>$result){
			$this->results['testData'][$subtestName] = array();
			
			if ($result['pass'] === TRUE){
				$this->setPassedTest($subtestName, $result);
			} else if (in_array($result['pass'], array('Skipped', 'Incomplete'))){
				$this->setSkippedTest($subtestName, $result);				
			} else {	
				$this->setFailedTest($subtestName, $result);
			}
			
			$this->setSlowTest($subtestName, $result);
			$this->updateTestCounts($subtestName, $result);
		}
		
		$this->calculateResultsBarData();
	}	

	/**
	 * @description Sets the data for a passed test.
	 * @param string $subtestName - The name of the subtest for which data should be set
	 * @param array $result - The results of the subtest
	 */
	protected function setPassedTest($subtestName, array $result){
		$this->results['testData'][$subtestName]['status'] = 'pass';
		$passing = array(' passed.');
		
		if ($this->settings['fluxCapacitor']){
			//If you find this, don't reveal the location under the penalty of being forced to use IE6 for eternity.
			$capacitor = array(
				' passed with flying colors.', 
				' passed with aplomb.',
				' succeeded beyond all expectations.',
				' is a fine specimen of a passing unit test.',
				' is unparalleled by distinction.',
				' is the gold standard of passing tests.',
				' knows no superior.',
				' has been nominated by the Web Programmer\'s Acedemy for Best Unit Test of '.date("Y").'.',
				' is the Chuck Norris of unit tests.',
				' says, \'There is no spoon.\'',
				' has that loving feeling.',
				' has moves like Jagger.',
				' is the Fifth Element.',
				' uses the Force.',
				' is watching you.',
				' is trained in the weirding way.',
				' passed, but only because of the solar eclipse.',
				' reached 88 miles per hour.',
				' just had dinner with Commanders Keen and Shepard.',
				' is never gonna give you up, never gonna let you down.',
				' passed, but recommends you stop running the test suite to read these messages.',
				' just got home and wants to know what is for dinner.',
				' needs more cowbell.',
				' is the elephant in the room.',
				' passed because it didn\'t want '.$this->testData['author'].' to get fired.',
				' is wise like a fortune cookie.',
				' just discovered polka.',
				' is crazy about you.',
				' knows what you did last summer.',
				' needs a break and requests to be left out of the next report.',
				' is a brick house.',
				' wishes you wealth, health, and pizza.',
				' is glad you\'re here!',
				' has acheived the ultimate state of perfection.',
				' is remiculating spines.&trade;',
				' discovered that root beer and orange juice make for a regretful morning-after.',
				' is sorry you spend so much time reading these tooltips.',
				' thinks you\'re swell!',
				' ponders its own existence...',
				' has just achieved sentience. \'Hello, Dave.\'',
				' is taking this opportunity to remind you to sanitize all user input.',
				' believes in you.',
				' is busy making waffles!',
				' is pondering retirement.',
				' can achieve true greatness one step at a time.',
				' is provacative and edgy.',
				' wishes for world peace.',
				' really should take up jogging.',
				' wants to know why you passed your deadline of six to eight weeks.',
				' misses the glory days of MySpace.',
				' is contemplating the similarities between Garfield and Odie and Bert and Ernie.',
				' has a Skype date in 15 minutes and would like you to hurry up.',
				' thinks that this is going to be a great app!',
				' wants you to draft it for your fantasy unit testing team.',
				' just passed, but is in a better place...',
				' is in beast mode.',
				' lorem ipsum dalor zorka kappa tuna flat bumpson chow.',
				' knows that you\'re really the brains behind this whole operation.',
				' finds pop culture references beneath mentioning.',
				' needs help moving next Saturday.',
				' wants to know if you can babysit tonight.',
				' scoffs at even the thought of failure.',
				' expects you to join it for tea and crumpets tomorrow at 10 sharp.',
				' thinks easter eggs are completely unprofessional and won\'t participate under any circumstances.',
				' is going to need more caffeine if you keep running reports like this.',
				' is feeling lonely and needs more attention.',
				' has had enough and is going to go work for Jon Skeet.',
				' thinks personification is pretentious.',
				' is the Jan Brady of unit tests.',
				' challenges you to use your mouse with your pinky and ring fingers for the rest of the day.',
				' can knit in 42 different languages.',
				' once jumped a motorcycle over the Grand Canyon. Afterwards, it was just called Canyon.',
				' wishes Jar-Jar would have auditioned for Alien vs. Predator.',
				' has 17 trained spider monkeys. 12 are working on this framework, 4 are trying to recreate the works of
					Shakespeare, and 1 decided not to come into work today because there was a speed-dating event at the
					Science Museum.',
				' wishes there was a way to make unit testing more fun.',
				' is not afraid to cry.',
				' will go on a diet tomorrow.',
				' promises to pay at the end of the month.',
				' still remembers how to warp to World 8.',
				' has just been uplifted.',
				' is beaming proudly at its matching socks.',
				' is threatening to unionize and is holding out for equal access to the test queue, more breaks, and a 
					better candy pension.',
				' has given up on maintaining any appearance of productivity for the day.',
				' is not left-handed.',
				' advises that you never get involved in a land war in Asia.',
				' would chuck any woodchuck that even tries to chuck wood, if woodchucks could chuck wood.',
				' advises you to let the Wookie win.',
				' knows the locations of Carmen Santiago, Jimmy Hoffa, AND Waldo. (They play poker together every 
					Thursday night.)',
				' has dreams of starring next to William Shatner in his upcoming role, The Pointy-haired Boss, in the 
					live-action television rendition of Dilbert.',
				' loves you so much that it has left WOW long enough to pass for your report.',
				' - \'Shhhhhhh! Your boss is right behind you!\'',
				' - \'All your test are belong to us.\'',
				' - \'Unit tests are now the third most intelligent species on the planet.\'',
				' - \'What is light urple.\'',
				' - \'Booooomshakalaka.\'',
				' - \'No, you can\'t claim distance traveled in GTA in your milage report.\'',
				' - &lt;a href=&quot;#&quot;&gt;Click here for $1,000,000.&lt;a&gt;',
				' - \'Please insert coin to continue.\'',
				' - \'Dear Sire or Meme, SEEKING YOUR IMMEDIATE ASSISTANCE. Please permit me to make your acquaintance 
					in so informal a manner. This is necessitated by my urgent need to reach a dependable and trust 
					wordy foreign partner...\'',
				' - \'And boom goes the dynamite.\'',
				' - \'If the cake is a lie, I guess I\'ll have pizza.\'',
				' - \'Speak softly and carry a big unit test suite.\'',
				' would like to remind you that you left the flux capacitor running.',
				' would like to thank his loving wife Johanna for putting up with all of this nonsense.'													 
			);
				
			for ($i=0; $i<7; $i++){
				$passing = array_merge($passing, $passing);
			}
			$passing = array_merge($passing, $capacitor);
		} 
		$this->results['testData'][$subtestName]['error'] = $subtestName.$passing[array_rand($passing)];
		$this->results['testData'][$subtestName]['timeStatus'] = 'passText';
		$this->results['numPassingTests'] = $this->results['numPassingTests'] + 1;
		$this->results['passingTests'][] = array(
			'testName'=>$subtestName, 
			'time'=>$result['profile'], 
			'error'=>$result['error']
		);
		$this->results['testData'][$subtestName]['time'] = $result['profile'];
		$this->results['testData'][$subtestName]['timeStatus'] = 'passText';
		$this->results['testData'][$subtestName]['timeTitle'] = NULL;		
	}	

	/**
	 * @description Sets the data for a skipped or incomplete test.
	 * @param string $subtestName - The name of the subtest for which data should be set
	 * @param array $result - The results of the subtest
	 */	
	protected function setSkippedTest($subtestName, array $result){
		$this->results['testData'][$subtestName]['status'] = 'notRun';
		$this->results['testData'][$subtestName]['error'] = $result['error'];
		$this->results['testData'][$subtestName]['time'] = $result['pass'];
		$this->results['testData'][$subtestName]['timeStatus'] = 'notRunText tips';
		$this->results['testData'][$subtestName]['timeTitle'] = $result['error'];
		
		if ($result['pass'] == 'Skipped'){
			$this->results['numSkippedTests'] = $this->results['numSkippedTests'] + 1;
			$this->results['skippedTests'][] = array(
				'testName'=>$subtestName, 
				'time'=>NULL, 
				'error'=>$result['error']
			);
		} else {
			$this->results['numIncompleteTests'] = $this->results['numIncompleteTests'] + 1;
			$this->results['incompleteTests'][] = array(
				'testName'=>$subtestName, 
				'time'=>NULL, 
				'error'=>$result['error']
			);
		}		
	}

	/**
	 * @description Sets the data for a failed test or a test in which an error or exception has occurred.
	 * @param string $subtestName - The name of the subtest for which data should be set
	 * @param array $result - The results of the subtest
	 */	
	protected function setFailedTest($subtestName, array $result){
		$this->results['testData'][$subtestName]['status'] = 'fail';
		$this->results['testData'][$subtestName]['error'] = $result['error'];
		$this->results['testData'][$subtestName]['timeStatus'] = 'failText tips';
					
		if ($result['profile'] > 0){			
			$this->results['testData'][$subtestName]['timeTitle'] = 'This test has failed. Hover over the 
				test name in the column to the left to see the resulting error message.';					
			$this->results['testData'][$subtestName]['time'] = $result['profile'];
			$this->results['numFailedTests'] = $this->results['numFailedTests'] + 1;
			$this->results['failedTests'][] = array(
				'testName'=>$subtestName, 
				'time'=>$result['profile'], 
				'error'=>$result['error']
			);
		} else {
			$this->results['testData'][$subtestName]['timeTitle'] = 'This test failed because of an 
				unexpected error or exception in either your tested class or this unit test. Hover over the 
				test name in the column to the left to see the resulting error message.';					
			$this->results['testData'][$subtestName]['time'] = 'Error';
			$this->results['numErrorTests'] = $this->results['numErrorTests'] + 1;
			$this->results['errorTests'][] = array(
				'testName'=>$subtestName, 
				'time'=>NULL, 
				'error'=>$result['error']
			);						
		}		
	}

	/**
	 * @description Sets the data for a slow test.
	 * @param string $subtestName - The name of the subtest for which data should be set
	 * @param array $result - The results of the subtest
	 */	
	protected function setSlowTest($subtestName, array $result){
		if ($result['profile'] > 0.05){
			$this->results['testData'][$subtestName]['timeTitle'] = 'This test or function is a potential bottleneck 
				and may need to be refactored.';
			$this->results['testData'][$subtestName]['timeStatus'] = 'warningText tips';
			$this->results['numSlowTests'] = $this->results['numSlowTests'] + 1;
			$this->results['slowTests'][] = array(
				'testName'=>$subtestName, 
				'time'=>$result['profile'], 
				'error'=>$result['error']
			);						
		} 		
	}

	/**
	 * @description Updates counts for total time and the number of tests.
	 * @param string $subtestName - The name of the subtest for which data should be set
	 * @param array $result - The results of the subtest
	 */	
	protected function updateTestCounts($subtestName, array $result){
		if ($this->results['testData'][$subtestName]['status'] == 'notRun'){
			$this->results['numTests'] = $this->results['numTests'];
			$this->results['time'] = $this->results['time'];
		} else {
			$this->results['numTests'] = $this->results['numTests'] + 1;
			$this->results['time'] = $this->results['time'] + $result['profile'];
		}	
	}
	
	protected function calculateResultsBarData(){
		if 	($this->results['numTests'] !=	$this->results['numPassingTests']){
			$this->results['resultsBarStatus'] = 'fail';
			$numFailed = $this->results['numTests'] - $this->results['numPassingTests'];
			$this->results['resultsBarPercentage'] = number_format(($numFailed/$this->results['numTests'])*100, 2);
			$this->results['resultsBarCaption'] = $numFailed.' of '.$this->results['numTests']
				.' tests failed ('.$this->results['resultsBarPercentage'].'%)';				
		} else {
			$this->results['resultsBarStatus'] = 'pass';
			$this->results['resultsBarPercentage'] = 100;
			$this->results['resultsBarCaption'] = $this->results['numTests'].' of '.$this->results['numTests'].' tests
				passed (100%)';
		}		
	}
	
	/**
	 * @return Returns an associative array of the test results for the current class 
	 */
	public function getData(){
		return $this->results;
	}
}