<?php
/**
 * This is the main Amandla Engine Bootstrap object.
 *
 * @package ProjectX.MainApplication
 * @author Marcu Sabin
 **/
class Amandla extends Singleton
{
    private $_instance = NULL;
	/**
	 * A shorthand to aquire the Singleton Object.
	 *
	 * @return Amandla The Singleton Object
	 * @author Marcu Sabin
	 */
	public static function app()	{
		return parent::obj(__CLASS__);
 	}	
	/**
	 * The autoload handler to be used when requesting a new event / plugin / helper
  	 * / etc
	 *
	 * @author Marcu Sabin
	 */
	function libInc($class)	{
		$class = strtolower($class);
		$paths = explode(PATH_SEPARATOR, get_include_path());	$found = false;
		foreach($paths as $p) {
		  		$fullname = $p.DIRECTORY_SEPARATOR.$class.".php";
		  		if(is_file($fullname)) {  $found = $fullname; break; }
			if ($found)	break;
		}
		if ( $found )	include $found;
		else 	{			
			/**
			*	@TODO : Do something when the file cannot be included. Aka, logging.
			*/
		}
	}
	/**
	 * When the application is launched, the libinc function specified earlier is
  	 * registered as an autoload function.
	 *
	 * @author Marcu Sabin
	 */
	public function __construct()	{
		parent::__construct();
		spl_autoload_register('Amandla::libInc');	
                config::db() -> init($this -> _instance);	
	}
        public function mojuba($instance)    {
            $this -> _instance = $instance;
            config::db() -> init($this -> _instance);
        }
} // END class 
?>