<?php

class baseController
{

	public $has_other = 0;
    public function __construct($data = NULL, $inst = NULL)	{
		if (isset($inst)) $this -> data = $inst;
    	if (isset($data['hasother']))	$this -> has_other = 1;
		$this -> actpprefix = (actp('module') == "admin" && !isset($this -> adminPanel) ? "_admin" : "");
		$this -> viewactpprefix = (actp('module') == "admin" && !isset($this -> adminPanel) ? ".admin" : "");
		$this -> actpaction = (actp('module') == "admin" && !isset($this -> adminPanel) ? (actp(6) ? actp(6) : "index") : actp('action'));
    }

    public function _index()	{
		/* code */
    }

    public function _catchall()	{
		/* code */
    }

    public function __install()	{
    	/* code */
    }

	public function getFunction()	{
		
		$str = "_".$this -> actpaction.$this -> actpprefix;
		if (!$this -> actpaction)	$this -> _index();
		else if (in_array("_".$this -> actpaction.$this -> actpprefix, get_class_methods($this)))	$this -> $str();
		else if ($this -> has_other)	$this -> _catchall();
		else sysErr(404, "Cannot Load Controller : " . get_class($this));
	}

	public function getView($id = NULL)	{
		 if (isset($id))	$this -> view = $id;
		$view = $this -> data['plugin_id'];
		$inst = $this -> data['instance_id'];
		$session = $this -> actpaction;
		if (isset($this ->  view) && file_exists("oski_content/modules/".$view."/views/".$this ->  view.$this -> viewactpprefix.".view.php"))		include "oski_content/modules/".$view."/views/".$this ->  view.$this -> viewactpprefix.".view.php";
		else if ($session && isset($this-> method) && method_exists($this -> $session))	include "oski_content/modules/".$view."/views/".$session.$this -> viewactpprefix.".view.php";
		else if (file_exists("oski_content/modules/".$view."/views/default".$this -> viewactpprefix.".view.php")) include "oski_content/modules/".$view."/views/default".$this -> viewactpprefix.".view.php";
		else server_error(404, "There is no view for this module.");
	}

}
class baseAdminController extends baseController
{
	public function __construct()	{
		
		$this -> adminPanel = 1;
		Oski::app() -> setProp("actp", Oski::app() -> getActp('action'), "panel");
		Oski::app() -> setProp("actp", (Oski::app() -> getActp("3") ? Oski::app() -> getActp(3) : ""), "action");
		parent::__construct();
	}

	public function getView($id = NULL)	{
			if (isset($id)) $this -> view = $id;
		$view = (actp('panel') ? actp('panel') : "index");
		$session = $this -> actpaction;
		if (isset($this ->  view))	{
			if (file_exists(ADMDIR."views/".$view."/".$this ->  view.".view.php")) include ADMDIR."views/".$view."/".$this ->  view.".view.php";
			else if ($session && method_exists($this, "_".$session) && file_exists(ADMDIR."views/".$view."/".$session.".".$this -> view.".view.php")) include ADMDIR."views/".$view."/".$session.".".$this -> view.".view.php";
		}
		else if ($session && method_exists($this, "_".$session) && file_exists(ADMDIR."views/".$view."/".$session.".view.php"))	 include  ADMDIR."views/".$view."/".$session.".view.php";
		else if (file_exists(ADMDIR."views/".$view."/default.view.php")) include ADMDIR."views/".$view."/default.view.php";
		else server_error(404, "There is no view for this module.");
	}
}

?>
