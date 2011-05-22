<?php getTPart("article", "", "admin"); getTPart("title"); echo __("Listing the Navigation Menus"); getTPart("title"); getTPart("content"); ?>
<dl>
<?php foreach ($this -> navs as $nav) : ?>
	<li>
		<a href='<?php echo linkTo(actp('complete').deext($nav)) ?>'><?php echo ucwords(deext($nav)) ?></a>
		<span class='right'><form method="POST" action="<?php echo linkTo(actp("complete")) ?>"><input type='submit' value='<?php echo __("Delete") ?>'><input type='hidden' name='removeNav' value='<?php echo $nav ?>'></form></span>
	</li>
	<div class='clear'></div>
<?php endforeach ?>
</dl>
<?php getTPart("content"); getTPart("article", "", "admin"); ?>