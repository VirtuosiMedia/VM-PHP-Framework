<div class="contentContainer firstContentContainer">
	<div class="content">
		<?php if ($this->tutorial):?>
			<?php echo $this->tutorial; ?>
		<?php else:?>
			<h3 class="title">Tutorial Not Found</h3>
			<p>This tutorial could not be found. Please check that your URL is correct or return to the <i>Docs</i> 
			page.</p>
		<?php endif;?>
	</div>
</div>