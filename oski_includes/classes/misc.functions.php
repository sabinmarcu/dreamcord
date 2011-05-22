<?php

function redirect($location) {
	header ( "Location: " . $location );
}

function getAdminNav() {

	echo "<ul>";
	if (! $_SESSION ['admin'])
		echo "<li> You are NOT an admin </li>";
	else {
		echo "
		<li id='engine'><a href=\"" . linkTo ( "admin/engine" ) . "\" title='" . __ ( "Configure the OSKI ENGINE installation" ) . "' accesskey='o'>" . __ ( "Engine Configuration (<u>O</u>)" ) . "</a>
		<li id='pages'><a href=\"" . linkTo ( "admin/pages" ) . "\" title='" . __ ( "Add, edit or remove pages." ) . "' accesskey='p'>" . __ ( "Pages (<u>P</u>)" ) . "</a></li>
		<li id='extensions'><a href=\"" . linkTo ( "admin/extensions" ) . "\" title='" . __ ( "Install new Extensions" ) . "' acceskey='e'>" . __ ( "Extensions (<u>E</u>)" ) . "</a>
		<ul>
			<li id='modules'><a href=\"" . linkTo ( "admin/extensions/modules" ) . "\" title='" . __ ( "Edit the modules" ) . "' acceskey='m'>" . __ ( "Modules (<u>M</u>)" ) . "</a></li>
			<li id='components'><a href=\"" . linkTo ( "admin/extensions/components" ) . "\" title='" . __ ( "Edit the components" ) . "' acceskey='c'>" . __ ( "Components (<u>C</u>)" ) . "</a></li>
			<li id='install'><a href=\"" . linkTo ( "admin/extensions/install" ) . "\" title='" . __ ( "Install new Extensions" ) . "' acceskey='i'>" . __ ( "Install Extensions (<u>I</u>)" ) . "</a></li>
		</ul>
		</li>
		<li id='layouts'><a href=\"" . linkTo ( "admin/layouts" ) . "\" title='" . __ ( "Create new layouts for your application" ) . "' acceskey='l'>" . __ ( "Layouts (<u>L</u>)" ) . "</a>
		<ul>
			<li id='navigation'><a href=\"" . linkTo ( "admin/layouts/navigation" ) . "\" title='" . __ ( "Create or edit navigation elements" ) . "' acceskey='n'>" . __ ( "Navigation Menus (<u>N</u>)" ) . "</a></li>
			<li id='templates'><a href=\"" . linkTo ( "admin/layouts/templates" ) . "\" title='" . __ ( "Create or edit page templates" ) . "' acceskey='t'>" . __ ( "Page Templates (<u>T</u>)" ) . "</a></li>
		</ul>
		</li>
		<li id='logs'><a href=\"" . linkTo ( "admin/logs" ) . "\" title='" . __ ( "View the logs ... " ) . "' accesskey='l'>" . __ ( "Logs (<u>L</u>)" ) . "</a><ul>";
		foreach ( Oski::app()->logger->logs as $log => $content ) :
			echo "<li id = '" . $log . "'><a href=\"".  linkTo( "admin/logs/" . $log ) . "\">" . ucwords ( $log ) . "</a></li>";
		endforeach
		;
		echo "</ul></li>
		";
		echo "</ul>";

	/*<li id='instances'><a href=\"/admin/extensions/\" title='".__("Configure the available extensions or install new ones")."' accesskey='a'>".__("Extensions Zone (<u>E</u>)")."</a>"; get_oski_admin(); echo "</li>
		<li id='modules'><a href=\"/admin/instances/\" title = 'Configure the actual module Instances' accesskey='i'><u>I</u>nstances Admin</a></li>
		<li><a href='/admin/uninstall' accesskey='u'><u>U</u>ninstall</a></li>";*/
	}
}
function getTitle() {
	?>
<h1 class="site title"><?php
	echo Oski::app() -> getInfo("site_title") . "<span class='small'>" , Oski::app() -> getProp("header-title"), "</span>";
	?> </h1>
<?php
}
function getSitetitle() {

	echo Oski::app()->getProp("info", 'site_title');
}
function getTagline() {

	?>
<h3 class="site tagline"><?php
	echo Oski::app()->getProp("info", 'site_tagline')?></h3>
<?php
}
function getSiteTagline() {

	echo Oski::app()->getProp("info", 'site_tagline');
}
function getNavigation($which = "main") {

	$nav = new nav_layout ( $which );
	$nav->getNav ();
}
function getUserName($return = 0) {
	if (isset ( $_SESSION ['username'] )) {
		if ($return)
			return $_SESSION ['name'] . " " . $_SESSION ['surname'];
		else
			echo $_SESSION ['name'], " ", $_SESSION ['surname'];
	}
}
function greetUserName($return = 0) {
	if ($return)
		return randGen ( __ ( "Welcome Back, " ), __ ( "Howdy, " ), __ ( "Hi, " ), __ ( "Glad to have you back, " ) ) . getUserName ( 1 );
	else
		echo randGen ( __ ( "Welcome Back, " ), __ ( "Howdy, " ), __ ( "Hi, " ), __ ( "Glad to have you back, " ) ), getUserName ( 1 );
}
function getUserNameElem() {
	if (isset ( $_SESSION ['username'] ))
		echo "<li id='username'>", randGen ( __ ( "Welcome Back, " ), __ ( "Howdy, " ), __ ( "Hi, " ), __ ( "Glad to have you back, " ) ), getUserName ( 1 ), "</li>";
}
function getAdmin() {
	echo "<ul>";
	if (isset ( $_SESSION ['username'] )) {
		echo "<li id='usrcfg'><a href='" . lnk ( "admin/userpanel" ) . "' title = '" . __ ( "User Admin Panel" ) . "' accesskey='m'>" . __ ( "<u>M</u>y Account" ) . "</a></li>";
		if (isset ( $_SESSION ['admin'] ) && $_SESSION ['admin'] == 1)
			echo "<li id='admin'><a href='" . lnk ( "admin" ) . "' title = '" . __ ( "Admin Panel" ) . "' accesskey='c'>" . __ ( "<u>C</u>ommand Pannel" ) . "</a></li>";
		echo "<li id='logout'><a href='" . lnk ( "logout" ) . "' title='" . __ ( "Logout!" ) . "' accesskey='l'>" . __ ( "<u>L</u>ogout</a>" ) . "</a></li>";
	} else {
		echo "<li id='login'><a href='" . lnk ( "login" ) . "' title='" . __ ( "Login!" ) . "' accesskey='l'>" . __ ( "<u>L</u>ogin" ) . "</a></li>";
		echo "<li id='register'><a href='" . lnk ( "register" ) . "' title='" . __ ( "Register!" ) . "' accesskey='r'>" . __ ( "<u>R</u>egister" ) . "</a></li>";
	}
	echo "</ul>";
}

