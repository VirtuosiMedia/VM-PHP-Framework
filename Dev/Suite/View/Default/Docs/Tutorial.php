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
		<?php if ($this->tutorial):?>
			<?php echo $this->tutorial; ?>
		<?php else:?>
			<h3 class="title">Tutorial Not Found</h3>
			<p>This tutorial could not be found. Please check that your URL is correct or return to the <i>Docs</i> 
			page.</p>
		<?php endif;?>
	</div>
</div>