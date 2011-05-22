<div class="header">
	<a href="/">
		<h1 class="site title"><?php getSiteTitle() ?></h1>
		<h4 class="site tagline"><?php getSiteTagline() ?></h4>
	</a>
</div>
<div class="section main">
	<div class="edge top"></div>
	<div class="edge left"></div>
	<div class="edge right"></div>
	<div class='section content noJS<?php if (actp('module') == "admin") echo " admin" ?>'>
	<div class="navigation main">
		<?php getNavigation() ?>
	</div>
	<div class="sidebar left">
		<div class="colors">
			<?php $colors = explode(",", str_replace(" ", "", Oski::app() -> theme -> themeExtras['colors'])); foreach($colors as $id => $color) : ?>
				<a href="?color_scheme=<?php echo $color ?>&set_permanent=1"><div class="color" id='<?php echo $color ?>'></div></a>
			<?php endforeach; ?>
		</div>
		<div class="widget">
			<div class="sec navigation"><?php if (actp('module') == "admin") getAdminNav(); else { $lay = new nav_layout("secnav"); $lay -> getNav(); } ?></div>
		</div>
		<div class="widget">
			<?php getAdmin() ?>
		</div>
	</div>
	<div class="main content">
		