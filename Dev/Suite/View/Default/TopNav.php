<nav id="navContainer">
	<ul id="topNav">
		<?php foreach ($this->menu as $item): ?>
			<li><a<?php echo $item['href'].$item['id'].$item['class']; ?>><?php echo $item['page'];?></a></li>
		<?php endforeach; ?>		
	</ul>
</nav>