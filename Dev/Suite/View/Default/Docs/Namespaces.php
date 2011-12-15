<div class="contentContainer firstContentContainer">
	<?php if ($this->breadcrumbExists):?>
		<ul id="breadcrumb">
			<li class="breadcrumbLi"><a href="index.php?p=docs">Docs</a></li>
			<?php foreach ($this->breadcrumbLinks as $name=>$link):?>
				<li class="breadcrumbLi"><a href="<?php echo $link;?>"><?php echo $name; ?></a></li>
			<?php endforeach;?>
			<li><?php echo $this->breadcrumbTitle;?></li>
		</ul>
	<?php endif;?>
	<div class="breadcrumbContent">
		<h1 class="title"><?php echo $this->namespaceName;?></h1>
		<?php if ($this->namespaceExists):?>
			<p>The following classes and sub-namespaces were found for the <?php echo $this->namespaceName;?> namespace.</p>
			<div class="column">
				<?php if (sizeof($this->classes) > 0):?>
					<h3>Classes</h3>
					<ul>
						<?php foreach ($this->classes as $class):?>
							<li><a href="index.php?p=docs&amp;f=<?php echo $class;?>"><?php echo $class;?></a></li>
						<?php endforeach;?>
					</ul>
				<?php endif;?>
			</div>
			<div class="column">
				<h3>Sub-namespaces</h3>
				<?php if (sizeof($this->subnamespaces) > 0):?>	
					<ul>
						<?php foreach ($this->subnamespaces as $sub):?>
							<li><a href="index.php?p=docs&amp;n=<?php echo $sub;?>"><?php echo $sub;?></a></li>
						<?php endforeach;?>
					</ul>
				<?php else:?>
					<p>No sub-namespaces were found for this namespace.</p>
				<?php endif;?>	
			</div>
		</div>
	<?php else:?>
		<p>No classes or sub-namespaces were found for the <?php echo $this->namespaceName;?> namespace.</p>
	<?php endif;?>
</div>