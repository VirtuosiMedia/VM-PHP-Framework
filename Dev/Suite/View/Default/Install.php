<div class="contentContainer firstContentContainer">
	<ul id="installTab" class="tabMenu firstTabMenu">
		<li><a class="firstTab tab active" href="#environment">Environment</a></li>
		<li><a class="tab" href="#install">Install</a></li>
		<li><a class="tab" href="#help">Help</a></li>
	</ul>
	<div class="tabContent">
		<div id="environment">
			<h3 class="title">PHP Development Environment</h3>
			<p>In order to take advantage of all the features of both VM PHP Framework and the VM PHP Framework Development Suite, 
			please make sure that all of the following PHP extensions are installed and properly configured before using the 
			framework.</p>
			<table cellspacing="0" cellpadding="0" width="100%">
				<thead>
					<tr>
						<th class="tableTitle">PHP Environment Check</th>
					</tr>
				</thead>
				<tbody>
					<tr><td><span class="<?php echo $this->phpVersionClass; ?>"></span><?php echo $this->phpVersion; ?></td></tr>
					<tr><td><span class="<?php echo $this->gdLibraryClass; ?>"></span><?php echo $this->gdLibrary; ?></td></tr>
					<tr><td><span class="<?php echo $this->zlibClass; ?>"></span><?php echo $this->zlib; ?></td></tr>
					<tr><td><span class="<?php echo $this->pdoClass; ?>"></span><?php echo $this->pdo; ?></td></tr>
					<tr><td><span class="<?php echo $this->mysqlPdoClass; ?>"></span><?php echo $this->mysqlPdo; ?></td></tr>
					<tr><td><span class="<?php echo $this->ctypeClass; ?>"></span><?php echo $this->ctype; ?></td></tr>
					<tr><td><span class="<?php echo $this->reflectionClass; ?>"></span><?php echo $this->reflection; ?></td></tr>
					<tr><td><span class="<?php echo $this->xdebugClass; ?>"></span><?php echo $this->xdebug; ?></td></tr>
				</tbody>
			</table>
		</div>
		<div id="install">
			<h3 class="title">Install Your App Skeleton</h3>
			<p>While you have successfully installed VM PHP Framework, you should install an app skeleton to accelerate your development 
			process. VM PHP Framework will automatically generate a basic app skeleton that includes a bootstrap file, a configuration file,
			a basic MVC setup, a connection to the database, and a few other utility files to get you started.</p>
			<p>After installation, additional tools will be made available in the Tools tab. Of course, you can modify any of the files for your own 
			purposes and you're welcome to skip installation if you need to setup your app manually.</p>
			<?php echo $this->form; ?>
		</div>
		<div id="help">
			<h3 class="title">Installation Help</h3>
			<p>VM PHP Framework and the VM PHP Framework Development Suite rely heavily on the conditions listed on the <i>Environment</i> tab. To
			ensure that your development process goes as smoothly as possible, please install and enable all of the listed extensions and settings before
			you begin to use the framework.</p>
			<p>Once you have installed an app skeleton, the install page will no longer be available in your menu. If you need to access the environment
			check at a later time, you can also find it in the <i>Tools</i> page.</p>
			<h4>Recommended Resources</h4>
			<ul>
				<li><a href="http://php.net/manual/en/ini.core.php">Description of core php.ini directives</a></li>
				<li><a href="http://xdebug.org/docs/install">XDebug Installation</a></li>
			</ul>		
		</div>	
	</div>
</div>
<p class="copyright">Version <?php echo $this->version; ?> - <?php echo $this->copyright; ?></p>