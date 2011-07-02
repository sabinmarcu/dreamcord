<!DOCTYPE HTML>
<html lang="ru-RU">
<head>
	<meta charset="UTF-8">
	<title>Oski Engine Testing</title>
</head>
<body>
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

	set_include_path( OEDIR. "/oski_includes" . PATH_SEPARATOR . OEDIR. "/oski_includes/engine" . PATH_SEPARATOR . OEDIR. "/oski_includes/helpers" . PATH_SEPARATOR . OEDIR. "/oski_includes/prototypes" . PATH_SEPARATOR . OEDIR. "/oski_includes/factories" . PATH_SEPARATOR . get_include_path() );
	function __oskiengine_autoload($class)	{
		$class = strtolower($class);
		$files = array("", ".class", ".event", ".event", ".helper", ".factory");
		$paths = explode(PATH_SEPARATOR, get_include_path());	$found = false;
		foreach($paths as $p) {
			foreach($files as $ext)	{
		  		$fullname = $p.DIRECTORY_SEPARATOR.$class.$ext.".php";
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
	
	Oski::app() -> setVar("Bogus", 1);
	
	
	var_dump(
		Oski::app(),
		Bogus::obj(),
		dataManagerConstructor::getDataManager("mysql")
	);
	
?>
<BR><BR><BR><BR><BR><BR><BR><BR><HR><BR>
BOOTSTRAP SUCCESSFUL !!!
<BR><BR><HR><BR>
<?php Prototype::printHistory(); ?>

	</body>
	</html>