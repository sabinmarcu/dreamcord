<?php 
foreach( $this -> posts as $post) :
$day = date("l", time($post['date_added_t'])); $month = date("F", time($post['date_added_t'])); 
getTPart("article", "", "admin"); 
	getTPart("title"); echo $post['title']; getTPart("title");
	getTPart("subtitle"); echo __($day), ", ", date("j", time($post['date_added_t'])), " ", __($month); getTPart("subtitle");
	getTPart("content"); echo htmlentities($post['excerpt']); getTPart("content");
getTPart("article", "", "admin");	
endforeach;
?>