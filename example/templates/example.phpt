<?php $this->layout ('index.phpt'); ?>

		<h2>Example structure</h2>
		<p>
			This is just an example structure.
			We want to give you all the freedom you need.
			No rigid structure.
			Just a bunch of classes that you can instanciate in any way you like.
		</p>

		<h2>Configuration</h2>
		<p>Config example: <?php echo $title; ?>.</p>
		<ul>
			<?php foreach ($counts as $k => $v) { ?>
				<li><?php echo $k; ?>: <?php echo $v; ?></li>
			<?php } ?>
		</ul>

