<?php getTPart('admin-article'); getTPart("title"); ?>
<a href="<?php echo $this -> back ?>" id='back'><?php		echo "Reading : ".$this -> url;		?></a>
<span class='right'> <form method="POST", action='<?php echo actp('complete') ?>' id='deleteform'><input type='submit' value='<?php __e("Delete") ?>'><input type='hidden' name='delete' value='1'></form> </span>
<?php getTPart("title"); getTPart('content');  ?>
<form action='<?php urlHelper::linkTo("/admin/pages".$this -> url) ?>' method='POST' id='saveform'>
	<div class='tt left'>
	<div class='input'>
		<textarea name='content' style='min-height: 550px' tabindex=1><?php echo $this -> content ?></textarea>
	</div>
	</div>
	<div class='ot right'>
		<?php if (ext($this -> file) == "md") : ?>
			<h3><?php __e("MARKDOWN SYNTHAX") ?></h3>
			<hr>
			<h4>Headings</h4>
			<p>
				# Header 1 # <br>
				## Header 2 ## <br>
				### Header 3 ### <br>
				#### Header 4 <br>
				##### Header 5 <br>
				###### Header 6 <br>
			</p>
			<h4>Emphasis</h4>
			<p>
				*italic* or _italic_ <br>
				**bold** or __bold__ <br>
			</p>
			<h4>Images</h4>
			<p>
				![alt text](/path/to/image/jpg "title")
			</p>
			<h4>Blockquotes</h4>
			<p>
				> An Example of a Blockquote
			</p>
			<h6>Automatic Links</h6>
			<p>
				&lt;http://www.example.com> will turn into : &lt;a href="http://www.example.com">http://www.example.com/</a>
			</p>
			<h6>Lists</h6>
			<p>
				* This is a list
				* New list item
			</p>
			<h6>Links</h6>
			<p>
				[text to be shown](http://www.url.com/ "title")
			</p>
		<?php else : ?>
			<h4><?php __e("HTML SYNTHAX") ?></h4>
		<?php endif; ?>
	</div>
	<input type='submit' value='<?php echo __("Continue") ?> &rArr;'>
</form>
<?php getTPart('content'); getTPart('admin-article'); $this -> getNotes(); ?>