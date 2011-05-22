<?php 
	$form = new formbuilder(actp("complete"));
	getTPart("article","","admin");
	getTPart("title");echo "Edit Post";getTPart("title");
	getTPart("content");
	$form -> addinput("title","","","","text","input",$this -> post["title"]);
	$form -> addinput("excerpt","","","","text","textarea",$this -> post["excerpt"]);
	$form -> addinput("body","","","","text","textarea",$this -> post["body"]);
	$form -> addinput("date_added_t","","","","text","input",$this -> post["date_added_t"]);
	$form -> printform();
	getTPart("content");
	getTPart("article","","admin");
	
?>