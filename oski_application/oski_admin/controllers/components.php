<?php
class componentsController extends baseAdminController{
	public function __construct()	{
		
		parent::__construct();
		$this -> file = new xml_reader(CACHEDIR."components.xml");
		$this -> cache = $this -> file -> toArray();
	}
	public function _index()	{
		if (count($_POST))	{
			if (isset($_POST['component']))	{
				foreach($this -> cache as $ch) if ($ch -> name == $_POST['component']) $ch -> activated = $_POST['active'];
			}	else if (isset($_POST['delete'])) {
				foreach($this -> cache as $ch) if ($ch -> name == $_POST['delete']) {
					$dom = dom_import_simplexml($ch);
					$dom -> parentNode -> removeChild($dom);
				}
				$this -> cache = new SimpleXMLELement($this -> cache -> asXml());
				$dir = COMPDIR.$_POST['delete'];
				recRmDir($dir);
			}
		}
	}
	
	public function __destruct()	{
		$this -> file = new xml_reader(CACHEDIR."components.xml", 'w');
		$this -> file -> aToFile($this -> cache);
	}
}
?>