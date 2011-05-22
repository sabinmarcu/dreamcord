<?php
if (count($this -> cache) % 2 != 0) $stop = count($this -> cache) - 1;
else $stop = -1; $i = 0;
foreach($this -> cache as $ch)	:
	$file = new ini_reader(COMPDIR.$ch -> name."/config.ini");
	$file = $file -> toDict();
	if ($i == $stop) getTPart("article", "", "admin");
	else getTPart('sidebox');
		getTPart('title');	echo $file['name']; echo "<span class=\"right\"><form action=\"". linkTo('/admin/extensions/components/') ."\" method=\"post\" accept-charset=\"utf-8\"><input type=\"hidden\" name=\"delete\" value=\"".$ch -> name."\" /><input type=\"submit\" value=\"".__("Delete")."\"></form></span>"; getTPart('title');
		getTPart('content'); 
?>
	<form action="<?php echo linkTo('/admin/extensions/components/') ?>" method="post" accept-charset="utf-8">
	<div class="compdescription">
		<?php echo (isset($file['description']) ? $file['description'] : "There is no description defined for this component.") ?>
	</div>
	<div class="compactive">
			<label for="active"><?php __e("Activate") ?></label>
			<input type="radio" name="active" id="active" value='1' <?php if(intval($ch -> activated)) echo "checked" ?> />
			<label for="inactive"><?php __e("Deactivate") ?></label>
			<input type="radio" name="active" id="inactive" value='0' <?php if(!intval($ch -> activated)) echo "checked" ?> />
			<input type="hidden" name="component" value='<?php echo $ch -> name ?>' />
			<input type="submit" value="<?php __e("Continue") ?> &rarr;" class='left full'>
	</div>
	<div class="compauthor">
		<?php echo (isset($file['author']) ? $file['author'] : "Anonymous") ?>
	</div>
	</form>
<?php
		getTPart('content');
	if ($i == $stop) getTPart("article", "", "admin");
	else getTPart('sidebox');
	$i++;
endforeach;


?>
<style type="text/css">
	div.compdescription{
		width: 70%;
		display: inline-block;
		float: left;
		font-size: 10pt;
	}
	div.compactive{
		width:20%;
		padding: 0 2%;
		float: right;
		display: inline-block;
	}
	div.compactive label{
		float: left;
		clear: left;
		padding: 3px;
		margin: 0;
	}
	div.compactive input[type='radio']{
		display: inline-block;
		float: right;
		clear: right;
		padding: 3px;
	}
	div.compactive input[type='submit']{
		font-size: 12pt;
		color: inherit;
	}
	div.compauthor{
		float:left;
		clear:left;
		color: #f22;
		margin: 15px 0 0;
	}
</style>