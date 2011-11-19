<div class="contentContainer firstContentContainer">
	<ul id="installTab" class="tabMenu firstTabMenu">
		<li><a class="firstTab tab active" href="#settings">Settings</a></li>
		<li><a class="tab" href="#permissions">Permissions</a></li>
		<li><a class="tab" href="#help">Help</a></li>
	</ul>
	<div class="tabContent">
		<div id="settings">
			<h3 class="title">PHP Security Settings</h3>
			<p>VM Framework automatically checks your server settings for some common security issues.  In some use 
			cases, the recommended settings below may not be applicable.</p>
			<table cellspacing="0" cellpadding="0" width="100%">
				<thead>
					<tr>
						<th class="tableTitle">Recommended Security Settings</th>
					</tr>
				</thead>
				<tbody>
					<tr><td>
						<span class="<?php echo $this->magicQuotesClass; ?>"></span><?php echo $this->magicQuotes; ?>
					</td></tr>
					<tr><td>
						<span class="<?php echo $this->displayErrorsClass; ?>"></span><?php echo $this->displayErrors; ?>
					</td></tr>
					<tr><td>
						<span class="<?php echo $this->allowUrlIncludeClass; ?>"></span><?php echo $this->allowUrlInclude; ?>
					</td></tr>
					<tr><td>
						<span class="<?php echo $this->allowUrlFopenClass; ?>"></span><?php echo $this->allowUrlFopen; ?>
					</td></tr>
					<tr><td>
						<span class="<?php echo $this->registerGlobalsClass; ?>"></span><?php echo $this->registerGlobals; ?>
					</td></tr>
					<tr><td>
						<span class="<?php echo $this->exposePhpClass; ?>"></span><?php echo $this->exposePhp; ?>
					</td></tr>
				</tbody>
			</table>
		</div>
		<div id="permissions">
			<h3 class="title">Folder Permissions</h3>
			<p>VM Framework automatically checks your folder settings for exposed permissions.</p>
			<p><strong>Note</strong>: If you are on a Windows server, VM Framework will not be able to automatically 
			set the folder or file permissions. By default, they will be 0777.</p>
			<table cellspacing="0" cellpadding="0" width="100%">
				<thead>
					<tr>
						<th class="tableTitle" colspan="2">Recommended Folder Permissions</th>
					</tr>				
					<tr>
						<th class="testTitle">Folder</th>
						<th class="testTitle">Mode</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->folders as $folder):?>
					<tr>
						<td><span class="<?php echo $folder['class']; ?>"></span><?php echo $folder['name']; ?></td>
						<td><?php echo $folder['permissions']; ?></td>
					</tr>
					<?php endforeach;?>			
				</tbody>
			</table>				
		</div>
		<div id="help">
			<h3 class="title">Security Help</h3>
			<p>For security purposes, <strong>do not include</strong> the <i>Dev</i> folder containing the VM PHP 
			Framework Development Suite on a production	server.</p> 
			<p>Note that VM PHP Framework's security testing is far from comprehensive and even if your application 
			passes all security tests, it does not mean that your application or your server is 100% secure. The 
			security testing is meant as a convenience for your development, but you should perform additional testing 
			on your	own. Virtuosi Media Inc. is not responsible	any security failures.</p>
			<h4>Recommended Resources</h4>
			<ul>
				<li><a href="https://www.owasp.org">Open Web Application Security Project (OWASP)</a></li>
				<li><a href="http://security.stackexchange.com/">IT Security on Stack Exchange</a></li>
			</ul>
		</div>	
	</div>
</div>