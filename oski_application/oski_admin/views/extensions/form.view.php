<?php 
getTPart("article", "", "admin"); getTPart("title"); echo "Install a new extensions"; getTPart("title"); getTPart("content");
	$form = new formBuilder("admin/extensions", "POST", "", 1);
	$form -> addInput("oextfile", "", "", "", "file");
	$form -> addLabel("oextfile", __("The .oext file you want to install"));
	$form -> printForm();
getTPart("content"); getTPart("article", "", "admin");
?>