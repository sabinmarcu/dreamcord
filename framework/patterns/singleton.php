<?php 
/**
 * The Singleton Object retains any other Singleton Objects, aids in their creation
 * and maintenance.
 *
 * @package ProjectX.Patterns
 * @author Marcu Sabin
 */
class Singleton extends Prototype
{
	/**
	 * The instances variable holds a list of all currently loaded Singleton Objects.
	 *
	 * @var string $_instances
	 **/
	private static $_instances = array();
	/**
	 * Returns the Singleton Object requested. If the Object does not exist, it will
  * be created.
	 *
	 * @param string $c Singleton Object to be retrieved / created
	 * @return mixed a Singleton Object
	 * @author Marcu Sabin
	 */
	public static function obj($c = NULL)	{
		$c = isset($c) ? $c : __CLASS__;
		if ( !array_key_exists($c, self::$_instances) )
            self::$_instances[$c] = new $c($c);
		return self::$_instances[$c];
	}

	public function __construct()	{		
		parent::__construct();
		self::logEvent("Creating new Singleton object : " . $this -> _classname, "success");
	}

	public function __clone(){
                trigger_error('Clone is not allowed.', E_USER_ERROR);
        }

}
?>