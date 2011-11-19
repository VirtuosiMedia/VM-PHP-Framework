<div class="contentContainer firstContentContainer">
	<ul id="docsTab" class="tabMenu firstTabMenu">
		<?php foreach ($this->tabs as $tab):?>
			<li><a class="<?php echo $tab['class']; ?>" href="<?php echo $tab['hash']; ?>"><?php echo $tab['name']?></a></li>
		<?php endforeach;?>
	</ul>
	<div class="tabContent">
		<?php foreach($this->apps as $app):?>
			<div id="<?php echo $app;?>">
				<?php $appTutorials = $app.'Tutorials';?>
				<?php if ($this->$appTutorials):?>
					<h3 class="title"><?php echo $app;?> Tutorials</h3>
					<p>Learn how to use <?php echo $app;?> including setup, conventions, and best practices.</p>
					<?php foreach ($this->$appTutorials as $column):?>
						<ul class="column">
						<?php foreach ($column as $tutorial):?>
							<li><a href="<?php echo $tutorial['url']; ?>"><?php echo $tutorial['name']?></a></li>	
						<?php endforeach;?>
						</ul>
					<?php endforeach;?>	
				<?php endif;?>
				<?php if (is_array($this->$app)):?>	
					<h3 class="title"><?php echo $app;?> Class Files</h3>
					<p>Each of the class files will contain API docs, a usage tutorial, and the file source code.</p>
					<?php foreach ($this->$app as $column):?>
						<ul class="column">
						<?php foreach ($column as $doc):?>
							<li><a href="<?php echo $doc['url']; ?>"><?php echo $doc['name']?></a></li>	
						<?php endforeach;?>
						</ul>
					<?php endforeach;?>
				<?php else:?>
					<h3 class="title"><?php echo $app;?> Class Files</h3>
					<p>No class files could be found for <?php echo $app;?>.</p>					
				<?php endif;?>		
			</div>	
		<?php endforeach;?>
		<div id="vm">
			<?php if ($this->VmTutorials):?>
				<h3 class="title">VM PHP Framework Tutorials</h3>
				<p>Learn how to use VM PHP Framework including setup, conventions, and best practices.</p>
				<?php foreach ($this->VmTutorials as $column):?>
					<ul class="column">
					<?php foreach ($column as $tutorial):?>
						<li><a href="<?php echo $tutorial['url']; ?>"><?php echo $tutorial['name']?></a></li>	
					<?php endforeach;?>
					</ul>
				<?php endforeach;?>	
			<?php endif;?>	
			<h3 class="title">VM PHP Framework Class Files</h3>
			<p>Each of the class files will contain API docs, a usage tutorial, and the file source code.</p>
			<?php foreach ($this->Vm as $column):?>
				<ul class="column">
				<?php foreach ($column as $doc):?>
					<li><a href="<?php echo $doc['url']; ?>"><?php echo $doc['name']?></a></li>	
				<?php endforeach;?>
				</ul>
			<?php endforeach;?>		
		</div>
		<div id="suite">
			<h3 class="title">Development Suite Documentation</h3>
			<p>Use the following tutorials to learn how you can best utilize the VM PHP Framework Development Suite to your advantage.</p>
			<?php foreach ($this->suite as $column):?>
				<ul class="column">
				<?php foreach ($column as $tutorial):?>
					<li><a href="<?php echo $tutorial['url']; ?>"><?php echo $tutorial['name']?></a></li>	
				<?php endforeach;?>
				</ul>
			<?php endforeach;?>
		</div>	
		<div id="help">
			<h3 class="title">Docs Help</h3>
			<p>The VM PHP Framework Development Suite automatically generates API documentation for you and you can add your own tutorials
			for any class.</p>
		</div>
	</div>
</div>