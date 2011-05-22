<?php
class doclistController extends baseController	{
	public function __construct($data = NULL, $inst = NULL)	{
		
		$this -> has_other = 1;
		parent::__construct($data, $inst);
		$file = actp('complete');	$file = substr($file, 0, strlen($file) - 1);
		$this -> prefix = ULDIR.Oski::app() -> instance."/".$this -> data['plugin_id'].$file;
		if (!file_exists(ULDIR.Oski::app() -> instance."/".$this -> data['plugin_id'].$file))	sysErr('404');
		$this -> file = ULDIR.Oski::app() -> instance."/".$this -> data['plugin_id'].$file;
	}
	public function _index()	{
		$this -> _catchall();
	}
	public function _catchall()	{
		
		if (file_exists($this -> file))
			if (is_dir($this -> file))	{
				$this -> getDir($this -> file);
				$this -> name = end(explode("/", $this -> file));
				$this -> prevname = str_replace("/".$this -> name, "", actp('complete'));
				$this -> view = "list";
			}	else 	{	
				$this -> name = end(explode("/", $this -> file));
				$this -> dir = str_replace("/".$this -> name, "", $this -> file);
				$this -> getDir($this -> dir);
				$this -> dir = str_replace("/".$this -> name."/", "", actp('complete'));
				if (isset($this -> indexes[$this -> name]))
					if (isset($this -> files[$this -> indexes[$this -> name]])){
							$this -> prev = $this -> next = $this -> dir . "/";
							if (isset($this -> files[$this -> indexes[$this -> name] - 1]))
								$this -> prev .= $this -> files[$this -> indexes[$this -> name] - 1];
							if (isset($this -> files[$this -> indexes[$this -> name] + 1]))
								$this -> next .= $this -> files[$this -> indexes[$this -> name] + 1];
							$this -> nextn = end(explode("/", $this -> next));
							$this -> prevn = end(explode("/", $this -> prev));
							$this -> prevf = ULDIR.Oski::app() -> instance . "/" . $this -> data['plugin_id'] . $this -> prev;
							$this -> nextf = ULDIR.Oski::app() -> instance . "/" . $this -> data['plugin_id'] . $this -> next;
						}
						else {
							$this -> prev = $this -> prevf = "#";
							$this -> next = $this -> nextf = "#";
						}
				$this -> view = "file";
			}
	}
	private function getDir($dir)	{
		$this -> files = array();
		$read = opendir($dir);
		while ($file = readdir($read))	{
			$this -> files[count($this -> files)] = $file;
			$this -> indexes[$file] = count($this -> files) - 1;
		}
	}
	public function stripName($string)	{
		return str_replace(".".ext($string), "", $string);
	}
}
?>