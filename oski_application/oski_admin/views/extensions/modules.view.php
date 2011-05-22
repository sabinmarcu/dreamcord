<?php 
	getTPart("article", "", "admin"); getTPart("title"); __e("Module List"); getTPart("title"); getTPart("content");
	for($i = 0; $i <= count($this -> mod -> module) - 1; $i++) { 
		$conf = $this -> mod -> module[$i];	
		echo "<li><a href='/admin/extensions/modules/", $conf -> id, "'>", $conf -> name, "</a><span class='right'><a href='?removeModule=", $conf -> id, "'>", __("Delete"), "</a></li>";
	}
	getTPart("content"); getTPart("article", "", "admin"); 
?>