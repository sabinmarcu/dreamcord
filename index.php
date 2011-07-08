<!DOCTYPE HTML>
<html lang="ru-RU">
<head>
	<meta charset="UTF-8">
	<title>Amandla Engine Testing</title>
</head>
<body>
<?php
/**
 * The bootstrap file of the Amandla Engine Instalation.   				         								
 * It adds the engine to the include path and sets up basic autoload.
 *
 * @package Amandla.Bootstrap
 * @author Marcu Sabin
 */
	error_reporting(E_ALL);
	include "framework/bootstrap.php";
	Amandla::app() -> mojuba("framework/config/other");
        
	var_dump(
		Amandla::app(),
		config::db() -> getAll("site_config"),
		stringHelper::trimext("string.class.php.bak", 3), 
                $_SERVER,
                fileHelper::ensureFile(APPDIR."facebook/crap/fuckolla.ini")
	);
        fileHelper::trashDir(APPDIR."facebook");
	
?>
<BR><BR><BR><BR><BR><BR><BR><BR><HR><BR>
BOOTSTRAP SUCCESSFUL !!!
<BR><BR><HR><BR>
<?php Prototype::printHistory(); ?>

	</body>
	</html>