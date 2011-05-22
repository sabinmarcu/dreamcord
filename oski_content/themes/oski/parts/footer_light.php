<section id='menu'>
    <div class='logo'></div>
    <div class='topmenu'><?php getNavigation('secnav') ?></div>
    <div class='leftmenu'>
	<div class='top'><?php getNavigation('main') ?></div>
	<div class='bottom'><?php getAdmin() ?></div>
    </div>
    <div id='modes'>
	<ul>
	    <li><a href='<?php echo linkToF('?mode=light&set_permanent=1')?>'>Light Interface</a></li>
	    <li><a href='<?php echo linkToF('?mode=classic&set_permanent=1')?>'>Classic Interface</a></li>
        </ul>
    </div>
    <div class='text'> Oski Engine </div>
</section>