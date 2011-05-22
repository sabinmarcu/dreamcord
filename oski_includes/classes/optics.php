<?php 
	class extension_optics	{
		
		public function __construct($type = "", $name = "")	{
			$this -> type = $type;
			$this -> name = $name;
		}
		
		public function execAction($action)	{
			if ($action === "delete")	$this -> deleteExtension();
		}
		
		public function deleteExtension()	{
			recrmdir("oski_content/".$this -> type."s/".$this -> name);
		}
		
		public function executeInstallation()	{			
			echo "<h1>Starting the Instalation ...</h1>";
			echo "<p>Saving ...</p>";
			$this -> saveContainer();
			echo "<p>Unpacking ...</p>";
			$this -> extractContainer();
			echo "<p>Reading ...</p>";
			$this -> readIni();
			echo "<p>Installing ...</p>";
			$this -> installExtension();
			echo "<p>Cleaning ...</p>";
			$this -> cleanTemp();
			echo "<h2>Done! ...</h2>";
			exit;
		}
		
		private function saveContainer()	{
			$this -> name = basename($_FILES['file']['name']);
			$this -> name = str_ireplace(".".end(explode(".", $this -> name)), "", $this -> name);
			if(!move_uploaded_file($_FILES['file']['tmp_name'], "oski_uploads/extensions/".$this -> name.".iExt"))	{
   				announce("error", "Um ... problem ... ", array("The file could not be uploaded", "Sorry ..."));
   			}
		}
		
		private function extractContainer()	{
			$zip = new ZipArchive;
			if ($zip -> open("oski_uploads/extensions/".$this -> name.".iExt") === FALSE)	{
   				announce("error", "Um ... problem ... ", array("The file could not be opened", "Sorry ..."));
   				exit;
			}
			else {
				if (!$zip -> extractTo('oski_uploads/extensions/'.$this -> name."/"))			{	
   					announce("error", "Um ... problem ... ", array("The file could not be unpacked", "Sorry ..."));
   					exit;
   				}
			}
		}
		
		private function readIni()	{
			$this -> config = new ini_reader('oski_uploads/extensions/'.$this -> name.'/install.ini');
			$this -> config = $this -> config -> toDict();
		}
		
		private function installExtension()	{
			$path = "oski_content/";
			switch ($this -> config['type'])	{
				case "module": $path .= "modules/"; break;
				case "theme": $path .= "themes/"; break;
				case "plugin": $path .= "plugins/"; break;
			}
			$path = getToNoDuplicateDir($path.$this -> config['id']);
			recCpy("oski_uploads/extensions/".$this -> name."/contents", $path);
		}
		
		private function cleanTemp()	{
			recrmdir("oski_uploads/extensions/".$this -> name);
			unlink("oski_uploads/extensions/".$this -> name.".iExt");		
		}
		
		public function requestContainer()	{
			?> 
			<form action="/admin/extensions/install/" method="post" enctype="multipart/form-data" >
				<fieldset>
					<label for="file">Upload you iExtenstion file</label><input type="file" name="file" id="file" />
					<input type="submit" value="Continue &rarr;" />
				</fieldset>
			</form>
			<?php
		}
		
		public function listExtensions($extensions)	{
		$dir = opendir("oski_content/".$extensions);
		while ($file = readdir($dir))	if (is_dir("oski_content/".$extensions."/".$file) && strpos($file, ".") !== 0)	:	$extension = new ini_reader("oski_content/".$extensions."/".$file."/config.ini"); $extension = $extension -> toDict();	$hasscreen = 0; $this -> currext = $file;if ($extensions === "themes" && !isset($extension['is_reusable']))	continue;	?>
			<div class="theme">
				<h1>The <?php echo ucwords($file), " ", ucwords(substr($extensions, 0, strlen($extensions) - 1)) ?> </h1>
				<?php if ((isset($extension['screenshot']) && file_exists("oski_content/".$extensions."/".$file."/".$extension['screenshot'])) || file_exists("oski_content/".$extensions."/".$file."/screenshot.png")) { if (isset($extension['screenshot'])) $screen = $extension['screenshot']; else $screen = "screenshot.png"; $hasscreen = 1; ?>	<img src="/oski_content/<?php echo $extensions ?>/<?php echo $file ?>/<?php echo $screen ?>" alt="Theme Screenshot (<?php echo $file ?>)" class='screenshot'/>	<?php } ?>
				<?php if ((isset($extension['description']) && file_exists("oski_content/".$extensions."/".$file."/".$extension['description'])) || file_exists("oski_content/".$extensions."/".$file."/description.md")) { if (isset($extension['description'])) $descr = $extension['description']; else $descr = "description.md" ?>	<div <?php if ($hasscreen) { ?>class='description'<?php } ?>><?php $read = new file_reader("oski_content/".$extensions."/".$file."/".$descr); $read -> printMarkdown(); ?></div>	<?php } ?>
				<div class="delete"><a href="/admin/extensions/<?php echo $extensions ?>/delete/<?php echo $file; ?>/" ><img src="/oski_includes/apache_icons/portal.png" alt="Delete the Theme!" /></a></div>
				<?php if ($extensions === "themes") {?>
				<div class="edit"><a href="/admin/extensions/<?php echo $extensions ?>/activate/<?php echo $file ?>/"><img src="/oski_includes/apache_icons/comp.blue.png" alt="Set the theme as Default" /></a></div>
				<?php } else if ($extensions === "modules") { ?>
				<?php $this -> printInstances($this -> getInstances()); ?>
				<?php $this -> getInstantiator() ?>
				<?php } ?>
				<div class="clear"></div>
			</div>
		<?php endif; 
		}
		
	}
	