function announce($what, $title, $content = "", $redirect = "", $offset = "", $time = 3, $returnstring = false) {
	$string = "<h1 class=\"" . $what . "\">" . $title;
	if ($offset)
		$string .= "<span class='right'>" . $offset . "</span>";
	$string .= "</h1>";
	if (is_array ( $content ))
		foreach ( $content as $paragraph )
			$string .= "<p>" . $paragraph . "</p>";
	else if (is_string ( $content ))
		$string .= "<p>" . $content . "</p>";
	if ($redirect)
		$string .= "<meta http-equiv='refresh' content=" . $time . ";url='" . linkTo ( $redirect ) . "' >";
	if ($returnstring)
		return $string;
	else
		echo $string;
}

function server_error($error, $reason = "") {
	if (file_exists ( 'oski_includes/errors/' . $error . ".php" ))
		include 'oski_includes/errors/' . $error . ".php";
	else
		die ( "Error reporting Failed!" );
}

function getLogo() {

	echo "<img src='/oski_includes/resources/images/logo.png' alt=\"" . Oski::app()->getProp("info", 'site_title') . "\" class='logo'/>";
}
function getLogoAddress() {
	echo "/oski_includes/resources/images/logo.png";
}

function recCpy($src, $dst) {
	$dir = opendir ( $src );
	@mkdir ( $dst );
	while ( false !== ($file = readdir ( $dir )) ) {
		if (($file != '.') && ($file != '..')) {
			if (is_dir ( $src . '/' . $file )) {
				recCpy ( $src . '/' . $file, $dst . '/' . $file );
			} else {
				copy ( $src . '/' . $file, $dst . '/' . $file );
			}
		}
	}
	closedir ( $dir );
}

function getToNoDuplicateDir($file) {
	$i = 0;
	if (file_exists ( $file ))
		$file = $file . $i;
	while ( file_exists ( $file ) ) {
		$i ++;
		$file = substr ( $file, 0, strlen ( $file ) - strlen ( $i - 1 ) ) . $i;
	}
	return $file;
}

