<link rel="shortcut icon" href="<?php echo Oski::app()->baseURL?>/oski_includes/resources/favicons/<?php echo (file_exists ( FAVICONDIR . Oski::app()->instance . ".ico" ) ? Oski::app()->instance : "default")?>.ico" />
<meta charset="<?php echo $this->config ['SITE_SETUP'] ['charset']?>">
<meta name="title" content="<?php getSiteTitle ()?>">
<meta name="generator" 	content="<?php	echo APPNAME?> <?php	echo APPVER?>">
<meta HTTP-EQUIV="REFRESH" content="300">
<meta name="application-name" content="<?php getSiteTitle ()?>">
<!--[if gte IE 9]>
<meta name="msapplication-tooltip" content="<?php getSiteTagline ()?>" />
<?php if (isset ( $_COOKIE ['user_admin'] ) || isset ( $_COOKIE ['tuser_admin'] )) {	?>
<meta name="msapplication-task" content="name=<?php __e ( "Logout" )?>; action-uri=/logout; icon-uri=<?php echo Oski::app()->baseURL?>/oski_includes/resources/images/icons/logout.ico" />
<meta name="msapplication-task" content="name=<?php __e ( "User Panel" )?>; action-uri=/admin/userpanel; icon-uri=<?php echo Oski::app()->baseURL?>/oski_includes/resources/images/icons/user.ico" />
<?php if ((isset ( $_COOKIE ['user_admin'] ) ? $_COOKIE ['user_admin'] : $_COOKIE ['tuser_admin'])) { ?>
	<meta name="msapplication-task" content="name=<?php __e ( "Command Panel" )?>; action-uri=/admin; icon-uri=<?php echo Oski::app()->baseURL?>/oski_includes/resources/images/icons/admin.ico" />
<?php }	} else { ?>
	<meta name="msapplication-task" content="name=<?php __e ( "Login" )?>; action-uri=/login; icon-uri=<?php echo Oski::app()->baseURL?>/oski_includes/resources/images/icons/login.ico" />
	<meta name="msapplication-task" content="name=<?php __e ( "Register" )?>; action-uri=/register; icon-uri=<?php	echo Oski::app()->baseURL?>/oski_includes/resources/images/icons/register.ico" />
<?php }	?>
<script type="text/JavaScript">
    if (window.external.msIsSiteMode()) {
     window.external.msSiteModeCreateJumplist('Main Navigation');
     window.external.msSiteModeClearJumplist();
      	<?php	try {
	    $nav = new nav_layout ( "main" );
	    foreach ( $nav->config->element as $el ) : ?>
		window.external.msSiteModeAddJumpListItem('<?php echo $el->name?>', '/<?php echo $el->link?>', '<?php echo Oski::app()->baseURL?>/oski_includes/resources/images/icons/link.ico');
            <?php endforeach;	} catch ( Exception $e ) { } ?>
            	window.external.msSiteModeShowJumplist();
     }
   </script>
<![endif]-->
<title><?php echo self::getProp("info", "site_title"), " | ", self::getProp("header-title"); ?></title>
<?php if (! xcheck ( "quirks", "1", array ($_GET, $_POST ) )) : ?>
<link rel="stylesheet"	href="<?php	echo Oski::app()->baseURL?>/oski_includes/resources/css/global.css"type="text/css" media="screen" title="no title" charset="utf-8">
<script type="text/javascript"	src="<?php	echo Oski::app()->baseURL?>/oski_includes/resources/js/jquery.js"></script>
<script type="text/javascript"	src="<?php	echo Oski::app()->baseURL?>/oski_includes/resources/js/jquery.ui.js"></script>
<?php	if (actp ( 'module' ) == "admin") : ?>
<script type="text/javascript" src="<?php echo Oski::app()->baseURL?>/oski_includes/resources/js/jquery.hotkeys.js"></script>
<script type="text/javascript" src="<?php echo Oski::app()->baseURL?>/oski_includes/resources/js/jquery.livequery.js"></script>
<script type="text/javascript" src="<?php echo Oski::app()->baseURL?>/oski_includes/resources/js/admin.js"></script>
<?php endif; ?>
<script src="<?php echo Oski::app()->baseURL?>/oski_includes/resources/js/flowplayer-3.2.6.min.js" type="text/javascript" charset="utf-8"></script>
 <?php 	if ($this->theme->has_font) $this->theme->getFont (); ?>
 <?php	if ($this->theme->has_css) $this->theme->getCSS (); ?>
 <?php 	if ($this->theme->has_js) $this->theme->getJS (); ?>
<?php endif; ?>
</head>
<body
id='<?php if (isset ( $this->module ['main']->instance_id )) echo $this->module ['main']->instance_id; else echo "folder_listing"?>'
class='<?php if (isset ( $this->module ['main']->plugin_id )) echo $this->module ['main']->plugin_id; echo " " . xbuild ( "width", array ($_GET, $_COOKIE ) ) . "width"; echo " " . xbuild ( "font", array ($_GET, $_COOKIE ) ) . "font";
foreach ( Oski::app()->theme->options as $option ) echo " " . $option . "_" . xbuild ( $option, array ($_GET, $_COOKIE ) ); ?>'>
	<?php
	insertComponent ( "postHead" );
	?>
	<div class='options'>
<form action='<?php
linkTo ()?>' method='get'><input type='submit' value="W" /><input
	type="hidden" name="width"
	value="<?php
	echo (isset ( $_COOKIE ['width'] ) && $_COOKIE ['width'] == "fluid" || isset ( $_GET ['width'] ) && $_GET ['width'] == "fluid" ? "normal" : "fluid")?>"><input
	type="hidden" name="set_permanent" value='1' /></form>
<form action='<?php
linkTo ()?>' method='get'><input type='submit' value="F" /><input
	type="hidden"
	value="<?php
	if (xcheck ( "font", "big", array ($_GET, $_COOKIE ) ))
		echo "small";
	else if (xcheck ( "font", "small", array ($_GET, $_COOKIE ) ))
		echo "normal";
	else
		echo "big";
	?>"
	name='font'><input type="hidden" name="set_permanent" value='1' /></form>
</div>
<div class="wrapper">