class theme_optics extends extension_optics	{


	public function __construct($name = "")	{
		$this -> name = $name;
		$this -> type = "theme";
	}
	
	public function execAction($action)	{
		parent::execAction($action);
		if ($action == "activate")	$this -> activateTheme();
	}
	
	public function activateTheme()	{
		$file = new ini_reader("oski_application/config.ini");
		$content = $file -> toDict();	$content['THEME_SETUP']['default_theme'] = $this -> name;
		$file -> openFile("oski_application/config.ini", 'w');	$file -> atoFile($content);	
		?> <meta http-equiv="refresh" content="0;url=/" /> <?php	
	}

	public function listThemes()	{
		$this -> listExtensions('themes');
	}
}

class module_optics extends extension_optics	{

	public function __construct($name = "")	{
		$this -> name = $name;
		$this -> type = "module";
	}

	public function listmodules()	{
		$this -> listExtensions('modules');
	}
	
	public function execAction($action)	{
		parent::execAction($action);
		if ($action == "add")	$this -> createInstance();
		if ($action == "deleteinstance")	$this -> deleteInstance();
	}
	
	public function getInstances()	{
		$prev = "oski_application/oski_instances";	$dir = opendir($prev);	$prev .= "/";
		$read = new ini_reader();	$instances = array();
		while ($file = readdir($dir))	{
			if (!is_dir($prev.$file))	{
				$read -> openFile($prev.$file);	$content = $read -> toDict();
				if ($content['ESENTIALS']['plugin_id'] === $this -> currext)	$instances[count($instances)] = str_ireplace(".".end(explode(".", $file)), "", $file);
			}
		}
		return $instances;
	}
	
	public function printInstances($data)	{
		if( count($data))	:
		echo "<ul>";
		foreach ($data as $instance)	echo "<li id='".$instance."'>".ucwords($instance)."<span class=\"right\"><a href=\"/admin/extensions/modules/deleteinstance/".$instance."/\">Delete this Instance</a></span>";
		echo "</ul>";
		endif;
	}
	
	
	public function getInstantiator()	{
		?> 
		<form action="/admin/extensions/modules/add/<?php echo $this -> currext ?>/" method = 'post' name = '<?php echo $this -> currext ?>'>
			<fieldset>
				<h2>Create a new Instance of this module</h2>
				<label for="instance_name">The name of the Instance</label><input type="text" name="instance_name" id="instance_name" placeholder='New Instance'/>				
				<label for="instance_id">The identifier (id) of the Instance</label><input type="text" name="instance_id" id="instance_id" placeholder='nwinst'/>	
				<input type="submit" value="Continue &rarr;" />
			</fieldset>
		</form>
		<?php
	}
	
	
	public function deleteExtension()	{
		recrmdir("oski_content/".$this -> type."s/".$this -> name);
	}
	