function recRmdir($dir) {
	if (is_dir ( $dir )) {
		$objects = scandir ( $dir );
		foreach ( $objects as $object ) {
			if ($object != "." && $object != "..") {
				if (filetype ( $dir . "/" . $object ) == "dir")
					recrmdir ( $dir . "/" . $object );
				else
					unlink ( $dir . "/" . $object );
			}
		}
		reset ( $objects );
		rmdir ( $dir );
	}
}

function recListDir($dir) {
	if (is_dir ( $dir )) {
		echo "<ul>";
		$objects = scandir ( $dir );
		foreach ( $objects as $object ) {
			if ($object != "." && $object != "..") {
				if (filetype ( $dir . "/" . $object ) == "dir")
					recListDir ( $dir . "/" . $object );
				else
					echo "<li>" . $dir . "/" . $object . "</li>";
			}
		}
		reset ( $objects );
		echo "</ul>";
	}
}

function sysErr($error, $where = "unknown") {
	signal ( $error, $where );
}

function getBTTLink() {

	if (! isset ( Oski::app()->theme->btt )) :
		?><a
	href="#<?php
		if (isset ( Oski::app() -> getModule('main')->instance_id ))
			echo Oski::app() -> getModule('main')->instance_id;
		else
			echo "folder_listing"?>"><?php
		__e ( "Back to Top" )?></a><?php
		Oski::app()->theme->btt = 1;

	endif;
}
function getThemeSelector() {

	if (! isset ( Oski::app()->theme->selector )) :
		?><form action='<?php
		linkTo ()?>' method='get'><select name='theme'>
	<option value='no-go'><?php
		__e ( "Select the theme you wanna use!" )?></option>
			<?php
		$file = (file_exists ( CACHEDIR . Oski::app()->instance . "/themes.xml" ) ? CACHEDIR . Oski::app()->instance . "/themes.xml" : CACHEDIR . "themes.xml");
		$cfg = new xml_reader ( $file );
		$cfg = $cfg->toArray ();
		$cfg = $cfg->reusable;
		foreach ( $cfg->theme as $theme )
			echo "<option value=" . $theme . ">" . ucwords ( $theme ) . "</option>";
		?>
			</select><input type="submit" value='<?php
		__e ( "Change Theme!" )?>' /><input
	type='hidden' name='set_permanent' value='1' /></form><?php
		Oski::app()->theme->selector = 1;

	endif;

}
function getCredits() {

	if (! isset ( Oski::app()->theme->credits )) :
		$theme = __ ( "Theme in use" ) . " : '";
		if (file_exists ( "oski_content/themes/" . Oski::app()->theme->name . "/credits.ini" )) {
			$file = new ini_reader ( "oski_content/themes/" . Oski::app()->theme->name . "/credits.ini" );
			$file = $file->toDict ();
			$theme .= $file ['name'] . "' " . __ ( "by" ) . " ";
			if (isset ( $file ['auth_link'] ))
				$theme .= "<a href='" . $file ['auth_link'] . "'>";
			$theme .= $file ['author'] . " ";
			if (isset ( $file ['auth_link'] ))
				$theme .= "</a>";
		} else
			$theme .= Oski::app()->theme->name . "'";
		?>&copy;
<a href="<?php
		echo AUTHORWEB?>"><?php
		echo AUTHOR?></a>
2010 <?php
		if (date ( "Y", time () - strtotime ( "2010" ) ) > 1960)
			echo " - " . date ( "Y", time () )?>  |  <?php
		__e ( "Powered by" )?>
<a href='<?php
		echo DEVELTEAMWEB ?>'> <?php
		echo FULLAPPNAME, " ", APPVER?></a>
| <?php
		echo $theme;
		?><?php

		Oski::app()->theme->credits = 1;

	endif;
}
function dewrap($string) {
	return ucwords ( str_replace ( ".", " ", str_replace ( "_", " ", $string ) ) );
}
function deext($string) {
	if (strpos ( $string, "." ) === false)
		return $string;
	else
		return substr ( $string, 0, strpos ( $string, "." ) );
}
function noCredits() {

	Oski::app()->theme->credits = 1;
}
function noBTT() {

	Oski::app()->theme->btt = 1;
}
function noTSelector() {

	Oski::app()->theme->selector = 1;
}
function noTDetails() {
	noCredits ();
	noBTT ();
	noTSelector ();
}
function lnk($to) {

	if (isset ( Oski::app()->config ['SITE_SETUP'] ['global_login'] ) && strpos ( Oski::app()->config ['SITE_SETUP'] ['root'], Oski::app()->config ['SITE_SETUP'] ['domain'] ))
		return linkTo ( "http://" . Oski::app()->config ['SITE_SETUP'] ['root'] . "/" . $to );
	else
		return linkTo ( $to );
}

