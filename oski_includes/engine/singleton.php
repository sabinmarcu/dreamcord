<?php 
/**
 * undocumented class
 *
 * @package Oski.LowLevel
 * @author Marcu Sabin
 */
class Singleton{
	private static $_instance;
	
	public static function obj($c = NULL)	{	
		$c = isset($c) ? $c : __CLASS__;
		if ( !isset(self::$_instance) )	self::$_instance = new $c();		
		return self::$_instance;
	}
		
	public function __construct(){
		
	}
	
    public function __clone(){
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
}
?>