<?php
/**
 * undocumented class
 *
 * @package Oski.MainApplication
 * @author Marcu Sabin
 **/
class Oski extends Singleton
{
	public static function app()	{
		return parent::obj(__CLASS__);
 	}
} // END class 
class Bogus extends Singleton{	
	public static function obj()	{
		return parent::obj(__CLASS__);
 	}
}
?>