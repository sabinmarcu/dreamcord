
<div class='tplbase'>
<?php
foreach($this -> tpl as $tpl)	
	if (is_array($tpl))	{ 
		getTPart("article", "tplelem", "admin"); getTPart("content", "tplcontent"); ?> 
		<div class='ot'><div class='input'><input type='text' name='id' id='id' class='id' value="<?php echo $tpl['content'] ?>"></div></div>
		<br>
		<div class='ot'><div class='input'>
			<select name="container" id="container" class="container">
				<option value='article' <?php if (strpos($tpl['container'], "article") !== FALSE) echo "selected"?>>Article</option>
				<option value='sidebox left' <?php if (strpos($tpl['container'], "sidebox left") !== FALSE) echo "selected"?>>Sidebox Left</option>
				<option value='sidebox right' <?php if (strpos($tpl['container'], "sidebox right") !== FALSE) echo "selected"?>>Sidebox Right</option>
			</select>
		</div></div>
		<br>
		<div class='ot'><div class='input'>
			<select name='type' id='type' class='type'>
				<option value="layout" <?php if ($tpl['type'] == "layout") echo "selected" ?>>Page Template</option>
				<option value="page" <?php if ($tpl['type'] == "page") echo "selected" ?>>Static Page</option>
				<option value="module" <?php if ($tpl['type'] == "module") echo "selected" ?>>Installed Module Instance</option>
				<option value="current_module" <?php if ($tpl['type'] == "current_module") echo "selected" ?>>The current module in use</option>
			</select>
		</div></div>
<?php getTPart("content"); getTPart("article", "", "admin");
}
?>
<div class="copyelem">
<?php getTPart("article", "tplelem", "admin"); getTPart("content", "tplcontent"); ?> 
		<div class='ot clear'><div class='input'><input type='text' name='id' id='id' class='id' value="<?php echo $tpl['content'] ?>"></div></div>
		<br>
		<div class='ot clear'><div class='input'>
			<select name="container" id="container" class="container">
				<option value='article'>Article</option>
				<option value='sidebox left'>Sidebox Left</option>
				<option value='sidebox right'>Sidebox Right</option>
			</select>
		</div></div>
		<br>
		<div class='ot clear'><div class='input'>
			<select name='type' id='type' class='type'>
				<option value="layout">Page Template</option>
				<option value="page">Static Page</option>
				<option value="module">Installed Module Instance</option>
				<option value="current_module">The current module in use</option>
			</select>
		</div></div>
<?php getTPart("content"); getTPart("article", "", "admin"); ?>
</div></div>