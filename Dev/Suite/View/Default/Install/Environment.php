<div id="topNavWrapper">
	<nav id="topNavContainer">
		<ul id="topNav">
			<li><a id="logo" href="index.php"></a></li>
			<li>VM PHP Framework Installation</li>
			<li><a id="helpLink" href="#help">(Help)</a></li>
		</ul>
	</nav>
</div>
<div id="contentWrapper" class="sidebarContent">
	<div class="sidebar firstSidebar">
		<div class="titleBar">
			<h3>Installation</h3>
		</div>
		<div class="content">
			<ul class="counter">
				<li><a href="#" class="<?php echo $this->environmentClass;?>">Environment Check</a></li>
				<li><a href="#" class="<?php echo $this->databaseClass;?>">Install Database</a></li>
				<li><a href="#" class="<?php echo $this->adminClass;?>">Create Admin User</a></li>
				<li><a href="#"class="<?php echo $this->appClass;?>">Enter App Data</a></li>
			</ul>	
		</div>
	</div>
	<div class="contentContainer firstContentContainer">
		<div class="titleBar">
			<h3>PHP Development Environment</h3>
		</div>		
		<div class="content">
			<div id="environment">
				<p>In order to take advantage of all the features of both VM PHP Framework and the VM PHP Framework 
				Development Suite, please make sure that all of the following PHP extensions are installed and properly 
				configured before using the framework.</p>
				<table cellspacing="0" cellpadding="0" width="100%">
					<thead>
						<tr>
							<th class="tableTitle">PHP Environment Check</th>
						</tr>
					</thead>
					<tbody>
						<tr><td>
							<span class="<?php echo $this->phpVersionClass; ?>"></span><?php echo $this->phpVersion; ?>
						</td></tr>
						<tr><td>
							<span class="<?php echo $this->gdLibraryClass; ?>"></span><?php echo $this->gdLibrary; ?>
						</td></tr>
						<tr><td>
							<span class="<?php echo $this->zlibClass; ?>"></span><?php echo $this->zlib; ?>
						</td></tr>
						<tr><td>
							<span class="<?php echo $this->pdoClass; ?>"></span><?php echo $this->pdo; ?>
						</td></tr>
						<tr><td>
							<span class="<?php echo $this->mysqlPdoClass; ?>"></span><?php echo $this->mysqlPdo; ?>
						</td></tr>
						<tr><td>
							<span class="<?php echo $this->ctypeClass; ?>"></span><?php echo $this->ctype; ?>
						</td></tr>
						<tr><td>
							<span class="<?php echo $this->reflectionClass; ?>"></span><?php echo $this->reflection; ?>
						</td></tr>
						<tr><td>
							<span class="<?php echo $this->xdebugClass; ?>"></span><?php echo $this->xdebug; ?>
						</td></tr>
					</tbody>
				</table>
				<?php if ($this->conditions):?>
					<form method="get">
						<input type="hidden" name="p" value="install-database"/>
						<input type="submit" value="Next" class="primary button"/>
					</form> 
				<?php else:?>
					<form method="get">
						<input id="environmentFail" type="submit" value="Next" class="disabled button"/>
					</form> 
				<?php endif;?>
			</div>
			<div id="help">
				<h3 id="helpTitle" class="title">Installation Help</h3>
				<p>VM PHP Framework and the VM PHP Framework Development Suite rely heavily on the conditions listed in 
				the PHP Environment Check table. To ensure that your development process goes as smoothly as possible, 
				please install and enable all of the listed extensions and settings before you begin to use the 
				framework. With the exception of Xdebug, most of the extensions are probably already installed by 
				default.</p>
				<h4>Recommended Resources</h4>
				<ul>
					<li><a href="http://php.net/manual/en/ini.core.php">Description of core php.ini directives</a></li>
					<li><a href="http://xdebug.org/docs/install">XDebug Installation</a></li>
				</ul>		
			</div>	
		</div>
	</div>
</div>