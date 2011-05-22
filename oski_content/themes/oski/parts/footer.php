</section></section>
<section class="main" id='footer'>
    <article>
	<div class="left"><?php getCredits() ?><br><?php getBTTLink() ?></div>
	<div class="right"><?php getThemeSelector() ?></div>
    </article>
</section>
<?php if (xcheck('mode', 'read', array($_GET, $_COOKIE))) include 'footer_read.php'; else if (xcheck('mode', 'light', array($_GET, $_COOKIE))) include 'footer_light.php'; ?>
</section>