function xcheck($index = "", $value = "", $arrays = array()) {
	foreach ( $arrays as $array ) {
		if (isset ( $array [$index] ) && $array [$index] == $value)
			return true;
	}
	return false;
}

function xbuild($index = "", $arrays = array()) {
	foreach ( $arrays as $array ) {
		if (isset ( $array [$index] ))
			return $array [$index];
	}
	return false;
}

function checkSetting($set) {

	if (isset ( $_GET [$set] ) && isset ( $_GET ['set_permanent'] ) && $_GET ['set_permanent'] == 1) {
		setcookie ( $set, Oski::app()->database->escape ( $_GET [$set] ), time () + 60 * 60 * 24 * 30, '/' );
		header ( "Location: " . (isset ( $_SERVER ['HTTP_REFERER'] ) ? $_SERVER ['HTTP_REFERER'] : "/") );
	}
}

function signal($what, $where = "unknown") {
	if (DEFINED ( "MVCS" ))
		return false;
	DEFINE ( "MVCS", $what );
	DEFINE ( "MVCW", $where );
	Oski::app() ->logger->log ( "[" . (isset ( $_SERVER ['HTTP_X_FORWARDED_HOST'] ) ? $_SERVER ['HTTP_X_FORWARDED_HOST'] : $_SERVER ['HTTP_HOST']) . "] :: Access : " . actp('complete') . " by user at : <" . $_SERVER ['REMOTE_ADDR'] . "> User agent : " . $_SERVER ['HTTP_USER_AGENT'] . " Error message : <" . $what . "> caught at : &lt;" . $where . "&gt;", "security" );
}

function isGL() {

	return (isset ( Oski::app()->config ['SITE_SETUP'] ['global_login'] ) || Oski::app()->config ['SITE_SETUP'] ['root'] == Oski::app()->config ['SITE_SETUP'] ['domain']) && strpos ( Oski::app()->config ['SITE_SETUP'] ['root'], Oski::app()->config ['SITE_SETUP'] ['domain'] != NULL );
}

function __($string) {

	if (isset ( Oski::app()->localizer ))
		return Oski::app()->localizer->match ( $string );
	else
		return $string;
}
function __e($string) {
	echo __ ( $string );
}
function randGen() {
	$args = func_get_args ();
	$seed = mt_rand ( 1, count ( $args ) );
	$count = 0;
	foreach ( $args as $arg ) {
		$count ++;
		if ($count == $seed)
			return $arg;
	}
}
function randArr($array) {
	$seed = mt_rand ( 1, count ( $array ) - 1 );
	return $array [$seed];
}
function randArr5($array) {
	$seed = array ();
	for($i = 0; $i < 5; $i ++)
		$seed [$i] = randArr ( $array );
	return randArr ( $seed );
}
function xRandGen() {
	$args = func_get_args ();
	$arr = array ();
	foreach ( $args as $arg ) {
		$arr [count ( $arr )] = randArr5 ( $arg );
	}
	return randArr5 ( $arr );
}
function devlog() {

	$args = func_get_args ();
	foreach ( $args as $arg )
		Oski::app()->logger->log ( $arg, "dev" );
}
function devlogprint() {

	if (isset ( Oski::app()->devlog ))
		foreach ( Oski::app()->devlog as $log )
			echo var_dump ( $log ), "<br><br>";
}
function getTPart($part, $classes = "", $variant = NULL, $override = 0) {

	Oski::app()->theme->getPart ( $part, $classes, $variant, $override );
}

function secure($string) {

	if (isset ( Oski::app()->database->error ) || ! isset ( Oski::app()->database ))
		return htmlentities ( addslashes ( $string ) );
	return htmlentities ( Oski::app()->database->escape ( $string ) );
}

function actp($string) {
	 $str = Oski::app() -> getActp();
	if (isset ( $str [$string] ))
		return $str [$string];
	return null;
}

function ext($ext) {
	return end ( explode ( ".", $ext ) );
}

function insertComponent($component) {

	foreach ( Oski::app()->components as $comp )
		$comp->getFunction ( $component );
}
function getErrorLocation() {
	echo MVCW;
}

function linkTo($to = "", $get = 1, $ssl = 0)	{
    return urlHelper::linkTo($to, $get, $ssl);
}
function eLinkTo($to = "", $get = 1, $ssl = 0)	{
    urlHelper::eLinkTo($to, $get, $ssl);
}
function linkToF($to = "", $get = 1, $ssl = 0)	{
    return urlHelper::linkToF($to, $get, $ssl);
}
?>
