
<span class='left'><a href="<?php echo linkTo("/admin/userpanel/edit/") ?>" class='smallf'><?php __e("Edit My Profile") ?></a></span>
<h1><?php echo $this -> user['name'] ?>, <?php echo $this -> user['surname'] ?><span class='right'><em><?php echo $this -> user['username'] ?></em></span></h1>
<p><?php __e("E-Mail Address") ?> : <span class="right"> <a href='mailto:<?php echo $this -> user['email'] ?>' title="<?php __e("E-Mail Address") ?>"><?php echo current(explode("@", $this -> user['email'])), " ".__("at")." ", end(explode("@", substr($this -> user['email'], 0, strpos($this -> user['email'], ".")))), str_replace(".", " ".__("dot")." ", substr($this -> user['email'], strpos($this -> user['email'], "."))) ?></a></span></p>
<p><?php __e("Web Site") ?> : <span class="right"><a href="<?php echo $this -> user['website'] ?>" title = '<?php __e("Web Site") ?>'><?php echo current(explode(".", strtoupper(str_replace("http://", "", $this -> user['website'])))) ?></a></span></p>
<div class="textbox">
	<?php echo $this -> user['descr'] ?>
</div>
