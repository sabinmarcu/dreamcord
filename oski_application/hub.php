<?php
	include 'oski_engine/oskiengine.class.php';
	$oski = Oski::app();
	if (Oski::app() -> signal === "gtg")	{
		Oski::app() -> initialise();
		Oski::app() -> loadengine();
	}	else if (Oski::app() -> signal === "install")	{
		include 'oski_engine/installer.class.php';
		$installer = new oski_engine_installer();
		$installer -> install();
	}
?>
