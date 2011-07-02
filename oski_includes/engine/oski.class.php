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
	public function __construct()	{
		parent::__construct();
	}
} // END class 
?>