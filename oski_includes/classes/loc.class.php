<?php
class localizer	{
	public function __construct($file = NULL)	{
		if (!isset($file)) return false;
		if (file_exists(CACHEDIR."languages/".$file.".lg")) $file = CACHEDIR."languages/".$file.".lg";
		else if (file_exists(LGDIR.$file.".lg")) $file = LGDIR.$file.".lg";
		else return false;
		
			$this -> parser = new lgParser($file);
			$this -> parser -> parseFile();
	}
	public function match($string)	{
		if (isset($this -> parser -> db[$string])) return $this -> parser -> db[$string];
		else return $string;
	}
}
?>