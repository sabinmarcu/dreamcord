<?php
/**
 * This is the main Oski Engine Bootstrap object.
 *
 * @package Oski.MainApplication
 * @author Marcu Sabin
 **/
class Oski extends Singleton
{
	/**
	 * A shorthand to aquire the Singleton Object.
	 *
	 * @return Oski The Singleton Object
	 * @author Marcu Sabin
	 */
	public static function app()	{
		return parent::obj(__CLASS__);
 	}
	public function __construct()	{
		parent::__construct();
		config::db() ->init();
	}
} // END class 
?>