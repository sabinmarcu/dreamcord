<?php $filetype = array("html" => "(HTML - Hypertext Markup Language", "md" => "(MD - Markdown)");	getTPart("article", "", "admin"); getTPart("title");	?>
<a href="<?php echo linkTo($this -> back) ?>" id='back'><?php	echo "Reading : ".$this -> url; ?></a>
<?php if ($this -> url) : ?>
<div class='right'> <form method="POST" action='<?php echo actp('complete') ?>' id='deleteform'><input type='submit' value='<?php __e("Delete") ?>'><input type='hidden' name='delete' value='1'></form> </div>
<?php endif; getTPart("title");		getTPart("content");	?>
<?php if (count($this -> dirs)) : $i = 0; ?>
<ol class='a'>
	<?php foreach($this -> dirs as $dir) : $i++ ?>
	<li><strong class='bigfont'><a href="<?php echo linkTo(actp('complete').$dir) ?>" class='alphaLink' id = '<?php echo chr(97+$i-1) ?>'><?php echo ucwords($dir) ?></a><span class='right'>(<?php __e("Folder") ?>)</span></strong></li>
	<?php endforeach ?>
</ol>
<?php endif; if (count($this -> files)) : $i = 0; ?>
<ol class='nr'>
	<?php foreach($this -> files as $file) : $i++; $ext = ext($file); ?>
	<li><a href="<?php echo linkTo(actp('complete').$file) ?>" id='<?php echo $i ?>' class='numberLink'><?php echo ucwords(str_replace(".".$ext, "", $file)) ?></a><span class='right'><?php echo $filetype[$ext] ?></span></li>
	<?php endforeach ?>
</ol>
<?php
endif;
getTPart("content");
getTPart("article", "", "admin");
$this -> getNewFileForm();
$this -> getNewFolderForm();
$this -> getNotes();
?>