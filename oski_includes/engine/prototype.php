<?php
class Prototype{
	
	protected static $_classname = "";
	protected static $_history = array();
			
	protected function __construct($class = "Prototype"){
		$this -> _classname = $class;
		self::logEvent("Creating new object from Prototype", "success");
	}	
	
	protected static function logEvent()	{
		self::$_history[] = func_get_args();
	}
	
	public function getVar($var = NULL)	{
		if (!$var)	trigger_error("There can be no variable registered as NULL");
		return isset($this -> $var) ? $this -> $var : NULL;
	}
	
	public function setVar($var = NULL, $value = NULL)	{
		if (!$var)	trigger_error("The variable name cannot be NULL");
		$this -> $var = $value;
	}

	public static function printHistory($location = NULL)	{
		if ($location)	{
			$file = fopen($location, 'w'); fwrite($file, varTrans::getString(self::$_history)); fclose($file);
		}	else var_dump(self::$_history);
		self::logEvent("Printing of the current session's history.", "success");
	}
	
	public function name()	{
		return get_class($this);
	}
	
	public function __clone()	{
		
	}
}
?>