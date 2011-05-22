<?php
class layoutsController extends baseAdminController {
	function __construct()	{
		
		parent::__construct();
		$this -> prefix = actp('module')."/".actp('panel');
		$this -> url = str_replace($this -> prefix, "", actp('complete'));
		$this -> url = substr($this -> url, 1, strlen($this -> url) - 2);
		$this -> file = LODIR.Oski::app() -> instance.$this -> url;
		$this -> back = "/" . $this -> prefix . str_replace(end(explode("/", $this -> url)), "", $this -> url);
	}
	function _index() {
		$this -> _navigation();
	}
	function _navigation()	{
		$this -> file = str_replace("/navigation", "", $this -> file);
		if (file_exists($this -> file.".xml") && !is_dir($this -> file.".xml"))	{
			$this -> file .= ".xml";
			$this -> nav = new xml_reader($this -> file);
			$this -> nav = $this -> nav -> toArray();		
		}
		if (count($_POST)) 	$this -> navPOST();
		$this -> navView();
	}
	function _templates()	{
		$this -> file = str_replace("/templates", "", $this -> file);
		if (file_exists($this -> file . ".ini") && !is_dir($this -> file.".ini"))	{
			$this -> file .= ".ini";
			$this -> tpl = new ini_reader($this -> file);
			$this -> tpl = $this -> tpl -> toDict();
		}
		if (count($_POST))	$this -> templatePOST();
		$this -> templateView();
	}
	private function templatePOST()	{
		
	}
	private function templateView()	{
		if (is_dir($this -> file))	$this -> listTpls();
		else $this -> printTpl();	
	}
	private function listTpls()	{
		$this -> view = "list";
		$dir = opendir($this -> file);
		$this -> tpls = array();
		while ($file = readdir($dir))	if (ext($file) == "ini")	$this -> tpls[count($this -> tpls)] = $file;
	}
	private function printTpl()	{
		$this -> view = "edit";
	}
	private function navPost()	{
		if (isset($_POST['name'])) $this -> newNav();
		else if (isset($_POST['removeNav'])) $this -> remNav();
		else $this -> editNav();
	}
	private function navView()	{
		if (is_dir($this -> file))	$this -> listDir();
		else $this -> printNav();
	}
	private function listDir()	{
		$this -> view = "list";
		$dir = opendir($this -> file);
		$this -> navs = array();
		while ($file = readdir($dir)) if (ext($file) == "xml") $this -> navs[count($this -> navs)] = $file;
	}
	private function printNav()	{		
		$this -> view = "edit";
	}	
	private function newNav()	{
		if(file_exists($this -> file . "/" . $_POST['name'] . ".xml")) return false;
		$file = fopen($this -> file . "/" . $_POST['name']. ".xml", 'w');
		fclose($file);
	}
	private function remNav()	{
		unlink($this -> file . "/" . $_POST['removeNav']);
	}
	private function editNav()	{
		if (isset($_POST['update'])) $this -> updateResults();
	}
	private function updateResults()	{
		$links = array();
		for($i = 1; $i <= (count($_POST) - 1) / 3; $i++)	{
			$links[$i]['name'] = $_POST['name'.$i];
			$links[$i]['parent'] = $_POST['parent'.$i];
			$links[$i]['link'] = $_POST['link'.$i];
			$links[$i]['id'] = $i;
		}
		unset($this -> nav);
		$this -> nav = new SimpleXMLElement("<nav></nav>");
		$_POST = array(); $this -> links = $links; unset($links);
		$this -> buildNav($this -> nav, 1);
		$this -> saveNav();
	}
	private function buildNav(&$xml, $which)	{
		if (!isset($this -> links[$which])) return false;	$nav = $this -> links[$which];
		$children = $brothers = array();	$count = count($xml -> element);
		$xml -> element[$count] -> name = $nav['name']; 
		$xml -> element[$count] -> link = $nav['link'];
		$pos = 0;
		foreach($this -> links as $link) {
			if ($link['parent'] == $nav['id'])	$children[count($children)] = $link['id'];
			if ($link['parent'] == $nav['parent'])	{
				$brothers[count($brothers)] = $link['id'];
				if ($link['id'] == $nav['id']) $fpos = $pos;
				$pos++;
			}
		}
		$bc = count($brothers); 	unset($this -> links[$which]);
		if (count($children)) foreach($children as $id) $this -> buildNav($xml -> element[$count], $id); 
		if($fpos < $bc - 1) $this -> buildNav($xml, $brothers[$fpos+1]);
	}
	private function saveNav()	{
		$file = new xml_reader($this -> file, 'w');
		$file -> aToFile($this -> nav);
		$this -> result['what'] = "success";
		$this -> result['title'] = "Updated the Navigation menu Successfuly!";
		$this -> result['content'][0] = "You can now use this menu in any theme slots or in any page template";
		unset($file);
	}
	public function recGetSubNav($start, &$for, $i)	{
		
		foreach($start -> element as $elem)	{ getTPart("article", "navelem accordion", "admin", 1); getTPart("content", "small navcontent accordion", NULL, 1); $i++; ?>
			<div class='ot'><div class='input'><input type="text" name="link" id="link" class='link' value="<?php echo $elem -> link ?>"></div></div>
			<div class='ot'><div class='input'><input type="text" name="name" id="name" class='name' value="<?php echo $elem -> name ?>"></div></div>
		<?php if (count($elem -> element)) $this -> recGetSubNav($elem, $for, $i); getTPart("content", "", NULL); getTPart("article", "", "admin"); }
		
	}
	public function getNewNavForm()	{
		getTPart("article", "", "admin"); getTPart("title"); __e("Add a new File"); getTPart("title"); getTPart("content");
		$form = new formBuilder(actp('complete'), "POST", "newfileform");
		$form -> addInput("name");
		$form -> addLabel("name", __("The name of the File"));
		$form -> printForm();
		getTPart("content"); getTPart("article", "", "admin");
	}
	public function recPrintFS($dir) {
		$prefix = str_replace(PAGESDIR.Oski::app() -> instance,"",$dir);
		$read = opendir($dir);
		while ($file = readdir($read)) {
			if (is_dir($dir . "/" . $file)) {if (strpos($file, ".") !== 0) $this -> recPrintFS($dir . "/" . $file);}
			else {?><div class='inline-button'><?php echo substr($prefix. "/" . deext($file), 1);?></div><?php }
		}
	}
}
?>