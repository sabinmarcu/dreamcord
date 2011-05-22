<?php

/**
 * The Oski object is the application framework which bootstarts the entire Oski Engine application.
 *
 * @package Oski
 */

class Oski{

	private static $oe;
	private static $actp;
	private static $info;
	private static $site_info;
	private static $theme_info;
	private static $db_info;
	private static $children_info;
	private static $module;

	/**
	 * Creates the Oski Engine singleton instance if not available and returns it.
	 * @return Oski Returns the Oski Engine singleton instance.
	 */

	public static function app()	{
		if (self::$oe === NULL)	self::$oe = new self();
		return self::$oe;
	}

	/**
	 * This function is a shorthand for the getProp method which fetches either the entire ACTP reccord, either a single element of the ACTP reccord specified via the parameter
	 * @param string The ACTP reccord element to be fetched.
	 * @return string Returns the entire ACTP reccord if no parameter is specified or the ACTP reccord element specified.
	 */

	public function getActp($which = NULL)	{
	    return self::getProp("actp", $which);
	}

	/**
	 * This function is a shorthand for the resetProp method which unsets the modules list.
	 * @return NULL It does not return anything.
	 */

	public function resetModule()	{
	    self::resetProp("module");
	}

	/**
	 * This function is a shorthand for the getProp method which fetches one of the modules loaded in the current session.
	 * @param string $which The name of the module to be fetched.
	 * @return module The module to be fetched.
	 */

	public function getModule($which = NULL)	{
	    return self::getProp("module", $which);
	}

	/**
	 * This function is a shorthand for the setProp method which adds a new module to the list of modules loaded in the current session.
	 * @param string $mod The name of the module to be instantiated.
	 * @param string $value The object to be created.
	 * @return bool Wether the addition of the module was successful.
	 */

	public function setModule($mod, $value)  {
	    return self::setProp("module", $value, $mod);
	}

	/**
	 * This function is a shorthand for the getProp method which fetches from the cache the requested piece of information about the current virtual website.
	 * @param string $element The name of the information requested.
	 * @return string The information requested.
	 */

	public function getInfo($element = NULL)    {
	    return self::getProp("info", $element);
	}

	/**
	 * Fetches the requested variable from cache.
	 * @param type $prop The variable requested.
	 * @param type $indice The indice of the variable requested (if the variable is an array)
	 * @return type The variable requested.
	 */

	public function getProp($prop, $indice = NULL)	{
	    if ($indice === NULL)
		if (isset($this -> $prop)) return $this -> $prop;
		else return NULL;
	    else {
		$var = $this -> $prop;
		if (is_array($indice))	{
		    foreach($indice as $ind)  if (isset($var[$ind])) $var = $var[$ind];  else return NULL;
		    return $var;
		} else if (!isset($var[$indice]))  return NULL;
		return $var[$indice];
	    }
	}

	/**
	 * Sets the title to be printed in the head section of the page.
	 * @param string $title The title to be set in the head section.
	 */

	public function setTitle($title)    {
	    self::setProp("header-title", $title);
	}

	/**
	 * Releases from cache a certain variable.
	 * @param string $prop The name of the property to be released from cache.
	 */

	public function resetProp($prop)   {
	    if (isset($this -> $prop))
		    unset($this -> $prop);
	}

	/**
	 * Modifies a specific variable of the application cache.
	 * @param string $prop The variable to be modified.
	 * @param string $value The value to be inserted into cache.
	 * @param string $indice The indice of the variable (if the variable is an array)
	 * @return bool Wether the modification of the cache was successful.
	 */

	public function setProp($prop, $value, $indice = NULL)	{
	    try{
		if ($indice === NULL)	$this -> $prop = $value;
		else {
		    $var = &$this -> $prop;
		    $var[$indice] = $value;
		}
	    } catch (Exception $e)  {
		return 0;
	    }
	    return 1;
	}

	/**
	 * In the construction phase some variables are created for the pourpose of running the application, and the application framework is loaded.
	 */

