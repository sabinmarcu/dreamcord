<?php
	DEFINE("APP_ENV", "devel");
	include 'oski_application/oski_engine/oskiengine.class.php';
	if (Oski::app() -> signal === "gtg")	Oski::app() -> initialise() -> loadengine();
	else if (Oski::app() -> signal === "install")	{
		include 'oski_application/oski_engine/installer.class.php';
		$installer = new oski_engine_installer();
		$installer -> install();
	}
?>
