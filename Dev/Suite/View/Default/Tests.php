<div class="contentContainer firstContentContainer">
	<ul id="suiteControls" class="tabMenu firstTabMenu">
		<li><a class="suiteControlsTab firstTab tab active" href="#reportGenerator">Reports</a></li>
		<li><a class="suiteControlsTab tab" href="#help">Help</a></li>
	</ul>
	<div class="testContent">
		<div id="reportGenerator">
			<h3 class="title">VM PHP Framework Test Suite</h3>
			<?php echo $this->testForm;?>
		</div>
		<div id="help">
			<h3 class="title">VM PHP Framework Test Suite Help</h3>
			<p>VM PHP Framework allows you to run reports for your unit tests. It also provides test coverage analysis 
				and code metrics statistics. By default, the testing suite tests VM PHP Framework files, but once you 
				install the framework, you can also use it to begin testing your own code.</p>
			<p>Please read the following tutorials to learn how to best use the testing suite:</p>
			<ul>
				<li><a href="#">How to use the testing suite</a></li>
				<li><a href="index.php?p=docs">How to write unit tests for your own code</a></li>
			</ul>
		</div>		
	</div>
</div>