	public function __construct()	{
		session_start(); ini_set("display_errors", "1");
		if (defined("APP_ENV") && APP_ENV === "devel") error_reporting(E_ALL);
		else error_reporting(0);
		include 'defines.php';
		$this -> mvcs = 0;
		$this -> excludeFolders = array("oski_application", "oski_content", "oski_includes", "oski_uploads");
		$this -> libInc('oski_includes/interfaces');
		$this -> libInc('oski_includes/classes');
		$this -> analiseURL();
		if ($this -> readCFG())	$this -> signal = 'gtg';
		else $this -> signal = 'install';
	}
	/**
	 * The initialization function coordonates the execution of the application through the
	 * necessary steps : logging, database connection, translation, layout generation,
	 * component insertion, module execeution and printing, checking for errors and finally
	 * reading the theme to be used.
	 */

	public function initialise()	{
		$this -> startLogging();
		$this -> conDB();
		$this -> getSiteInfo();
		$this -> getLanguage();
		$this -> getLayout();
		$this -> getComponents();
		$this -> setModules();
		$this -> openModule();
		$this -> check();
		$this -> setTheme();
		return self::$oe;
	}

	/**
	* The getLanguage function initialises the translation object that will be used to translate the pages into diffrent languages
	*
	*/

	public function getLanguage()	{
		$this -> localizer = new localizer($this -> config['SITE_SETUP']['language']);
	}

	/**
	* The loadEngine function generates the page to be sent to the browser
	*/

	public function loadengine()	{
		$this -> runBackstage();
		$quirks = xcheck("quirks", "1", array($_GET, $_POST));
		if (!$quirks){
			$this -> loadDoctype();
			$this -> theme -> loadHeader();
		}
		if (in_array($this -> actp['module'], array("login", "logout", "register", "admin")))	$this -> module['main'] -> loadView();
		else $this -> layout -> getViews();
		if (!$quirks){
			$this -> theme -> loadFooter();
			$this -> loadData();
		}
	}


	public function openmodule()	{
		if (isset($this -> module['main']))	$this -> module['main'] -> loadController();
	}


	public function libInc($dir)	{
	if (!file_exists($dir))	return 0;
		fileHelper::crawlFolder($dir, "php", "incFile");
	}

	public function incFile($file)	{   include $file;  }

