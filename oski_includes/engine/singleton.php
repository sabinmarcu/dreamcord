<?php 
/**
 * undocumented class
 *
 * @package Oski.LowLevel
 * @author Marcu Sabin
 */
class Singleton extends Prototype
{
	private static $_instances = array();
	
	public static function obj($c = NULL)	{	
		$c = isset($c) ? $c : __CLASS__;
		if ( !array_key_exists($c, self::$_instances) )	self::$_instances[$c] = new $c($c);		
		return self::$_instances[$c];
	}

	public function __construct($class)	{
		parent::__construct($class);
		self::logEvent("Creating new Singleton object : " . $this -> _classname, "success");
	}

	public function __clone(){
       trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
}
?>