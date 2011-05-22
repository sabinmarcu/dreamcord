<?php
class extensionsController extends baseAdminController	{
	
	public function _index()	{
		$this -> _modules();
		$this -> _components();
		$this -> view = "default";
	}

	public function _install()	{
		$this -> view = "install";
		if (count($_FILES)) {
			$this -> result['go'] = 1;
			$this -> result['reason'] = "";
			$this -> saveContainer();
			if ($this -> result['go'])	$this -> extractContainer();
			if ($this -> result['go'])	$this -> readConfiguration();
			if ($this -> result['go'])	$this -> installExtension();
			if ($this -> result['go'])	$this -> installLanguages();
			if ($this -> result['go'])	$this -> updateCache();
			if ($this -> result['go'])	$this -> cleanTemp();
		}	
	}
	
	public function _components()	{		
		$this -> file = new xml_reader(CACHEDIR."components.xml");
		$this -> cache = $this -> file -> toArray();
		$this -> view = "components";
		if (count($_POST))	{
			if (isset($_POST['component']))	{
				foreach($this -> cache as $ch) if ($ch -> name == $_POST['component']) $ch -> activated = $_POST['active'];
				$this -> file = new xml_reader(CACHEDIR."components.xml", 'w');
				$this -> file -> aToFile($this -> cache);
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
	
	public function _modules()	{
		$this -> getModules();
    	if ( actp(4) )  {
    		foreach($this -> mod as $this -> module) 	if ($this -> module -> id == actp(4)) break; 
    		foreach($this -> cfg as $this -> instModule)	if ($this -> instModule -> id == actp(4)) 	break;
     	 	$this -> view = 'visualize';
     	 	if (actp(5)) $this -> getModuleAdminPanel();     
     	 	if (count($_GET))	{
     	 		if (isset($_GET['addInstance'])) $this -> addModuleInstance();	
     	 		else if (isset($_GET['removeInstance'])) $this -> removeModuleInstance();
     	 	}	 	
   	 	}
   	 	else {	
    		if (count($_GET) && isset($_GET['removeModule'])) 	$this -> removeModule();
    	}
	}
  	private function getModules() {  
		
	 	 $this -> mod = CACHEDIR."modules.xml";
	 	 $this -> mod = new xml_reader($this -> mod);
	 	 $this -> mod = $this -> mod -> toArray();
	 	 $this -> cfg = CACHEDIR.Oski::app() -> instance."/modules.xml";
	 	 $this -> cfg = new xml_reader($this -> cfg);
	 	 $this -> cfg = $this -> cfg -> toArray();
  	}
  	private function removeModule()	{
  		for ($i = 0; $i <= count($this -> cfg -> module) - 1; $i++)	{
  			if ($this -> cfg -> module[$i] -> id == $_GET['removeModule']) 
  				unset($this -> cfg -> module[$i]);
  		}
  		$this -> saveModuleCache();
  	}
  	private function saveModuleCache()	{
		
	 	 $file = (file_exists(CACHEDIR.Oski::app() -> instance."/modules.xml") ? CACHEDIR.Oski::app() -> instance."/modules.xml" : CACHEDIR."modules.xml");
	 	 $file = new xml_reader($file, 'w');
	 	 $file -> aToFile($this -> cfg);
  	}
  
  	private function getModuleAdminPanel()	{
  		
  		Oski::app() -> libInc("oski_content/modules/".actp(4)."/controllers");
  			$file = new ini_reader(INSTDIR.Oski::app() -> instance."/".actp(5).".ini"); $file = $file -> toDict();
  			$class = actp(4)."Controller";
  			$this -> controller = new $class(array(), $file['ESENTIALS']);
  			$this -> controller -> getFunction();
  			$this -> view = "admin";
  	}
  	private function addModuleInstance()	{
  		
  		$file = new xml_reader(CACHEDIR.Oski::app() -> instance . "/modules.xml");
  		$file = $file -> toArray();
  		for ($i = 0; $i <= count($file -> module); $i++)
  			if ($file -> module[$i] -> id == actp(4))	{ if (!isset($this -> instances)) $file -> module[$i] -> addchild("instances");	$file -> module[$i] -> instances -> addChild("instance", $_GET['addInstance']); break; }
  		$file2 = new xml_reader(CACHEDIR.Oski::app() -> instance . "/modules.xml", 'w');
  		$file2 -> aToFile($file);
  		unset($file2);
  		$file = array("ESENTIALS" => array("instance_id" => $_GET['addInstance'], "instance_name" => $_GET['addInstance'], "plugin_id" => actp(4)));
  		$file2 = new ini_reader(INSTDIR.Oski::app() -> instance . "/" . $_GET['addInstance'].".ini", 'w');
  		$file2 -> aToFile($file);
  		unset($file2);
  		
  		Oski::app() -> libInc("oski_content/modules/".actp(4)."/controllers");
  		$class = actp(4)."Controller";
  		$this -> controller = new $class(array(), $file['ESENTIALS']);
  		$this -> controller -> __install(); 
  		redirect(actp('complete'));		
  	}
  	
  	private function removeModuleInstance()	{
  		
  		var_dump(file_exists(INSTDIR.Oski::app() -> instance . "/" . $_GET['removeInstance'].".ini"));
  		$file = new xml_reader(CACHEDIR.Oski::app() -> instance."/modules.xml");
  		$file = $file -> toArray();
  		for ($i = 0; $i <= count($file -> module) - 1; $i++)
  			if ($file -> module[$i] -> id == actp(4))	{ 
  				for($j = 0; $j <= count($file -> module[$i] -> instances -> instance) - 1; $j++)
  					if ($file -> module[$i] -> instances -> instance[$j] == $_GET['removeInstance']) unset($file -> module[$i] -> instances -> instance[$j]);
  			}
  		unlink(INSTDIR.Oski::app() -> instance . "/" . $_GET['removeInstance'].".ini");
  		$file2 = new xml_reader(CACHEDIR.Oski::app() -> instance . "/modules.xml", 'w');
  		$file2 -> aToFile($file);
  	}
	
  /* TODO Instantiate, remove Instance */
	
	
	
	
	
	
	
	
	
	
	
	public function __destruct()	{
	}	
	
	private function saveContainer()	{
		$this -> name = basename($_FILES['oextfile']['name']);
		$this -> name = str_ireplace(".".end(explode(".", $this -> name)), "", $this -> name);
		if (!move_uploaded_file($_FILES['oextfile']['tmp_name'], "oski_uploads/extensions/".$this -> name.".oext"))	{
			$this -> result['go'] = 0;
			$this -> result['reason'] = "Cannot copy file!";
		}
	}
	private function extractContainer()	{
		$zip = new ZipArchive;
		if ($zip -> open("oski_uploads/extensions/".$this -> name.".oext") === FALSE)	{
			$this -> result['go'] = 0;
			$this -> result['reason'] = "Cannot open archive install file!";
		}
		else {
			if (!$zip -> extractTo('oski_uploads/extensions/'.$this -> name."/"))			{	   				
				$this -> result['go'] = 0;
				$this -> result['reason'] = "Cannot extract the installation!";
   			}
		}
	}
	private function readConfiguration()	{		
		$this -> config = new ini_reader('oski_uploads/extensions/'.$this -> name.'/Install.ini');
		$this -> config = $this -> config -> toDict();
	}
	private function installExtension()	{
		$path = "oski_content/".$this -> config['type']."s/";
		$path = getToNoDuplicateDir($path.$this -> name);
		recCpy("oski_uploads/extensions/".$this -> name."/Copy", $path);
	}
	private function installLanguages()	{
		$dir = "oski_uploads/extensions/".$this -> name."/Install/Languages";
		$read = opendir($dir);
		while ($file = readdir($read))
			if (ext($file) == "lg")	{
				$tfile = new file_reader($dir."/".$file);
				$content = $tfile -> toString();
				$tfile -> openFile("oski_application/oski_config/cache/languages/". $file, 'r');
				$content = $tfile -> toString() . $content;
				$tfile -> openFile("oski_application/oski_config/cache/languages/". $file, 'w');
				$tfile -> writeFile($content);
				unset($file);
			}
	}
	private function updateCache()	{
		$xml = new xml_reader("oski_application/oski_config/cache/".$this -> config['type']."s.xml");
		$dom = $xml -> toArray();
		$dome = $dom -> component;
		$dome[count($dome)] -> name = $this -> name;
		$dome[count($dome) - 1] -> activated = 0;
		var_dump($dom);
		$xml -> openFile("oski_application/oski_config/cache/".$this -> config['type']."s.xml", 'w');
		$xml -> aToFile($dom);
	}
	private function cleanTemp()	{
		recRmdir("oski_uploads/extensions/".$this -> name);
		unlink("oski_uploads/extensions/".$this -> name.".oext");
	}
}
?>