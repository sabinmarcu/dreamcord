<?php 
if (count($_FILES)) :
if ($this -> result['go']) announce("success", "Done!", array("It's done, your extension is installed."));
else announce("error", "Houston, we have a problem", array($this -> result['reason']));
else :
getTPart("article", "", "admin"); getTPart("title"); echo "Install a new extensions"; getTPart("title"); getTPart("content");
	$form = new formBuilder("admin/extensions/install", "POST", "", 1);
	$form -> addInput("oextfile", "", "", "", "file");
	$form -> addLabel("oextfile", __("The .oext file you want to install"));
	$form -> printForm();
getTPart("content"); getTPart("article", "", "admin");
endif;
?>