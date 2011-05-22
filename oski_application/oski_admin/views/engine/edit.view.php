
<?php if (isset($this -> edit)) : ?>
	<?php getTPart("article", "updateResults", "admin"); announce("success", __("Successfull Update !")); getTPart("article", "", "admin") ?>
<?php endif; ?>
<?php getTPart("article", "", "admin") ?>
	<?php getTPart("title") ?>	
		<?php __e("Edit an installation configuration : "); echo $this -> confign -> config; ?>
	<?php getTPart("title") ?>
	<?php getTPart("content") ?>	
	
	
	<?php getTPart("content") ?>
<?php getTPart("article", "", "admin") ?>
	
<?php getTPart("sidebox") ?>
	<?php getTPart("title") ?>	
		<?php __e("Edit the basic information of the Web Site : "); ?> (<?php __e("title") ?>, <?php __e("tagline") ?>)
	<?php getTPart("title") ?>
	<?php getTPart("content") ?>
	<?php 
		$form = new formBuilder(actp('complete'), "POST", "SITE_INFO");
		$form -> addInput("site_title", $this -> config['SITE_INFO']['site_title']);
		$form -> addLabel("site_title", __("The ").__("title").__(" of the Web Site"));
		$form -> addInput("site_tagline", $this -> config['SITE_INFO']['site_tagline']);
		$form -> addLabel("site_tagline", __("The ").__("tagline").__(" of the Web Site"));
		$form -> addInput("section", "", "", "", "hidden", "input", "SITE_INFO");
		$form -> printForm();	
	?>	
	<?php getTPart("content") ?>
<?php getTPart("sidebox") ?>

<?php getTPart("sidebox") ?>
	<?php getTPart("title") ?>	
		<?php __e("Edit the site-specific settings : "); ?> (<?php __e("language") ?>, <?php __e("charset") ?>)
	<?php getTPart("title") ?>
	<?php getTPart("content") ?>
	<?php 
		$form = new formBuilder(actp('complete'), "POST", "SITE_SETUP");
		$form -> addInput("language", $this -> config['SITE_SETUP']['language']);
		$form -> addLabel("language", __("The ").__("language").__(" of the Web Site"));
		$form -> addInput("charset", $this -> config['SITE_SETUP']['charset']);
		$form -> addLabel("charset", __("The ").__("charset").__(" of the Web Site"));
		$form -> addInput("section", "", "", "", "hidden", "input", "SITE_SETUP");
		$form -> printForm();	
	?>	
	<?php getTPart("content") ?>
<?php getTPart("sidebox") ?>

<?php getTPart("sidebox") ?>
	<?php getTPart("title") ?>	
		<?php __e("Edit the database settings : "); ?> (<?php __e("server") ?>, <?php __e("database") ?>, <?php __e("username") ?>)
	<?php getTPart("title") ?>
	<?php getTPart("content") ?>
	<?php 
		$form = new formBuilder(actp('complete'), "POST", "DB_DATA");
		$form -> addInput("table_prefix", $this -> config['DB_DATA']['database_prefix']);
		$form -> addLabel("table_prefix", __("The ").__("table prefix").__(" of the Database"));
		$form -> addInput("database_server", $this -> config['DB_DATA']['database_server']);
		$form -> addLabel("database_server", __("The ").__("server").__(" of the Database"));
		$form -> addInput("database_username", $this -> config['DB_DATA']['database_username']);
		$form -> addLabel("database_username", __("The ").__("username").__(" of the Database"));
		$form -> addInput("database_password", $this -> config['DB_DATA']['database_password']);
		$form -> addLabel("database_password", __("The ").__("password").__(" of the Database"));
		$form -> addInput("myDB_DATAbase", $this -> config['DB_DATA']['database_database']);
		$form -> addLabel("myDB_DATAbase", __("The ").__("actual database"));
		$form -> addInput("database_port", $this -> config['DB_DATA']['database_port']);
		$form -> addLabel("database_port", __("The ").__("port").__(" of the Database"));
		$form -> addInput("database_type", "", "db_mysql", "", "radio", "input", "database");
		$form -> addLabel("db_mysql", "<img src=\"/oski_includes/resources/images/mysqllogo.gif\" alt=\MySQL Database\" />", "left");
		$form -> addInput("database_type", "", "db_mongodb", "", "radio", "input", "mongodb");
		$form -> addLabel("db_mongodb", "<img src=\"/oski_includes/resources/images/mongodblogo.png\" alt=\MongoDB\" />", "left");
		$form -> addInput("section", "", "", "", "hidden", "input", "DB_DATA");
		$form -> printForm();	
	?>	
	<?php getTPart("content") ?>
<?php getTPart("sidebox") ?>

<?php getTPart("sidebox") ?>
	<?php getTPart("title") ?>	
		<?php __e("Edit the theming settings : "); ?> (<?php __e("default theme") ?>, <?php __e("error theme") ?>, <?php __e("login theme") ?>)
	<?php getTPart("title") ?>
	<?php getTPart("content") ?>
	<?php 
		$form = new formBuilder(actp('complete'), "POST", "THEME_SETUP");
		$themes = array();
		foreach($this -> themes -> all -> theme as $theme)	$themes[count($themes)] = (string) $theme[0];
		$themes['_selected'] = $this -> config['THEME_SETUP']['default_theme'];
		$form -> addInput("default_theme", "", "", "", "", "select", $themes);
		$form -> addLabel("default_theme", __("The  default theme"));
		$themes = array();
		foreach($this -> themes -> admin -> theme as $theme)	$themes[count($themes)] = (string) $theme[0];
		$themes['_selected'] = $this -> config['THEME_SETUP']['admin_theme'];
		$form -> addInput("admin_theme", "", "", "", "", "select", $themes);
		$form -> addLabel("admin_theme", __("The  administration theme (used for the admin panel)"));
		$themes = array();
		foreach($this -> themes -> error -> theme as $theme)	$themes[count($themes)] = (string) $theme[0];
		$themes['_selected'] = $this -> config['THEME_SETUP']['error_theme'];
		$form -> addInput("error_theme", "", "", "", "", "select", $themes);
		$form -> addLabel("error_theme", __("The  error theme (used when a serious error occurs)"));
		$themes = array();
		foreach($this -> themes -> all -> theme as $theme)	$themes[count($themes)] = (string) $theme[0];
		$themes['_selected'] = $this -> config['THEME_SETUP']['login_theme'];
		$form -> addInput("login_theme", "", "", "", "", "select", $themes);
		$form -> addLabel("login_theme", __("The  login theme (used in when logging in, logging out, or registering)"));
		$themes = array();
		foreach($this -> themes -> all -> theme as $theme)	$themes[count($themes)] = (string) $theme[0];
		$themes['_selected'] = $this -> config['THEME_SETUP']['user_theme'];
		$form -> addInput("user_theme", "", "", "", "", "select", $themes);
		$form -> addLabel("user_theme", __("The  user panel theme (used when a non-admin user logges in and desires to change his settings)"));
		$form -> addInput("section", "", "", "", "hidden", "input", "THEME_SETUP");
		$form -> printForm();	
	?>	
	<?php getTPart("content") ?>
<?php getTPart("sidebox") ?>