<?php $this->textdomain ('catlab.accounts'); ?>

<p class="navbar-text navbar-right">
	<?php echo sprintf ($this->gettext ('Welcome, %s.'), $user->getDisplayName ()); ?>
	<a href="<?php echo $logout; ?>"><?php echo $this->getText ('logout'); ?></a>
</p>
