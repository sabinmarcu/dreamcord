<?php 
	$form = new formbuilder(actp("complete"));
	getTPart("article","","admin");
	getTPart("title");echo "Edit Post";getTPart("title");
	getTPart("content");
	$form -> addinput("title","","","","text","input");
	$form -> addlabel("title",__("The Title of the Post"));
	$form -> addinput("excerpt","","","","text","textarea");
	$form -> addlabel("excerpt",__("The excerpt of the Post"));
	$form -> addinput("body","","","","text","textarea");
	$form -> addlabel("body","The body of the Post");
	$form -> addinput("date_added_t","","","","text","input");
	$form -> addlabel("date_added_t","Type a date for the post in format YYYY-MM-DD");
	$form -> addinput("permalink");
	$form -> addlabel("permalink", "The permalink of the Post");
	$form -> printform();
	getTPart("content");
	getTPart("article","","admin");
	
?>