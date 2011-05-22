<?php
if (isset($this -> noLog)) { announce("error", __("Houston, we have a problem !", array("There is no log with the name <".actp('action').">"))); return false; } 
getTPart("article", "", "admin");
	getTPart("title"); echo "Listing the log file : " , $this -> logname; getTPart("title");
	getTPart("content"); echo nl2br($this -> logcontent); getTPart("content");
getTPart("article", "", "admin");
?>
