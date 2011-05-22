<?php
/**
* The theme loader object loads the theme configuration and handles the printing of the header, footer, and the diffrent container objects defined by the theme.
*
* @package Loaders
* @author Sabin Marcu
* @property has_css Wether the theme loaded requires the inclusion of css files.
* @property has_js Wether the theme loaded requires the inclusion of js files.
* @property css_dir The directory from which the css files will be loaded.
* @property js_dir The directory from whici the js files will be loaded.
*/

class theme_loader	{

	public $dir;
	public $has_css;
	public $has_js;
	public $has_font;
	public $css_dir;
	public $js_dir;
	public $font_dir;

	/**
	* When the object is created, it automatically loads the theme configuration
	*
	*/

	function __construct($theme)	{
			$themecfg = new ini_reader('oski_content/themes/'.$theme.'/config.ini');
			$themecfg = $themecfg -> toDict();
			if (isset($themecfg['header_script']))	$this -> header_script = $themecfg['header_script']; else $this -> header_script = 'header.php';
			if (isset($themecfg['footer_script']))	$this -> footer_script = $themecfg['footer_script']; else $this -> footer_script = 'footer.php';
			if (isset($themecfg['options']))	$this -> options = explode(",", str_replace(" ", "", $themecfg['options'])); else $this -> options = array();
			if (isset($themecfg['parts_dir']))	$this -> parts_dir = $themecfg['parts_dir']."/"; else $this -> parts_dir = "parts/";
			$this -> themeExtras = $themecfg;
			if (isset($themecfg['css_dir']))	{
				$this -> css_dir = $themecfg['css_dir'];
				$this -> has_css = 1;
			}
			if (isset($themecfg['js_dir']))	{
				$this -> js_dir = $themecfg['js_dir'];
				$this -> has_js = 1;
			}
			if (isset($themecfg['font_dir']))	{
				$this -> font_dir = $themecfg['font_dir'];
				$this -> has_font = 1;
			}
			$this -> dir = 'oski_content/themes/'.$theme.'/';
			$this -> name = $theme;
			if (isset($themecfg['doctype'])) $this -> doctype = $themecfg['doctype']; else $this -> doctype = "";
	}

	/**
	* The loadHeader function is a shorthand for aquiring the header part of the theme.
	*/

	public function loadHeader()	{
		$this -> getPart('header');
	}

 	/**
	* The loadFooter function is a shorthand for aquiring the footer part of the theme.
	*/

	public function loadFooter()	{
		$this -> getPart('footer');
	}

	/**
	* The getCSS function crawls the css_dir and includes the necessary css files
	* @see css_dir
	*/

	public function getCSS()	{

	    fileHelper::crawlFolder($this -> dir.$this -> css_dir, "css", "includeCSS");

	}

	public function includeCSS($file)   {
	    echo "<link rel=\"stylesheet\" href='" . urlHelper::linkToF($file) . "' type=\"text/css\" media=\"screen\" title=\"no title\" charset=\"utf-8\">";
	}

	/**
	* The getJS function crawls the js_dir and includes the necessary js files
	* @see js_dir
	*/

	public function getJS()	{

	    fileHelper::crawlFolder($this -> dir.$this -> js_dir, "js", "includeJS");

	}
	/**
	* The getPart function includes a requested theme part along with aditional classes, etc
	*/

	public function includeJS($file)    {
	    echo "<script type='text/javascript' src = '" . urlHelper::linkToF($file) . "' ></script>";
	}

	public function getFont()   {

	    fileHelper::crawlFolder($this -> dir.$this -> font_dir, "css", "includeCSS");

	}

	public function getPart($part, $class = "", $variant = NULL, $override = 0)	{

		if (strpos($part, " "))	{
			$class .= substr($part, strpos($part, " "));
			$part = substr($part, 0, strpos($part, " "));
		}
		$class = " ".$class;
		if (isset($variant) && file_exists($this -> dir . $this -> parts_dir . $variant . "-" . $part . ".php")) $part = $variant."-".$part;
		$parts = "start".$part;	$parto = "order".$part;
		$part .= ".php";
		if (!isset($this -> $parts)) { $this -> $parts = 1; $this -> $parto = 0; }
		if ($override) { $this -> $parto++; $this -> $parts = 1; }
		if (file_exists($this -> dir . $this -> parts_dir . $part)) include $this -> dir . $this -> parts_dir . $part;
		if ($this -> $parts) {  $this -> $parts = 0; }
		else $this -> $parts = 1;
		if (!$override && $this -> $parto) { $this -> $parto --;  $this -> $parts = 0; }
	}


}

?>
