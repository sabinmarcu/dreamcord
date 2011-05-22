<?php if ($this -> remove) {?>
<?php getTPart("article", "", "admin") ?>
<?php getTPart("title") ?>
	<?php __e("Remove a domain from the database") ?>
<?php getTPart("title") ?>
<?php getTPart("content") ?>
<div class='hlf left'>
<?php
	$form = new formBuilder(actp('complete'));
	$form -> addInput("remove", "", "", "", "hidden", "input", "domain");
	$form -> addSubDetail("value", __("Delete only the domain added to the configuration")." &rarr;");
	$form -> printForm();
?>
</div>
<div class='hlf right'>
<?php
	$form -> resetData(actp('complete'));
	$form -> addInput("remove", "", "", "", "hidden", "input", "config");
	$form -> addSubDetail("value", __("Delete this domain, the configuration and all of the other domains using this configuration")." &rarr;");
	$form -> printForm();
?>
</div>
<?php getTPart("content") ?>
<p class='help'>You can either remove the domain from the list, and thus disableing it to use a specific configuration, or delete the configuration along side it.</p>
<p class="help"><strong><?php __e("NOTE") ?> *</strong> This configuration profile may be used for other sites as well.</p>
<p class="help"><strong><?php __e("NOTE") ?> *</strong> Removing this domain will remove all other websites created with this one. </p>
<?php getTPart("article", "", "admin") ?>
<?php } else ?>
<?php var_DUMP($_POST); ?>