	public function createInstance($instance_name = NULL, $instance_id = NULL, $plugin_id = NULL, $silent = false)	{
			
		if (!isset($instance_name))	$instance_name = Oski::app() -> database -> escape($_POST['instance_name']);
		if (!isset($instance_id)) $instance_id = Oski::app() -> database -> escape($_POST['instance_id']);
		if (!isset($plugin_id)) $plugin_id = Oski::app() -> actp[5];
		$cfg = new ini_reader('oski_content/modules/'.$this -> name.'/install/install.ini');	$cfg = $cfg -> toDict();
		if (!file_exists("oski_uploads/".$plugin_id)) mkdir("oski_uploads/".$plugin_id);
		if (!file_exists("oski_uploads/".$plugin_id."/".$instance_id)) mkdir("oski_uploads/".$plugin_id."/".$instance_id);
		if (isset($cfg['requires_upload_folder']))	{
			$folders = explode(":", $cfg['upload_folders']);
			foreach ($folders as $dir)	{
				$dir = str_ireplace('$instance^', $instance_id, $dir);
				$dir = str_ireplace('$plugin^', $plugin_id, $dir);
				if (!file_exists("oski_uploads/".$plugin_id."/".$instance_id."/".$dir))	mkdir("oski_uploads/".$plugin_id."/".$instance_id."/".$dir);
			}
			unset($dir); unset($folders);
		}
		if (isset($cfg['requires_upload_files']))	{
			$files = explode(":", $cfg['upload_files']);
			foreach ($files as $file)	{
				$file = str_ireplace('$instance^', $instance_id, $file);
				$file = str_ireplace('$plugin^', $plugin_id, $file);
				if (!file_exists("oski_uploads/".$plugin_id."/".$instance_id."/".$file))	{ $write = new file_reader(); $write -> openFile("oski_uploads/".$plugin_id."/".$instance_id."/".$file, 'w'); $write -> writefile("Just Been Instantiated"); unset($write); }
			}
			unset($file);
		}
		if (!file_exists("oski_application/oski_instances/".$instance_id))	mkdir("oski_application/oski_instances/".$instance_id);
		$prev = "oski_content/modules/".$this -> name."/install";	$dir = opendir($prev);	$prev .= "/";
		while ($file = readdir($dir))	{
			if (!is_dir($prev.$file))	{
				if (stripos($file, "default_") === 0)	copy($prev.$file, "oski_application/oski_instances/".$instance_id."/".str_ireplace("default_", "", $file));
				if (stripos($file, "sql_") === 0)	{
					$sql = new ini_reader($prev.$file);	$sql = $sql -> toDict();
					if (!Oski::app() -> database -> createTable($instance_id . "_" .str_ireplace(".ini", "", str_ireplace("sql_", "", str_ireplace(".".end(explode(".", $file)), "", $file))), $sql['FIELDS'], $sql['EXTRAS']))
						announce("error", "Problems With the DB", array("It seems like we can't create the table !", "Reason : ".Oski::app() -> database -> errorRep()));
				}
			}
		}
		$file = new ini_reader();	$file -> openFile("oski_application/oski_instances/".$instance_id.".ini", 'w');
		$data['ESENTIALS']["instance_name"] = $instance_name;
		$data['ESENTIALS']["instance_id"] = $instance_id;
		$data['ESENTIALS']["plugin_id"] = $plugin_id;
		if (isset($cfg['subinstances']))	{
			for ($i = 1; $i <= $cfg['subinstances']; $i++)	{
				$subinstance = explode(":", $cfg['subinstance'.$i]);
				$data['SUBINSTANCES'][$subinstance[0]] = $subinstance[1];
			}
		}
		$file -> aToFile($data);
		unset($file); unset($data);
		
		$file = new file_reader(".htaccess");
		$content = $file -> toString();$content .= "\nRewriteRule ^".$instance_id."(.*)/$ /";
		$file -> openFile(".htaccess", 'w');	$file -> writeFile($content);
		unset($file); unset($content);
		if (isset($cfg['postinstall']))	include "oski_content/modules/".$plugin_id."/install/".$cfg['postinstall'];
		if (!$silent)	header("Location: /admin/extensions/modules/");
	}
	
	public function deleteInstance($instance_id = NULL, $silent = false)	{
			
		if (!isset($instance_id))	$instance_id = Oski::app() -> actp[5];
		$file = new ini_reader("oski_application/oski_instances/".$instance_id.".ini");	$file = $file -> toDict();
		$plugin_id = $file['ESENTIALS']['plugin_id'];
		$cfg = new ini_reader('oski_content/modules/'.$plugin_id.'/install/install.ini');	$cfg = $cfg -> toDict();
		if (file_exists("oski_uploads/".$plugin_id."/".$instance_id)) recrmdir("oski_uploads/".$plugin_id."/".$instance_id);
		if (file_exists("oski_application/oski_instances/".$instance_id)) recrmdir("oski_application/oski_instances/".$instance_id);
		$prev = "oski_content/modules/".$plugin_id."/install";	$dir = opendir($prev);	$prev .= "/";
		while ($file = readdir($dir))	if (!is_dir($prev.$file))	if (stripos($file, "sql_") === 0)	Oski::app() -> database -> dropTable($instance_id . "_" .str_ireplace(".ini", "", str_ireplace("sql_", "", str_ireplace(".".end(explode(".", $file)), "", $file))));
		unlink("oski_application/oski_instances/".$instance_id.".ini");
		$file = new file_reader(".htaccess");
		$content = $file -> toString();$content = str_replace("\nRewriteRule ^".$instance_id."(.*)/$ /", "", $content);
		$file -> openFile(".htaccess", 'w');	$file -> writeFile($content);
		unset($file); unset($content);
		if (!$silent)	header("Location: /admin/extensions/modules/");
	}

	
}
?>
