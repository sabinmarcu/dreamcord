<?php
class pagesController extends baseAdminController	{
	public function __construct()	{
			$this -> has_other = 1;
		parent::__construct();
		$this -> prefix = actp('module')."/".actp('panel');
		$this -> url = urldecode(str_replace($this -> prefix, "", actp('complete')));
		$this -> url = substr($this -> url, 1, strlen($this -> url) - 2);
		$this -> back = "/" . $this -> prefix . str_replace(end(explode("/", $this -> url)), "", $this -> url);
		$this -> file  = PAGESDIR.Oski::app() -> instance."/".$this -> url;
	}
	
	public function _index()	{
		$this -> _catchall();
	}
	
	public function _catchall()	{
		
		if (!file_exists($this -> file)) sysErr('404');
		else {
			if (is_dir($this -> file))	{
				if (count($_POST)){
					if (isset($_POST['name']))	{
						$dir = (substr($this -> file, 0, strlen($this -> file) - 1) == "/" ? $this -> file : $this -> file . "/");
						if ($_POST['what'] == "folder" && !file_exists($dir . $_POST['name']))			mkdir($dir . $_POST['name']);
						else if ($_POST['what'] == "file" && !file_exists($dir . $_POST['name'] . "." . $_POST['type']))		{ $file = fopen($dir . $_POST['name'] . "." . $_POST['type'], 'w'); fclose($file); }
					}	else if (isset($_POST['delete']))	{
						recRmDir($this -> file);
						redirect($this -> back);
					}
				}
				$dir = opendir($this -> file);
				$this -> dirs = $this -> files = array();
				while ($file = readdir($dir))	{
					if (is_dir($this -> file . "/" .$file) && strpos($file, ".") === false)	$this -> dirs[count($this -> dirs)] = $file;
					else if (in_array(ext($file), array("md", "html"))) $this -> files[count($this -> files)] = $file;
				}
				$this -> view = 'list';
			}	else	{       
				if (count($_POST))	{
					if (isset($_POST['content'])){
						$this -> content = $_POST['content'];
						$file = new file_reader($this -> file, 'w');
						$file -> writeFile($this -> content);
						unset($file);
					} else if (isset($_POST['delete']))	{
						unlink($this -> file);
						redirect($this -> back);
					}
				}	else  {
					$file = new file_reader($this -> file);
					$this -> content = $file -> toString();
				}
				$this -> view = 'edit';	
			}	
		}
	}
	
	public function getNewFileForm()	{
		getTPart("sidebox", "", "admin"); getTPart("title"); __e("Add a new File"); getTPart("title"); getTPart("content");
		$form = new formBuilder(actp('complete'), "POST", "newfileform");
		$form -> addInput("name");
		$form -> addLabel("name", __("The name of the File"));
		$form -> addInput("type", "", "", "", "", "select", array("Markdown" => "md", "HTML" => "html", "_selected" => "md"));
		$form -> addLabel("type", __("The type of the File"));
		$form -> addInput("what", "", "", "", "hidden", "input", "file");
		$form -> printForm();
		getTPart("content"); getTPart("sidebox", "", "admin");
	}
	
	public function getNewFolderForm()	{
		getTPart("sidebox", "", "admin"); getTPart("title"); __e("Create a new Folder"); getTPart("title"); getTPart("content");
		$form = new formBuilder(actp('complete'), "POST", "newfolderform");
		$form -> addInput("name");
		$form -> addLabel("name", __("The name of the Folder"));
		$form -> addInput("what", "", "", "", "hidden", "input", "folder");
		$form -> printForm();
		getTPart("content"); getTPart("sidebox", "", "admin");
	}
	public function getNotes()	{
		getTPart("sidebox bottomf right auto help"); getTPart("content");
		if (is_dir($this -> file)) : ?> 
		<ul>
			<li>Alt + #n : Starts editing on the file with the number #n from the list</li>
			<li>Alt + #a : Moves to the folder named #a</li>
			<br>
			<li>Control + D : Delete current Folder along with everything it contains (folders and files)</li>
			<li>Control + L : Focus on the New File Form</li>
			<li>Control + K : Focus on the New Folder Form</li>
			<li>Control + B : Moves to the previous Folder</li>
		</ul>
		<?php else : ?> 
		<ul>
			<li>Control + D : Delete current File</li>
			<li>Control + S : Save the document</li>
			<li>Control + B : Moves to the previous Folder</li>
		</ul>
		<?php endif;
		getTPart("content"); getTPart("sidebox");
	}
}
?>