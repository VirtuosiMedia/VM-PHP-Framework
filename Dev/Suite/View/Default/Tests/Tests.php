<?php foreach ($this->tests as $name=>$test):?>
<div class="contentContainer">
	<div class="titleBar">
		<h3 class="title"><a class="classLink" href="<?php echo '#'.$name;?>"><?php echo $name;?></a></h3>
		<a href="#top" class="topLink tips" title="Return to the top of the page"></a>
	</div>
	<ul id="<?php echo $name;?>" class="tabBar">
		<li><a class="<?php echo $name;?>Tab firstTab active" href="#results-for-<?php echo $name;?>">Results</a></li>
		<?php if ($this->includeCoverage):?>
			<li><a class="<?php echo $name;?>Tab tab" href="#coverage-for-<?php echo $name;?>">Coverage</a></li>
		<?php endif;?>
		<?php if ($this->includeMetrics):?>
			<li><a class="<?php echo $name;?>Tab tab" href="#metrics-for-<?php echo $name;?>">Metrics</a></li>
		<?php endif;?>
		<li><a id="<?php echo $name;?>HistoryTab" class="<?php echo $name;?>Tab tab" href="#history-for-<?php echo $name;?>">History</a></li>
	</ul>
	<div class="testContent">
			<div id="results-for-<?php echo $name;?>">
				<div class="infoContainer">
					<div class="meterContainer">
						<span class="<?php echo $test['resultsBarStatus'];?>" style="width:<?php echo $test['resultsBarPercentage'];?>%;"></span>
					</div>
					<span class="meterCaption"><?php echo $test['resultsBarCaption'];?></span>
					<ul>
						<li><strong>Group</strong>: <?php echo $test['group'];?></li>
						<li><strong>SubGroup</strong>: <?php echo $test['subgroup'];?></li>
						<li><strong>Test Author</strong>: <?php echo $test['author'];?></li>
						<li><strong>Description</strong>: <?php echo $test['description'];?></li>
					</ul>
				</div>
				<div class="resultsTableContainer">
					<?php if (sizeof($test['testData']) > 0):?>
						<table class="testTable" cellspacing="0" cellpadding="0" width="100%">
							<thead>
								<tr>
									<th>Test Name</th>
									<th>Time/Status</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($test['testData'] as $testName=>$data):?>
									<tr>
										<td class="tips" title="<?php echo $data['error'];?>">
											<span class="<?php echo $data['status'];?>"></span><?php echo $testName;?>
										</td>
										<td class="<?php echo $data['timeStatus'];?>" title="<?php echo $data['timeTitle'];?>">
											<span><?php echo $data['time'];?></span>
										</td>
									</tr>
								<?php endforeach;?>
								<tr>
									<td><strong>Total Time</strong></td>
									<td><strong><?php echo $test['time'];?></strong></td>
								</tr>
							</tbody>					
						</table>
					<?php else:?>
						<p class="failText">No tests could be found for this test case.</p>
					<?php endif;?>
				</div>
			</div>
			<?php if ($this->includeCoverage):?>
				<div id="coverage-for-<?php echo $name;?>">
					<div class="infoContainer">
						<div class="meterContainer tips" title="<?php echo $test['functionalBarTitle'];?>">
							<span class="<?php echo $test['functionalBarStatus'];?>" style="width:<?php echo $test['functionalBarPercentage'];?>%;"></span>
						</div>
						<span class="meterCaption"><?php echo $test['functionalBarCaption'];?></span>
						<div class="meterContainer tips" title="<?php echo $test['statementBarTitle'];?>">
							<span class="<?php echo $test['statementBarStatus'];?>" style="width:<?php echo $test['statementBarPercentage'];?>%;"></span>
						</div>
						<span class="meterCaption"><?php echo $test['statementBarCaption'];?></span>					
					</div>
					<div class="resultsTableContainer">
						<p>Note that even 100% coverage does not necessarily mean that the tested class is thoroughly 
						tested, it only means that each line of code is executed at least once. The coverage 
						illustration below is only meant as a starting point for your test coverage. For true quality 
						test coverage, test each possible state of your classes.</p>
					</div>
					<h3 class="title">Source Code Coverage</h3>
					<ul class="codeCoverage">
						<?php foreach ($test['codeCoverage'] as $line=>$data):?>
							<li class="<?php echo $data['type'];?>">
								<span class="lineNumber"><?php echo $line;?></span>
								<span class="execute <?php if (isset($data['title'])){ echo 'tips';}?>" title="<?php echo $data['title'];?>"><?php echo $data['executions'];?></span>
								<span class="code"><?php echo $data['code'];?></span>
							</li>
						<?php endforeach;?>
					</ul>
				</div>
			<?php endif;?>
			<?php if ($this->includeMetrics):?>
				<div id="metrics-for-<?php echo $name;?>">
					<div class="infoContainer">
						<div class="complexity tips" title="<?php echo $test['complexityTitle'];?>">
							<span class="<?php echo $test['complexityStatus'];?>"><?php echo $test['avgComplexity'];?></span>
						</div>
						<span class="meterCaption tips" title="<?php echo $test['complexityTitle'];?>">Average Method Complexity</span>
						<div class="meterContainer tips" title="<?php echo $test['refactorTitle'];?>">
							<span class="<?php echo $test['refactorStatus'];?>" style="width:<?php echo $test['refactorPercentage'];?>%;"><?php echo $test['refactorMeterText'];?></span>
						</div>
						<span class="meterCaption tips" title="<?php echo $test['refactorTitle'];?>"><?php echo $test['refactorCaption'];?></span>
						<div class="meterContainer tips" title="<?php echo $test['readabilityTitle'];?>">
							<span class="<?php echo $test['readabilityStatus'];?>" style="width:<?php echo $test['readabilityPercentage'];?>%;"></span>
						</div>
						<span class="meterCaption tips" title="<?php echo $test['readabilityTitle'];?>"><?php echo $test['readabilityCaption'];?></span>
						<ul>
							<li class="tips" title="A list of the classes extended by <?php echo $name;?>, in order of inheritance."><strong>Extends</strong>: 
								<?php if (sizeof($test['parents']) == 0):?>
									 None
								<?php else:?>
									<ul>
										<?php foreach($test['parents'] as $parent):?>
											<li><?php echo $parent;?></li>
										<?php endforeach;?>
									</ul>
								<?php endif;?>
							</li>
							<li class="tips" title="A list of the classes used or called internally by <?php echo $name;?>. This does not include parent classes."><strong>Uses</strong>: 
								<?php if (sizeof($test['uses']) == 0):?>
									 None
								<?php else:?>
									<ul>
										<?php foreach($test['uses'] as $uses):?>
											<li><?php echo $uses;?></li>
										<?php endforeach;?>
									</ul>
								<?php endif;?>
							</li>							
							<li><strong>Total Lines Of Code</strong>: <?php echo $test['totalLoc'];?></li>
							<li><strong>Number of Methods</strong>: <?php echo $test['numMethods'];?></li>
							<li><a href="index.php?p=docs&amp;f=<?php echo $name;?>">View Class Documentation</a></li>
						</ul>																							
					</div>
					<div class="resultsTableContainer">
						<h4 class="tips" title="Lines of Code Analysis">LOC Analysis</h4>
						<div id="<?php echo $name;?>LocHolder" title="Lines of Code Analysis, by percentage" class="locHolder tips">
							<table id="<?php echo $name;?>Loc" class="testTable locChart" cellspacing="0" cellpadding="0" width="100%">
								<thead>
									<tr>
										<?php if ($test['executableLoc'] > 0):?>
											<th>Executable Code</th>
										<?php endif;?>
										<?php if ($test['commentsLoc'] > 0):?>
											<th>Comments</th>
										<?php endif;?>
										<?php if ($test['whitespaceLoc'] > 0):?>										
											<th>Whitespace</th>
										<?php endif;?>
									</tr>
								</thead>
								<tbody>
									<tr>
										<?php if ($test['executableLoc'] > 0):?>
											<td><?php echo $test['executableLoc'];?></td>
										<?php endif;?>
										<?php if ($test['commentsLoc'] > 0):?>
											<td><?php echo $test['commentsLoc'];?></td>
										<?php endif;?>
										<?php if ($test['whitespaceLoc'] > 0):?>										
											<td><?php echo $test['whitespaceLoc'];?></td>
										<?php endif;?>											
									</tr>
								</tbody>
							</table>
						</div>
						<h4>Method Data</h4>
						<?php if ($test['numMethods'] > 0):?>
							<table class="testTable" cellspacing="0" cellpadding="0" width="100%">
								<thead>
									<tr>
										<th>Method Name</th>
										<th class="tips" title="Method Cyclomatic Complexity">Complexity</th>
										<th class="tips" title="Method Refactor Probability">Refactor %</th>
										<th class="tips" title="Lines of Code">LOC</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($test['methods'] as $method=>$data):?>
										<tr>
											<td class="tips" title="<?php echo $data['data'];?>"><?php echo $method;?></td>
											<td class="<?php echo $data['complexityStatus'];?>" title="<?php echo $data['complexityTitle'];?>"><?php echo $data['complexity'];?></td>
											<td class="<?php echo $data['refactorStatus'];?>" title="<?php echo $data['refactorTitle'];?>"><?php echo $data['refactorPercentage'];?>%</td>
											<td class="<?php echo $data['locStatus'];?>" title="<?php echo $data['locTitle'];?>"><?php echo $data['loc'];?></td>
										</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						<?php else:?>
							<p>This class did not contain any methods of its own, but it may extend another class.</p>
						<?php endif;?>						
					</div>					
				</div>
			<?php endif;?>
			<?php if ($test['historyExists']):?>
				<div id="history-for-<?php echo $name;?>" class="historyContainer">
					<noscript><p class="fail"><strong>History is not viewable with JavaScript disabled.</strong></p></noscript>
					<div class="chartsInfoContainer">
						<ul id="<?php echo $name;?>ChartTabs" class="chartTabs">
							<li><a href="<?php echo '#'.$name;?>TestChartSlide" class="<?php echo $name;?>ChartTab active">Unit Tests</a></li>
							<li><a href="<?php echo '#'.$name;?>CoverageChartSlide" class="<?php echo $name;?>ChartTab">Coverage</a></li>
							<li><a href="<?php echo '#'.$name;?>ComplexityChartSlide" class="<?php echo $name;?>ChartTab">Complexity</a></li>
							<li><a href="<?php echo '#'.$name;?>RefactorChartSlide" class="<?php echo $name;?>ChartTab">Cleanup</a></li>
							<li><a href="<?php echo '#'.$name;?>LocChartSlide" class="<?php echo $name;?>ChartTab">LOC</a></li>
						</ul>
					</div>
					<div class="chartsContainer">
						<div id="<?php echo $name;?>TestChartSlide">
							<div id="<?php echo $name;?>TestChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="<?php echo $name;?>TestChart" class="lines"></div>
							</div>
							<p>The number of tests should increase over time until each possible state of <?php echo $name;?> has 
								been tested.</p>
							<div id="<?php echo $name;?>TestChartData" style="display:none;"><?php echo $test['historyTests'];?></div>
						</div>
						<div id="<?php echo $name;?>CoverageChartSlide">
							<div id="<?php echo $name;?>CoverageChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="<?php echo $name;?>CoverageChart" class="lines"></div>
							</div>
							<p>Functional coverage tests that each method in your class is called while statement coverage tests 
								that each line of executable code is run. You should try to achieve 100% coverage for both.</p>
							<div id="<?php echo $name;?>CoverageChartData" style="display:none;"><?php echo $test['historyCoverage'];?></div>
						</div>
						<div id="<?php echo $name;?>ComplexityChartSlide">
							<div id="<?php echo $name;?>ComplexityChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="<?php echo $name;?>ComplexityChart" class="lines"></div>
							</div>
							<p>Cyclomatic Complexity measures how complex a given piece of code is based on the number of decision 
								points it contains. This chart gives the average complexity rating for all of <?php echo $name;?>'s 
								methods. Lower numbers are good, above 8 should be looked at, and over 16 should be refactored.</p>
							<div id="<?php echo $name;?>ComplexityChartData" style="display:none;"><?php echo $test['historyComplexity'];?></div>
						</div>
						<div id="<?php echo $name;?>RefactorChartSlide">						
							<div id="<?php echo $name;?>RefactorChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="<?php echo $name;?>RefactorChart" class="lines"></div>
							</div>
							<p>The Refactor Probability attempts gauge if a class needs to be refactored based on its complexity, 
								readability, and length. Readability simply measures how easily a class can be understood based on 
								its complexity and the amount of commenting.</p>
							<div id="<?php echo $name;?>RefactorChartData" style="display:none;"><?php echo $test['historyRefactor'];?></div>
						</div>
						<div id="<?php echo $name;?>LocChartSlide">										
							<div id="<?php echo $name;?>LocChartContainer" class="tips" title="Click on the chart for a new chart type.">
								<div id="<?php echo $name;?>LocChart" class="lines"></div>
							</div>
							<p>This chart simply tracks the number of total lines of code in <?php echo $name;?> over time, 
								including comments and whitespace.</p>
							<div id="<?php echo $name;?>LocChartData" style="display:none;"><?php echo $test['historyLoc'];?></div>
						</div>
					</div>
				</div>
			<?php else:?>
				<div id="history-for-<?php echo $name;?>" class="historyContainer empty">
					<h3 class="title">No History Found</h3>
					<p>History for this test class is not available. Please rerun the test suite and select the 'Save 
						Results' checkbox to begin tracking the results of this test class over time.</p>
				</div>
			<?php endif;?>			
		</div>
	</div>
</div>
<?php endforeach;?>