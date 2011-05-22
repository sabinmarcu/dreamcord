<?php
class galleryController extends baseController	{
	public function _index()	{
		
		$this -> elem = array();
		$folder = ULDIR.Oski::app() -> instance."/".$this -> data['plugin_id']."/".$this -> data['instance_id'];
		$dir = opendir($folder);
		while ($file = readdir($dir))	{
			$ext = end(explode(".", $file));
			if ($ext == "flv") $this -> elem[count($this -> elem)] = $file;
		}
	}
}
?>