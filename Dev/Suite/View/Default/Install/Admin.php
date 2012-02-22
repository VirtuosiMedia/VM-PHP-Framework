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
			<h3>Create Admin User</h3>
		</div>		
		<div class="content">
			<div id="environment">
				<p>Please enter user information for the initial admin account below. You'll be able to change these 
				settings and add additional admins and users once installation is complete.</p>
				<?php echo $this->adminForm;?>
			</div>
			<div id="help">
				<h3 id="helpTitle" class="title">Create Admin User Help</h3>
				<p>The VM PHP Framework Development Suite allows you to create users for each member of your development
					team, allowing for permissions-based access to tools, team communication, and audit trails of the
					development of your application. Even if you're developing on your own, you'll easily be able to add
					more users to your project as you grow.</p>
				<p>You'll be able to change your admin user settings once installation is complete by simply going to 
					your profile and editing your information. New users and user groups can be added by clicking on the
					'Users' menu item that will appear in the top navigation bar.</p>		
			</div>	
		</div>
	</div>
</div>