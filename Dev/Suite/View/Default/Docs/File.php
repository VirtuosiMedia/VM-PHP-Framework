<div class="contentContainer firstContentContainer">
	<ul id="docsTab" class="tabMenu firstTabMenu">
		<?php foreach ($this->tabs as $tab):?>
			<li><a class="<?php echo $tab['class']; ?>" href="<?php echo $tab['hash']; ?>"><?php echo $tab['name']?></a></li>
		<?php endforeach;?>
	</ul>
	<div class="tabContent">
		<?php if ($this->tutorial):?>
			<div id="tutorial">
			<?php echo $this->tutorial; ?>
			</div>
		<?php endif;?>
		<?php if ($this->api):?>
			<div id="api">
				<h1><?php echo $this->fileName;?> API Docs</h1>
				
				<!-- The class docs -->
				<?php if (sizeof($this->classDocs) > 0):?>
					<?php if (isset($this->classDocs['Description'])):?>
						<p><?php echo $this->classDocs['Description'];?></p>
					<?php endif;?>
					<table width="100%" cellpadding="0" cellspacing="0">
						<thead>
							<tr><th colspan="2" class="tableTitle">Class Data</th></tr>
							<tr><th>Property</th><th>Value</th></tr>
						</thead>
						<tbody>
							<?php foreach($this->classDocs as $property=>$values):?>
								<?php if (!in_array($property, array('Description', 'Example', 'Note'))):?>
									<?php foreach($values as $index=>$value):?>
										<tr><td><?php if ($index == 0) echo $property;?></td><td><?php echo $value;?></td></tr>
									<?php endforeach;?>
								<?php endif;?>
							<?php endforeach;?>					
						</tbody>
					</table>
					<?php if (isset($this->classDocs['Example'])):?>
						<?php echo $this->classDocs['Example'];?>
					<?php endif;?>
					<?php if (isset($this->classDocs['Note'])):?>
						<?php foreach($this->classDocs['Note'] as $note):?>
							<div class="note"><strong>Note</strong>: <?php echo $note;?></div>
						<?php endforeach;?>
					<?php endif;?>									
				<?php endif;?>
				
				<!-- Public Methods List -->
				<?php if (sizeof($this->publicMethods) > 0):?>
					<table width="100%" cellpadding="0" cellspacing="0">
						<thead>
							<tr><th colspan="2" class="tableTitle">Public Methods</th></tr>
							<tr><th>Method</th><th>Parameters</th></tr>
						</thead>
						<tbody>			
							<?php foreach($this->publicMethods as $method=>$params):?>
								<tr><td><a class="scroll" href="#<?php echo $method;?>"><?php echo $method;?></td><td><?php echo $params;?></td></tr>
							<?php endforeach;?>
						</tbody>
					</table>					
				<?php endif;?>
				
				<!-- Protected Methods List -->
				<?php if (sizeof($this->protectedMethods) > 0):?>
					<table width="100%" cellpadding="0" cellspacing="0">
						<thead>
							<tr><th colspan="2" class="tableTitle">Protected Methods</th></tr>
							<tr><th>Method</th><th>Parameters</th></tr>
						</thead>
						<tbody>			
							<?php foreach($this->protectedMethods as $method=>$params):?>
								<tr><td><a class="scroll" href="#<?php echo $method;?>"><?php echo $method;?></a></td><td><?php echo $params;?></td></tr>
							<?php endforeach;?>
						</tbody>
					</table>					
				<?php endif;?>	
				
				<!-- Methods -->
				<?php foreach($this->methodDocs as $name=>$method):?>
					<span id="<?php echo $name;?>" class="scrollPoint"></span>
					<span class="divider"></span>
					<a class="topLink tips" title="Return to the top of the page" href="#top"></a>
					<h3 class="title"><?php echo $name;?></h3>
					<p><?php echo $method['Description'];?></p>
	
					<!-- Method Data -->				
					<table width="100%" cellpadding="0" cellspacing="0">
						<thead>
							<tr><th colspan="2" class="tableTitle">Method Data</th></tr>
							<tr><th>Property</th><th>Value</th></tr>
						</thead>
						<tbody>
							<?php foreach($method['Data'] as $property=>$values):?>
								<?php if (!in_array($property, array('Description', 'Example', 'Note', 'Security'))):?>
									<?php foreach($values as $index=>$value):?>
										<tr><td><?php if ($index == 0) echo $property;?></td><td><?php echo $value;?></td></tr>
									<?php endforeach;?>
								<?php endif;?>
							<?php endforeach;?>					
						</tbody>
					</table>
					
					<!-- Method Params -->									
					<?php if (sizeof($method['Params']) > 0):?>
						<table width="100%" cellpadding="0" cellspacing="0">
							<thead>
								<tr><th colspan="4" class="tableTitle">Method Parameters</th></tr>
								<tr><th>Parameter</th><th>Type</th><th>Default</th><th>Description</th></tr>
							</thead>
							<tbody>			
								<?php foreach($method['Params'] as $param):?>
									<tr>
										<td><?php echo $param['name'];?></td>
										<td><?php echo $param['type'];?></td>
										<td><?php echo $param['default'];?></td>
										<td><span class="tableText"><?php echo $param['description'];?></span></td>
									</tr>
								<?php endforeach;?>
							</tbody>
						</table>					
					<?php endif;?>
					
					<h5>Returns</h5>
					<p><?php echo $method['Returns'];?></p>
					
					<?php if (isset($method['Data']['Example'])):?>
						<h5>Example Usage</h5>
						<?php echo $method['Data']['Example'];?>
					<?php endif;?>
					<?php if (isset($method['Data']['Note'])):?>
						<?php foreach($method['Data']['Note'] as $note):?>
							<div class="note"><span></span><p><?php echo $note;?></p></div>
						<?php endforeach;?>
					<?php endif;?>
					<?php if (isset($method['Data']['Security'])):?>
						<?php foreach($method['Data']['Security'] as $security):?>
							<div class="security"><span></span><p><?php echo $security;?></p></div>
						<?php endforeach;?>
					<?php endif;?>								
				<?php endforeach;?>		
			</div>
		<?php endif;?>
		<?php if ($this->code):?>
			<div id="source">
				<h1><?php echo $this->fileName;?> Source Code</h1>
				<pre id="phpCode" class="php"><?php echo $this->code; ?></pre>
			</div>
		<?php endif;?>			
	</div>
</div>
<p class="copyright">Version <?php echo $this->version; ?> - <?php echo $this->copyright; ?></p>