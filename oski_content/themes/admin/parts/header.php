		<div class='header'>
			<a href="/" class="site title"><?php getSiteTitle() ?></a>
			<div class="admin">
				<?php greetUserName() ?> (<a href="<?php echo linkTo("admin/userpanel/") ?>"><?php __e("<u>M</u>y Account") ?></a> | <a href="<?php echo linkTo("logout") ?>"><?php __e("<u>L</u>ogout</a>") ?></a>)
			</div>
		</div>
		<nav class='hor'>
			<?php getAdminNav(); ?>
		</nav>
			<div class='section' id='<?php echo actp('panel') ?>'>
