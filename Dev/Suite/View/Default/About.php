<div class="contentContainer firstContentContainer">
	<ul id="aboutTab" class="tabMenu firstTabMenu">
		<li><a class="firstTab tab active" href="#about">About</a></li>
		<li><a class="tab" href="#updates">Updates</a></li>
		<li><a class="tab" href="#news">News</a></li>
		<li><a class="tab" href="#support">Support</a></li>
		<li><a class="tab" href="#contribute">Contribute</a></li>
	</ul>
	<div class="tabContent">
		<div id="about">
			<img id="medLogo" src="<?php echo $this->logoUrl;?>" />
			<h3 class="title">About VM PHP Framework</h3>
			<p>VM PHP Framework is an OOP framework built for use with PHP 5. It was designed by <a href="http://www.virtuosimedia.com/">Virtuosi Media Inc.</a> to have a dead-simple, easy-to-use API while still providing most of the functionality you'll find in other PHP frameworks.</p>
			<p>Use the installer to get started on building your application with VM PHP Framework. You can view the API docs, write additional unit tests for your app, or use the tools tab to generate your database files.</p>
			<p>VM Framework is open source and licensed under the <a href="http://www.opensource.org/licenses/mit-license.php">MIT License</a>. That means you can use it however you want just so long as you keep the license and copyright notice intact.</p>
		</div>
		<div id="updates">
			<h3 class="title">VM PHP Framework Updates</h3>
			<p>Eventually, this tab will contain updates to VM PHP Framework and will allow the user to download and install those updates.</p>
		</div>
		<div id="news">
			<h3 class="title">VM PHP Framework News</h3>
			<p>Here are the latest news items from the VM PHP Framework blog.</p>
			<ul>
			<?php foreach ($this->news as $item):?>
				<li><a href="<?php echo $item['link']; ?>"><?php echo $item['title']; ?></a></li>
			<?php endforeach; ?>
			</ul>
		</div>
		<div id="support">
			<h3 class="title">VM PHP Framework Support</h3>
			<p>Need help implementing VM PHP Framework in your project? There are several options available:</p>
			<ul>
				<li><strong>Extensive Documentation</strong> - Check out the documentation included in the VM PHP Framework Development Suite. We've made
				an effort to provide useful information because we believe the development process should be as smooth as possible. Each class in the 
				framework will have both API Documentation as well as a tutorial to show you when, how, and why the class should be used. If the in-Suite
				documentation isn't enough for you, be sure to check out the VM PHP Framework online docs, which also include tips from the community.</li>
				<li><strong>Community Support</strong> - Contribute to and benefit from our open community forums. VM PHP Framework is an open-source
				project and volunteers are welcome. You can help out in the forums, write tutorials or documentation, contribute tools, submit bug
				reports or patches.</li>
				<li><strong>Commercial Support</strong> - Virtuosi Media, the company behind VM PHP Framework offers commercial support packages including
				prioritized email and forum support, consulting, and on-site training.</li>
			</ul>
		</div>
		<div id="contribute">
			<h3 class="title">How To Contribute To VM PHP Framework</h3>
			<p>VM PHP Framework is open-source and we value the contributions of our community.</p>
		</div>			
	</div>
</div>