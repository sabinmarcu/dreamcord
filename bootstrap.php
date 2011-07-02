<?php
/**
 * The bootstrap file of the Oski Engine Instalation.   				         								
 * It adds the engine to the include path and sets up basic autoload.
 *
 * @package Oski.Bootstrap
 * @author Marcu Sabin
 */

	error_reporting(E_ALL);
	define("OEDIR", dirname(__FILE__));

	set_include_path( OEDIR. "/oski_includes" . PATH_SEPARATOR . OEDIR. "/oski_includes/engine" . PATH_SEPARATOR . OEDIR. "/oski_includes/events" . PATH_SEPARATOR . get_include_path() );
	function __oskiengine_autoload($class)	{
		$class = strtolower($class);
		$files = array($class.".php", $class.".class.php", $class.".event.php", $class.".event.php");
		$paths = explode(PATH_SEPARATOR, get_include_path());	$found = false;
		echo "Attepting to include {$class}<br>";
		foreach($paths as $p) {
			foreach($files as $find)	{
		  		$fullname = $p.DIRECTORY_SEPARATOR.$find;
		  		if(is_file($fullname)) {  $found = $fullname; break; }
			}
			if ($found)	break;
		}
		if ( $found )	{	
					
			include $found;
						
		}	else 	{			
			/**
			*	@TODO : Do something when the file cannot be included. Aka, logging.
			*/
			echo "Cannot include {$class}.class.php";
		}
	}	
	spl_autoload_register('__oskiengine_autoload');
	
	
	var_dump(Oski::app());
	
?>