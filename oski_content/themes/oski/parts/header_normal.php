<section id='menu'>
    <div class='container'>
    <div class='logo'></div>
    <ul>
        <li><a href='<?php echo linkToF('?mode=light&set_permanent=1')?>'>Light Interface</a></li>
        <li><a href='<?php echo linkToF('?mode=classic&set_permanent=1')?>'>Classic Interface</a></li>
    </ul>
    </div>
    <div class='text'> Oski Engine </div>
</section>
<section class="main" id='nav'>
<header>
    <div class='logo'></div>
    <nav>
	<div class='wrp'>
	    <a href='<?php echo linkTo() ?>'> <?php getTitle() ?> </a>
	    <div class='mainnav'><?php getNavigation("main") ?></div>
	</div>
    <div class='clear'></div>
    </nav>
    <div class='admin'><?php getAdmin() ?></div>
    <div class="secnav"><?php getNavigation("secnav") ?></div>
    <div class='clear'></div>
</header>
</section>