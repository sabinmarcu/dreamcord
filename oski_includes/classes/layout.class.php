<?php
class base_layout	{

	public function __construct($file = NULL)	{
		if (isset($file) && file_exists(LODIR.$file.".ini"))	$this -> readLayout($file);
	}

	public function readLayout($file)	{
		$read = new ini_reader(LODIR.Oski::app() -> instance."/".$file.".ini");
		$this -> config = $read -> toDict();
	}

	public function clearLayout()	{
		unset($this -> config);
		Oski::app() -> resetModule();
		return true;
	}

}
class main_layout extends base_layout	{
	public function __construct($file = NULL)	{
		if (file_exists(LODIR.Oski::app() -> instance."/home.ini") && actp('module') == "") $this -> readLayout("home");
		else if (isset($file) && file_exists(LODIR.Oski::app() -> instance."/".$file.".ini"))	$this -> readLayout($file);
		else if (file_exists(LODIR.Oski::app() -> instance."/".actp('module').".ini")) $this -> readlayout(actp('module'));
		else $this -> readLayout("default");
	}

	public function openModules()	{
		foreach ($this -> config as $id => $args)	{
			if (defined("MVCS")) 		break;
			if ($id !== "layout"){
				if ($args['type'] == "current_module")  {
					$this -> complete = Oski::app() -> getActp("complete");
					$this -> module = Oski::app() -> getActp("module");
					$file = actp('complete');
					if (substr($file, strlen($file) - 1) === "/")
					$file = substr($file, 1, strlen($file) - 2);
					if (actp('complete') === "/")	$this -> initmodule("home", "main");
					else if (in_array(actp('module'), array("register", "login", "logout"))) $this -> initmodule("usrrel", "main");
					else if (actp('module') === "admin")  $this -> initmodule("admin", "main");
					else if (file_exists("oski_includes/resources/pages/".Oski::app() -> instance."/".$file.".md") || file_exists("oski_includes/resources/pages/".Oski::app() -> instance."/".$file.".html"))	$this -> initmodule("page", "main", $file);
					else if ($this -> initmodule(actp('module'), "main"));
					if (xcheck("quirks", "1", array($_GET, $_POST))) return true;
				}
				else if ($args['type'] == "layout") $this -> initmodule("layout", $id, $args);
				else if ($args['type'] == "page")	$this -> initmodule("page", $id, $args['content']);
				else if ($args['type'] == "module")	$this -> initmodule($args['content'], $id);}
		}
	}

	public function initModule($module, $identifier = NULL, $args = NULL)	{
		if (!isset($identifier))	$identifier = count(Oski::app() -> getModule('main'));
		if ($module === "home") Oski::app() -> setModule($identifier, new page_loader(array("title" => "Home Page", "page" => "home"), $identifier)) ;
		else if ($module === "page")	Oski::app() -> setModule($identifier, new page_loader(array("title" => ucwords($args), "instance_id" => $args, "page" => $args), $identifier));
		else if ($module === "usrrel")	Oski::app() -> setModule($identifier, new userRel());
		else if ($module === "admin")	Oski::app() -> setModule($identifier,  new admin_loader());
		else {
			if (file_exists(LODIR.$args['content'].".xml") || file_exists(LODIR.$args['content'].".ini")) {
				if ($args['layout_type'] == "nav") Oski::app() -> setModule($identifier,  new nav_layout($args['content']));
				else {
					Oski::app() -> setModule($identifier,  new main_layout($args['content']));
					Oski::app() -> getModule($identifier) -> openModules();
				}
			}
			else if (file_exists(substr(actp('complete'), 1, strlen(actp('complete')) - 2))) { Oski::app() -> setModule($identifier,  new folder_loader()); }
			else if (!file_exists(INSTDIR.Oski::app() -> instance."/".$module.'.ini'))	{ if ($identifier == "main") signal(404, "Main Module Not Found!"); else Oski::app() -> setModule($identifier,  new error_loader(404)); return false; }
			else {
				$modulecfg = new ini_reader(CFGDIR."modules/".Oski::app() -> instance."/".$module.".ini");
				$modulecfg = $modulecfg -> toDict();
				Oski::app() -> setModule($identifier,  new module_loader($modulecfg['ESENTIALS'], $identifier));
				if (isset($modulecfg['AUXILIARIES']))	Oski::app() -> getModule($identifier) -> loadAuxiliaries($modulecfg['AUXILIARIES']);
				if (isset($modulecfg['SUBINSTANCES']))	Oski::app() -> getModule($identifier) -> loadSubinstances($modulecfg['SUBINSTANCES']);
			}
		}
		return true;
	}

	public function getViews()	{
		$i = 1;
		foreach ($this -> config as $id => $args) if ($id !== "layout")	{
			if (!isset($args['container_type'])) $args['container_type'] = "";
			Oski::app() -> theme -> getPart($args['container'], $args['container_type']);
			if ($args['type'] == "current_module")	Oski::app() -> getModule('main') -> loadView();
			else if (!xcheck("quirks", "1", array($_GET, $_POST)) && Oski::app() -> getModule($i)) {
				if (get_class(Oski::app() -> getModule($i)) == "main_layout")		    Oski::app() -> getModule($i++) -> getViews();
				else if (get_class(Oski::app() -> getModule($i)) == "nav_layout")	    Oski::app() -> getModule($i++) -> getNav();
				else Oski::app() -> getModule($i++) -> loadView();
 			}
			Oski::app() -> theme -> getPart($args['container'], $args['container_type']);
		}
	}
}
class nav_layout extends base_layout	{
	public function readLayout($file) {
		$read = new xml_reader(LODIR.Oski::app() -> instance."/".$file.".xml");
		$this -> config = $read -> toArray();
	}

	public function __construct($file = NULL)	{
		if (isset($file) && file_exists(LODIR.Oski::app() -> instance."/".$file.".xml"))	$this -> readLayout($file);
		else $this -> nogo = 1;
	}
	public function getNav()	{
	    if (isset($this -> nogo)) return NULL;
		$this -> recNav($this -> config -> element);
	}

	public function recNav($el)	{
	    if (isset($this -> nogo)) return NULL;
		echo "<uL>";
		foreach ($el as $elem)	{
			$attr = $elem -> attributes();
			echo "<li id='".$elem -> attr['id']."'><a href='".linkTo($elem -> link, $elem -> type);
			if (isset($elem -> accesskey)) echo "' accesskey='".$elem -> accesskey."'";
			echo "'>".$elem -> name."</a>";
			if (count($elem -> element))  $this -> recNav($elem -> element);
			echo "</li>";
		}
		echo "</uL>";
	}
}
?>
