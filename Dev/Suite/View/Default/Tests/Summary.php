<div class="contentContainer">
	<div class="titleBar">
		<h3 class="title"><a class="classLink" href="#suiteOverview"><?php echo $this->reportName.' ';?>Report Summary</a></h3>
		<a href="#top" class="topLink tips" title="Return to the top of the page"></a>
	</div>
	<ul id="suiteOverview" class="tabBar">
		<li><a class="suiteOverviewTab firstTab active" href="#results-for-suiteOverview">Results</a></li>
		<?php if ($this->includeCoverage):?>
			<li><a class="suiteOverviewTab tab" href="#coverage-for-suiteOverview">Coverage</a></li>
		<?php endif;?>
		<?php if ($this->includeMetrics):?>
			<li><a class="suiteOverviewTab tab" href="#metrics-for-suiteOverview">Metrics</a></li>
		<?php endif;?>
		<li><a id="suiteOverviewHistoryTab" class="suiteOverviewTab tab" href="#history-for-suiteOverview">History</a></li>
	</ul>
	<div class="testContent">
			<div id="results-for-suiteOverview">
				<div class="infoContainer">
					<div class="meterContainer">
						<span class="<?php echo $this->resultsBarStatus;?>" style="width:<?php echo $this->resultsBarPercentage;?>%;"></span>
					</div>
					<span class="meterCaption"><?php echo $this->resultsBarCaption;?></span>
					<ul>
						<li><strong>Test Cases</strong>: <?php echo $this->numTestCases;?></li>
						<li><strong>Unit Tests/Test Case</strong>: <?php echo $this->avgNumUnitTests;?></li>
						<li><strong>Time/Unit Test</strong>: <?php echo $this->avgTestTime;?></li>
						<li><strong>Total Elapsed Time</strong>: <?php echo $this->totalTime;?></li>
					</ul>				
				</div>		
				<div class="resultsTableContainer">
					<table class="statsTable" cellspacing="0" cellpadding="0" width="100%">
						<thead><tr><th>Test Status</th><th>Count</th></tr></thead>
						<tbody>					
							<?php if ($this->numPassedTests > 0):?>
								<tr><td><span class="pass"></span>Passing</td><td><?php echo $this->numPassedTests;?></td></tr>
							<?php endif;?>
							<?php if ($this->numFailedTests > 0):?>
								<tr><td><span class="fail"></span>Failed</td><td><?php echo $this->numFailedTests;?></td></tr>
							<?php endif;?>
							<?php if ($this->numErrorTests > 0):?>
								<tr><td><span class="fail"></span>Errors</td><td><?php echo $this->numErrorTests;?></td></tr>
							<?php endif;?>
							<?php if ($this->numIncompleteTests > 0):?>
								<tr><td><span class="notRun"></span>Incomplete</td><td><?php echo $this->numIncompleteTests;?></td></tr>
							<?php endif;?>
							<?php if ($this->numSkippedTests > 0):?>
								<tr><td><span class="notRun"></span>Skipped</td><td><?php echo $this->numSkippedTests;?></td></tr>
							<?php endif;?>
							<?php if ($this->numSlowTests > 0):?>
								<tr><td><span class="warning"></span>Slow</td><td><?php echo $this->numSlowTests;?></td></tr>
							<?php endif;?>			
						</tbody>
					</table>
				</div>
				<?php if ($this->numNonPassing > 0):?>
					<h3 class="tableTitle">Notable Results</h3>
					<table class="testsTable" cellspacing="0" cellpadding="0" width="100%">
						<thead><tr><th>Class</th><th>Test</th><th>Time/Status</th></tr></thead>
						<tbody>
							<?php foreach($this->nonPassing as $name=>$test):?>
								<tr>
									<td>
										<span class="<?php echo $test['statusClass'];?>"></span>
										<a class="classLink tips" title="View this class" href="<?php echo '#'.str_replace('\\', '-', $test['className']);?>"><?php echo $test['className'];?></a>
									</td>
									<td<?php echo $test['testNameAttributes'];?>><?php echo $test['testName'];?></td>
									<td <?php echo $test['statusAttributes'];?>><?php echo $test['status'];?></td>
								</tr>
							<?php endforeach;?>
						</tbody>
					</table>
				<?php endif;?>
			</div>
			<?php if ($this->includeCoverage):?>
				<div id="coverage-for-suiteOverview">
					<div class="infoContainer">
						<div class="meterContainer tips" title="<?php echo $this->functionCoverageTitle;?>">
							<span class="<?php echo $this->functionCoverageStatus;?>" style="width:<?php echo $this->functionCoveragePercentage;?>%;"></span>
						</div>
						<span class="meterCaption tips" title="<?php echo $this->functionCoverageTitle;?>"><?php echo $this->functionCoverageCaption;?></span>
						<div class="meterContainer tips" title="<?php echo $this->statementCoverageTitle;?>">
							<span class="<?php echo $this->statementCoverageStatus;?>" style="width:<?php echo $this->statementCoveragePercentage;?>%;"></span>
						</div>
						<span class="meterCaption tips" title="<?php echo $this->statementCoverageTitle;?>"><?php echo $this->statementCoverageCaption;?></span>											
					</div>
					<div class="resultsTableContainer">
						<p>Note that even 100% coverage does not necessarily mean that the tested class is thoroughly 
						tested, it only means that each line of code is executed at least once. The coverage 
						illustration below is only meant as a starting point for your test coverage. For true quality 
						test coverage, test each possible state of your classes.</p>
					</div>
					<table class="testsTable" cellspacing="0" cellpadding="0" width="100%">
						<thead><tr><th>Class</th><th>Functional</th><th>Statement</th></tr></thead>
						<tbody>
							<?php foreach ($this->suiteCoverage as $name=>$data):?>
							<tr>
								<td>
									<a class="classLink tips" title="View this class" href="<?php echo '#'.str_replace('\\', '-', $name);?>"><?php echo $name;?></a>
								</td>
								<td>
									<div class="meterContainer tips" title="<?php echo $data['functional']['functionCoverageTitle'];?>">
										<span class="<?php echo $data['functional']['functionCoverageStatus'];?>" style="width:<?php echo $data['functional']['functionCoveragePercentage'];?>%;"><?php echo $data['functional']['functionMeterText'];?></span>
									</div>								
								</td>
								<td>
									<div class="meterContainer tips" title="<?php echo $data['statement']['statementCoverageTitle'];?>">
										<span class="<?php echo $data['statement']['statementCoverageStatus'];?>" style="width:<?php echo $data['statement']['statementCoveragePercentage'];?>%;"><?php echo $data['statement']['statementMeterText'];?></span>
									</div>								
								</td>
							</tr>
							<?php endforeach;?>
						</tbody>
					</table>					
				</div>
			<?php endif;?>
			<?php if ($this->includeMetrics):?>
				<div id="metrics-for-suiteOverview">
					<div class="infoContainer">
						<div class="complexity tips" title="<?php echo $this->complexityTitle;?>">
							<span class="<?php echo $this->complexityStatus;?>"><?php echo $this->avgComplexity;?></span>
						</div>
						<span class="meterCaption tips" title="<?php echo $this->complexityTitle;?>">Average Class Complexity</span>
						<div class="meterContainer tips" title="<?php echo $this->refactorTitle;?>">
							<span class="<?php echo $this->refactorStatus;?>" style="width:<?php echo $this->refactorPercentage;?>%;"><?php echo $this->refactorMeterText;?></span>
						</div>
						<span class="meterCaption tips" title="<?php echo $this->refactorTitle;?>"><?php echo $this->refactorCaption;?></span>
						<div class="meterContainer tips" title="<?php echo $this->readabilityTitle;?>">
							<span class="<?php echo $this->readabilityStatus;?>" style="width:<?php echo $this->readabilityPercentage;?>%;"></span>
						</div>
						<span class="meterCaption tips" title="<?php echo $this->readabilityTitle;?>"><?php echo $this->readabilityCaption;?></span>
						<ul>
							<li><strong>Total Lines Of Code</strong>: <?php echo $this->metricsLoc;?></li>
							<li><strong>Classes</strong>: <?php echo $this->numClasses;?></li>
							<li><strong>Methods/Class</strong>: <?php echo $this->avgNumMethods;?></li>
							<li><strong>LOC/Class</strong>: <?php echo $this->avgLocClass;?></li>
						</ul>																							
					</div>
					<div class="resultsTableContainer">
						<h4 class="tips" title="Lines of Code Analysis">LOC Analysis</h4>
						<div id="suiteOverviewLocHolder" title="Lines of Code Analysis, by percentage" class="locHolder tips">
							<table id="suiteOverviewLoc" class="testTable locChart" cellspacing="0" cellpadding="0" width="100%">
								<thead>
									<tr>
										<th>Executable Code</th>
										<th>Comments</th>
										<th>Whitespace</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?php echo $this->executableLoc;?></td>
										<td><?php echo $this->commentsLoc;?></td>
										<td><?php echo $this->whitespaceLoc;?></td>				
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<table class="testsTable" cellspacing="0" cellpadding="0" width="100%">
						<thead>
							<tr>
								<th>Class</th>
								<th>Complexity</th>
								<th>Refactor</th>
								<th>Readability</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->suiteMetrics as $name=>$data):?>
							<tr>
								<td>
									<a class="classLink tips" title="View this class" href="<?php echo '#'.str_replace('\\', '-', $name);?>"><?php echo $name;?></a>
								</td>
								<td>
									<div class="complexity tips" title="<?php echo $data['complexity']['complexityTitle'];?>">
										<span class="<?php echo $data['complexity']['complexityStatus'];?>"><?php echo $data['complexity']['avgComplexity'];?></span>
									</div>								
								</td>
								<td>
									<div class="meterContainer tips" title="<?php echo $data['refactor']['refactorTitle'];?>">
										<span class="<?php echo $data['refactor']['refactorStatus'];?>" style="width:<?php echo $data['refactor']['refactorPercentage'];?>%;"><?php echo $data['refactor']['refactorMeterText'];?></span>
									</div>								
								</td>
								<td>
									<div class="meterContainer tips" title="<?php echo $data['readability']['readabilityTitle'];?>">
										<span class="<?php echo $data['readability']['readabilityStatus'];?>" style="width:<?php echo $data['readability']['readabilityPercentage'];?>%;"></span>
									</div>								
								</td>
							</tr>
							<?php endforeach;?>						
						</tbody>
					</table>									
				</div>
			<?php endif;?>		
			<?php if ($this->historyExists):?>
				<div id="history-for-suiteOverview" class="historyContainer">
					<noscript><p class="fail"><strong>History is not viewable with JavaScript disabled.</strong></p></noscript>
					<div class="chartsInfoContainer">
						<ul id="suiteOverviewChartTabs" class="chartTabs">
							<li><a href="#suiteOverviewTestChartSlide" class="suiteOverviewChartTab active">Unit Tests</a></li>
							<li><a href="#suiteOverviewCoverageChartSlide" class="suiteOverviewChartTab">Coverage</a></li>
							<li><a href="#suiteOverviewComplexityChartSlide" class="suiteOverviewChartTab">Complexity</a></li>
							<li><a href="#suiteOverviewRefactorChartSlide" class="suiteOverviewChartTab">Cleanup</a></li>
							<li><a href="#suiteOverviewLocChartSlide" class="suiteOverviewChartTab">LOC</a></li>
						</ul>
					</div>
					<div class="chartsContainer">
						<div id="suiteOverviewTestChartSlide">
							<div id="suiteOverviewTestChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="suiteOverviewTestChart" class="lines"></div>
							</div>
							<p>The number of tests should increase over time until each possible state of each class has 
								been tested.</p>
							<div id="suiteOverviewTestChartData" style="display:none;"><?php echo $this->historyTests;?></div>
						</div>
						<div id="suiteOverviewCoverageChartSlide">
							<div id="suiteOverviewCoverageChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="suiteOverviewCoverageChart" class="lines"></div>
							</div>
							<p>Functional coverage tests that each method in your class is called while statement coverage tests 
								that each line of executable code is run. You should try to achieve 100% coverage for both.</p>
							<div id="suiteOverviewCoverageChartData" style="display:none;"><?php echo $this->historyCoverage;?></div>
						</div>
						<div id="suiteOverviewComplexityChartSlide">
							<div id="suiteOverviewComplexityChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="suiteOverviewComplexityChart" class="lines"></div>
							</div>
							<p>Cyclomatic Complexity measures how complex a given piece of code is based on the number of decision 
								points it contains. This chart gives the average complexity rating for all classes in the report. 
								Lower numbers are good, above 8 should be looked at, and over 16 should be refactored.</p>
							<div id="suiteOverviewComplexityChartData" style="display:none;"><?php echo $this->historyComplexity;?></div>
						</div>
						<div id="suiteOverviewRefactorChartSlide">						
							<div id="suiteOverviewRefactorChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="suiteOverviewRefactorChart" class="lines"></div>
							</div>
							<p>The Refactor Probability attempts gauge if a class needs to be refactored based on its complexity, 
								readability, and length. Readability simply measures how easily a class can be understood based on 
								its complexity and the amount of commenting.</p>
							<div id="suiteOverviewRefactorChartData" style="display:none;"><?php echo $this->historyRefactor;?></div>
						</div>
						<div id="suiteOverviewLocChartSlide">										
							<div id="suiteOverviewLocChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="suiteOverviewLocChart" class="lines"></div>
							</div>
							<p>This chart simply tracks the number of total lines of code in this report over time, 
								including comments and whitespace.</p>
							<div id="suiteOverviewLocChartData" style="display:none;"><?php echo $this->historyLoc;?></div>
						</div>
					</div>
				</div>				
			<?php else:?>
				<div id="history-for-suiteOverview"  class="historyContainer empty">
					<h3>No History Found</h3>
					<p>History for this report is not available. Please rerun the test suite and select the 
					'Save Results' checkbox to begin tracking the results of this report over time.</p>
				</div>
			<?php endif;?>
		</div>
	</div>
</div>