<div id="topNavWrapper">
	<nav id="topNavContainer">
		<ul id="topNav">
			<li><a id="logo" href="install.php"></a></li>
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
				<li class="<?php echo $this->environmentClass;?>">Environment Check</li>
				<li class="<?php echo $this->databaseClass;?>">Install Database</li>
				<li class="<?php echo $this->adminClass;?>">Create Admin User</li>
				<li class="<?php echo $this->appClass;?>">Enter App Data</li>
			</ul>	
		</div>
	</div>
	<div class="contentContainer firstContentContainer">
		<div class="titleBar">
			<h3>Database Information</h3>
		</div>		
		<div class="content">
			<div id="environment">
				<p>The VM PHP Framework Development Suite requires a database. Please create a database and enter the
					connection information below.</p>
				<?php echo $this->databaseForm;?>
			</div>
			<div id="help">
				<h3 id="helpTitle" class="title">Installation Help</h3>
				<p>The VM PHP Framework Development Suite uses a database to keep track of information related to the
					development of your application. This database should be used only for application development and
					not for the application itself. You'll be able to create the application database at a later time in
					the "Tools" section.</p>
				<p>VM PHP Framework supports multiple database types, so you can choose from any of the RDBMS's listed
					in the "Database Type" selection field. For any questions or issues related to creating a database,
					please consult your database vendor's documentation.</p>		
			</div>	
		</div>
	</div>
</div>