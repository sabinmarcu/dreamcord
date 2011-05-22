<?php Oski::app() -> theme -> getPart('sidebox'); ?>
<?php getTPart('title') ?> <?php __e("OSKI ENGINE Websites") ?> <?php getTPart('title') ?>
<?php getTPart('content') ?>	
	<?php $this -> listEngines($this -> engines); ?>
	<p class="help"><strong><?php __e("NOTE") ?> *</strong> <?php __e("You only have access to the sites built from this particular site.") ?></p>
<?php getTPart('content') ?>
<?php Oski::app() -> theme -> getPart('sidebox'); ?>

<?php Oski::app() -> theme -> getPart('sidebox'); ?>
<?php getTPart('title') ?> <?php __e("Create a new Website") ?><?php getTPart('title') ?> 
<?php getTPart('content') ?>
<?php 
$form = new formBuilder("admin/engine/add/"); 
	$form -> addInput("config", __("The configuration file it will use")); 
	$form -> addLabel("config", __("The configuration file it will use"));
	$form -> addInput("domain", __("The domain it will be redirected from")); 
	$form -> addLabel("domain", __("The domain it will be redirected from"));
$form -> printForm(); ?>
<?php getTPart('content') ?>
<?php Oski::app() -> theme -> getPart('sidebox'); ?>

<p class="help"><strong><?php __e("NOTE") ?> *</strong></p>
<p class="help"><?php __e("A single OSKI ENGINE application can be used to build more web sites, and those themselves can build other web sites, and so on.") ?></p>
<p class="help"><?php __e("Each 'parent' application can edit the contents of both itself and of its 'children'.") ?></p>