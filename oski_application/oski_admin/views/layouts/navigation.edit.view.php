<?php if(isset($this -> result) && count($this -> result)) { getTPart("article", "updateResults", "admin"); announce($this -> result['what'], $this -> result['title'], $this -> result['content']) ?>
<?php getTPart("article", "", "admin"); } ?>

<?php getTPart("article", "navelem", "admin"); getTPart("title"); echo "Edit the navigation menu named : " . ucwords(deext(end(explode("/", $this -> file)))); getTPart("title"); getTPart("content", "navcontent"); 
$i = 0; ?>
<div class='left'><form method="POST" action="<?php actp('complete') ?>" id='saveform'> <input type='submit' value='<?php __e("Update") ?>'> </form></div>
<?php foreach ($this -> nav -> element as $elem) : $i++; getTPart("article", "navelem accordion", "admin", 1); getTPart("content", "small navcontent accordion", NULL, 1);?> 

<div class='ot'><div class='input'><input type="text" name="link" id="link" class="link" value="<?php echo $elem -> link ?>"></div></div>
<div class='ot'><div class='input'><input type="text" name="name" id="name" class="name" value="<?php echo $elem -> name ?>"></div></div>
<div class='clear'></div>
<?php if(count($elem -> element)) $this -> recGetSubNav($elem, $i, 0); ?>

<?php getTPart("content", "", NULL); getTPart("article", "", "admin"); endforeach ?>

<div class='copyelem'>
<?php getTPart("article", "navelem accordion", "admin", 1); getTPart("content", "small navcontent accordion", NULL, 1); getTPart("content", "", NULL); getTPart("article", "", "admin"); ?>
</div>
 
<?php getTPart("content"); getTPart("article", "", "admin"); ?>

<?php getTPart("article","navelem","admin");getTPart("title");
echo __("Sugestions");
getTPart("title");
getTPart("content");
$this -> recPrintFS(PAGESDIR.Oski::app() -> instance);
getTPart("content");
getTPart("article","","admin");
?>