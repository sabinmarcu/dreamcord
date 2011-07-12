<?php

	error_reporting(E_ALL);
	include "framework/bootstrap.php";
	Amandla::app();
        ?>
<!DOCTYPE HTML>
<html lang="ru-RU">
<head>
	<meta charset="UTF-8">
    <title><?php echo config::db() -> findProp("site_info", "site_title"), " | ", config::db() -> findProp("site_info", "site_tagline"); ?></title>
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
	var_dump(
                "SomeText",
                Amandla::trigger("mojuba")
	);
        fileHelper::trashDir(APPDIR."facebook");


?>
<BR><BR><BR><BR><BR><BR><BR><BR><HR><BR>
BOOTSTRAP SUCCESSFUL !!!
<BR><BR><HR><BR>
<?php Prototype::printHistory(); ?>

	</body>
	</html>