	private function readCFG()	{
	if (file_exists(ENGNDIR.'default.ini'))	:
		self::setProp("instance", "default");
		self::setProp("config", new ini_reader(ENGNDIR.'default.ini'));
		self::setProp("config", self::getProp("config") -> toDict());
		if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) $childconf = $_SERVER['HTTP_X_FORWARDED_HOST'];
		else if (isset($_GET['config'])) $childconf = $_GET['config'];
		else $childconf = $_SERVER['HTTP_HOST'];
		if (isset($this -> config['CHILDREN'][$childconf]) && file_exists(ENGNDIR.$this -> config['CHILDREN'][$childconf].".ini")) {
			$config = ENGNDIR.$this -> config['CHILDREN'][$childconf];
			self::setProp("instance", self::getProp("config", array("CHILDREN", $childconf)));
			$this -> config = new ini_reader($config.'.ini');
			$this -> config = $this -> config -> toDict();
		}
		return true;
	else : return false;
	endif;
	}

	private function analiseURL()	{
		self::setProp("baseURL", str_replace(" ", "%20", str_replace($_SERVER['DOCUMENT_ROOT'], "", dirname($_SERVER['SCRIPT_FILENAME']))));
		if (isset($_GET['route']))	$medium = "/".$_GET['route']."/"; else $medium = $_SERVER['REQUEST_URI'];
		$medium = str_replace($this -> baseURL, "", $medium);
		$url = explode("/", $medium);
		if (strpos($url[1], "?") !== FALSE)	$this -> actp['module'] = str_replace(substr($url[1], strpos($url[1], "?")), "", $url[1]);
		else $this -> actp['module'] = $url[1];
		if (isset($url[2]))	{
			if (strpos($url[2], "?") !== FALSE) $this -> actp['action'] = str_replace(substr($url[2], strpos($url[2], "?")), "", $url[2]);
			else $this -> actp['action'] = $url[2];
		} else $this -> actp['action'] = "";
		for ($i = 1; $i <= count($url) - 2; $i++)	if (strpos($url[$i], "?") !== FALSE) $this -> actp[$i] = str_replace(substr($url[$i], strpos($url[$i], "?")), "", $url[$i]); else $this -> actp[$i] = $url[$i];
		$str = "?";
		foreach ($_GET as $key => $value)	$str .= $key . "=" . $value . "&";
		$str = substr($str, 0, strlen($str) - 1);
		$this -> actp['complete'] = str_replace($str, "", $medium);
		if (in_Array($this -> actp['module'], $this -> excludeFolders)) sysErr(403, "URL Analise");
	}

	private function conDB()	{
		if ($this -> config['DB_DATA']['database_type'] == "null") 	$this -> database = new NO_DATABASE_CONNECTION();
		else {
			$database = $this -> config['DB_DATA']['database_type']."_DATABASE_CONNECTION";
			$res = true;
			$this -> database = new $database($res);
			if (!$res)	$this -> database = new NO_DATABASE_CONNECTION();
		}
	}

	private function getSiteInfo()	{
		self::setProp("info", $this -> config['SITE_INFO']);
		self::setProp("site_info", $this -> config['SITE_SETUP']);
		self::setProp("db_info", $this -> config['DB_DATA']);
		self::setProp("theme_info", $this -> config['THEME_SETUP']);
		self::setProp("children_info", $this -> config['CHILDREN']);
	}


	public function getLayout()	{
		$this -> layout = new main_layout();
	}

	public function setmodules()	{
		$this -> layout -> openModules();
	}

	public function initTheme($theme)	{
		if (file_exists("oski_content/themes/".$theme."/"))
		$this -> theme = new theme_loader($theme);
		else $this -> theme = new theme_loader($this -> config['THEME_SETUP']['default_theme']);
	}

	public function setTheme()	{
		if (isset($this -> config['THEME_SETUP']['error_theme']) && (DEFINED("MVCS"))) $this -> initTheme($this -> config['THEME_SETUP']['error_theme']);
		else if (isset($this -> config['THEME_SETUP']['home_theme']) && ($this -> actp['module'] === "")) $this -> initTheme($this -> config['THEME_SETUP']['home_theme']);
		else if (isset($this -> config['THEME_SETUP']['user_theme']) && ($this -> actp['module'] === "admin") && ($this -> actp['panel'] === "userpanel")) $this -> initTheme($this -> config['THEME_SETUP']['user_theme']);
		else if (isset($this -> config['THEME_SETUP']['admin_theme']) && ($this -> actp['module'] === "admin")) $this -> initTheme($this -> config['THEME_SETUP']['admin_theme']);
		else if (isset($this -> config['THEME_SETUP']['login_theme']) && (in_array($this -> actp['module'], array("login", "logout", "register")) )) $this -> initTheme($this -> config['THEME_SETUP']['login_theme']);
		else {
			if (isset($_GET['theme'])) $this -> initTheme($_GET['theme']);
 			else if (isset($_COOKIE['theme'])) $this -> initTheme($_COOKIE['theme']);
			else $this -> initTheme($this -> config['THEME_SETUP']['default_theme']);
		}
	}

	private function loadDoctype()	{
		if ($this -> theme -> doctype && file_exists('oski_includes/resources/doctypes/html'.$this -> theme -> doctype .'.php'))		include 'oski_includes/resources/doctypes/html'.$this -> theme -> doctype .'.php';
		else include 'oski_includes/resources/doctypes/html5.php';
		include 'oski_includes/resources/parts/head.php';
	}

	private function loadData()	{
		include 'oski_includes/resources/parts/foot.php';
	}
	public function runBackstage()	{
		checkSetting("theme");
		checkSetting("width");
		checkSetting("font");
		foreach ($this -> theme -> options as $option)	checkSetting($option);

		if ($this -> config['SITE_SETUP']['root'] != $this -> config['SITE_SETUP']['domain']) $_SESSION['redir'] =  $this -> config['SITE_SETUP']['root'];
		if (isset($_SESSION['username']) && !isset($_COOKIE['tuser_username']) && isGL() && isset($_COOKIE['t_rec'])) $this -> setTCookie("session");
		if (!isset($_COOKIE['t_rec']) && isset($_SESSION['username']) && isGL() ) {
			unset($_SESSION['name']);
			unset($_SESSION['surname']);
			unset($_SESSION['admin']);
			unset($_SESSION['username']);
		}
		if (isset($_COOKIE['tuser_username']) && (isset($this -> config['SITE_SETUP']['global_login']) || $this -> config['SITE_SETUP']['root'] == $this -> config['SITE_SETUP']['domain']))	{
			$_SESSION['name'] = $_COOKIE['tuser_name'];
			$_SESSION['surname'] = $_COOKIE['tuser_surname'];
			$_SESSION['username'] = $_COOKIE['tuser_username'];
			$_SESSION['admin'] = $_COOKIE['tuser_admin'];
			session_write_close();
		}
		if (isset($_COOKIE['user_username'])) {
			$_SESSION['name'] = $_COOKIE['user_name'];
			$_SESSION['surname'] = $_COOKIE['user_surname'];
			$_SESSION['username'] = $_COOKIE['user_username'];
			$_SESSION['admin'] = $_COOKIE['user_admin'];
			session_write_close();
			$this -> setTCookie("cookie");
		}
	}

	private function check()	{
		if (DEFINED("MVCS"))	{
			$this -> layout -> clearLayout();
			$this -> module["main"] = new error_loader(404);
			$this -> module["main"] -> loadController();
			$this -> layout -> config["0"]['type'] = 'current_module';
			$this -> layout -> config["0"]['container'] = 'article';
		}
	}

	private function setTCookie($what)	{
		if ($what == "cookie") :
			setcookie('tuser_name', ($_COOKIE['user_name'] ? $_COOKIE['user_name'] : 0), time() + 60 * 5, "/", ".".$this -> config['SITE_SETUP']['root']);
			setcookie('tuser_surname', ($_COOKIE['user_surname'] ? $_COOKIE['user_surname'] : 0), time() + 60 * 5, "/", ".".$this -> config['SITE_SETUP']['root']);
			setcookie('tuser_username', ($_COOKIE['user_username'] ? $_COOKIE['user_username'] : 0), time() + 60 * 5, "/", ".".$this -> config['SITE_SETUP']['root']);
			setcookie('tuser_admin', ($_COOKIE['user_admin'] ? $_COOKIE['user_admin'] : 0), time() + 60 * 5, "/", ".".$this -> config['SITE_SETUP']['root']);
		elseif ($what == "session") :
			setcookie('tuser_name', ($_SESSION['name'] ? $_SESSION['name'] : 0), time() + 60 * 5, "/", ".".$this -> config['SITE_SETUP']['root']);
			setcookie('tuser_surname', ($_SESSION['surname'] ? $_SESSION['surname'] : 0), time() + 60 * 5, "/", ".".$this -> config['SITE_SETUP']['root']);
			setcookie('tuser_username', ($_SESSION['username'] ? $_SESSION['username'] : 0), time() + 60 * 5, "/", ".".$this -> config['SITE_SETUP']['root']);
			setcookie('tuser_admin', ($_SESSION['admin'] ? $_SESSION['admin'] : 0), time() + 60 * 5, "/", ".".$this -> config['SITE_SETUP']['root']);
		endif;

	}

	private function startLogging()	{
		$this -> logger = new logger;
		$this -> logger -> log( "[" . (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']) . "] :: Access : ". $this -> actp['complete'] . " by user at : <" . $_SERVER['REMOTE_ADDR'] . "> User agent : " . $_SERVER['HTTP_USER_AGENT'] , "access");
	}

	public function __destruct()	{
		if (isset($this -> logger))		$this -> logger -> endSession();
	}

	private function getComponents()	{
		$file = new xml_reader(CACHEDIR."components.xml");
		$file = $file -> toArray();
		$this -> components = array();
		foreach($file -> component as $comp)	{
			if (intval($comp -> activated))	{
				include COMPDIR.$comp -> name."/component.php";
				$file2 = new ini_reader(COMPDIR.$comp -> name."/config.ini");
				$file2 = $file2 -> toDict();	$compName = $file2['component']."Component";
				$this -> components[$file2['component']] = new $compName();
			}
		}
	}
}

class fileHelper    {

    public function crawlFolder($dir, $extension, $func) {

	if (!file_exists($dir))	return 0;
		$Directory = new RecursiveDirectoryIterator($dir);
		$Iterator = new RecursiveIteratorIterator($Directory);
		$Regex = new RegexIterator($Iterator, '/^.+\.'.$extension.'$/i', RecursiveRegexIterator::GET_MATCH);
		foreach ($Regex as $file)
		    $this -> $func( $file[0] );

	}

}

?>
