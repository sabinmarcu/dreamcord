<div class="sidebar">
	<div class='sidebar-content'>
	<div class="nav main"><?php getNavigation() ?></div>
	<a href="/">
		<div class="logo">CNNG</div>
		<div class="text">Colegiul National</div>
		<div class="text">Nicolae Grigorescu</div>
	</a>
	<div class="navigation"><?php $nav = new nav_layout("secnav"); $nav -> getNav(); ?></div>
	<div class="credits">
		&copy; Marcu Sabin 2010-<?php echo date("Y", time()) ?>
		<?php echo __("Powered by"), " ", ucwords(APPNAME), " ", APPVER ?>
	</div>
	</div>
</div>
<div class="main-content">
	<div class="nav admin"><?php getAdmin() ?></div>
	<div class="shadow top"></div>
	<?php noCredits(); noTSelector() ?>