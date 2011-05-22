<?php
class engineController extends baseAdminController{


	public function __construct()	{
		parent::__construct();
		$this -> getCurrentEngineHierarchy();
		$this -> adminSection = "Engine Configuration Home";
		Oski::app() -> setTitle("Engine Configuration Home");
	}

	public function _edit()	{
		$engine = secure($_GET['engine']);
		$this -> confign = $this -> seekEngine($this -> engines, $engine);
		$file = new xml_reader(CACHEDIR."themes.xml");
		$this -> themes = $file -> toArray();
		$file = new ini_reader(ENGNDIR.$this -> confign -> config.".ini");
		$this -> config = $file -> toDict();
		if (count($_POST))	{
			$this -> edit = 1;
			foreach($_POST as $key => $value) if ($key != "section")	$this -> config[$_POST['section']][$key] = $value;
			$file -> openFile(ENGNDIR.$this -> confign -> config.".ini", 'w');
			$file -> aToFile($this -> config);
		}
	}

	public function _add()	{
		if (!count($_POST)) sysErr(404, "I have nothing to add!");
		else {
			$file = new ini_reader(ENGNDIR."default.ini");
			$read = $file -> toDict();
			$read['CHILDREN'][$_POST['domain']] = $_POST['config'];
			$file -> openFile(ENGNDIR."default.ini", 'w');
			$file -> aToFile($read);
			$neweng = $this -> engines -> addChild('engine');
			$neweng -> addChild("domain", $_POST['domain']);
			$neweng -> addChild("config", $_POST['config']);
			$xml = new xml_reader(CACHEDIR."engines.xml", 'w');
			$xml -> aToFile($this -> engines);
			if (!file_exists(ENGNDIR.$_POST['config'].".ini"))	{
				$file -> openFile(ENGNDIR.$this -> engines -> config.".ini");
				$read = $file -> toDict();
				$read['SITE_SETUP']['domain'] = $_POST['domain'];
				$read['CHILDREN'] = array();
				$file -> openFile(ENGNDIR.$_POST['config'].".ini", 'w');
				$file -> aToFile($read);
				mkdir(LODIR.$_POST['config']);
				mkdir(INSTDIR.$_POST['config']);
				mkdir(ULDIR.$_POST['config']);
			}
		}
	}

	public function _remove()	{
		$this -> remove = (count($_POST) ? 0 : 1);
		if (count($_POST) && isset($_POST['remove']))	{
			$this -> recRemoveEngine($this -> engines, $_GET['engine']);
			var_dumP($this -> engines, $_GET['engine']);
			$file = new xml_reader(CACHEDIR."engines.xml", 'w');
			$file -> aToFile($this -> engines); unset($file);
		}
	}

	private function getCurrentEngineHierarchy()	{

		$file = new xml_reader(CACHEDIR."engines.xml");
		$file = $file -> toArray();
		$this -> engines = $this -> seekEngine($file, Oski::app() -> getProp("site_info", "domain"));
		if (!$this -> engines) $this -> engines = $this -> seekEngine($file, Oski::app() -> getProp("site_info", "root"));
	}

	private function seekEngine($data, $cmp)	{
		if ($data -> domain == $cmp)  return $data;
		foreach($data as $engine)	{
			if ($engine -> domain == $cmp)  { return $engine;  }
			else if (count($engine -> engine)) { $res = $this -> seekEngine($engine, $cmp); if ($res) return $res; }
		}
		return 0;
	}

	private function recRemoveEngine(&$data, $cmp)	{
		for($i = 0; $i <= count($data -> engine); $i++)	{
			if (count($data -> engine[$i] -> engine)) $this -> recRemoveEngine($data -> engine[$i], $cmp);
			if ($data -> engine[$i] -> domain == $cmp) { var_dump($data -> engine); unset($data -> engine[$i]);}
		}
	}

	public function listEngines($data)	{
		 $domain = (isset($data -> config) ? $data -> domain : Oski::app() -> getProp("site_info", "domain"));
		?>
		<ul>
		<li><a href="<?php echo linkTo("admin/engine/edit"), "?engine=".$domain; ?>"><?php echo $domain ?></a><span class="right"><a href="<?php echo linkTo("admin/engine/remove"), "?engine=".$domain ?>">Remove</a></span></li>
			<?php if($data) $this -> listCrawlEngines($data) ?>
		</ul>
		<?php
	}

	public function listCrawlEngines($data)	{
			?>
			<ul>
				<?php foreach($data as $engine) if (isset($engine -> domain)): ?>
				<li><a href="<?php echo linkTo("admin/engine/edit"), "?engine=".$engine -> domain; ?>"><?php echo $engine -> domain ?></a><span class="right"><a href="<?php echo linkTo("admin/engine/remove"), "?engine=".$engine -> domain; ?>">Remove</a></span></li>
				<?php if (count($engine -> engine)) $this -> listCrawlEngines($engine); ?>
				<?php endif; ?>
			</ul>
			<?php
	}


}
?>