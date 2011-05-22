<?php
	DEFINE("CFGDIR", "oski_application/oski_config/");
	DEFINE("INSTDIR", CFGDIR."modules/");
	DEFINE("LODIR", CFGDIR."layouts/");
	DEFINE("ENGNDIR", CFGDIR."engines/");
	DEFINE("CACHEDIR", CFGDIR."cache/");
	
	DEFINE("LGDIR", "oski_includes/languages/");
	DEFINE("ULDIR", "oski_uploads/");
	DEFINE("LOGDIR", "oski_application/oski_logs/");
	
	DEFINE("BACKURL", (isset($_SERVER['REDIRECT_URL'])? $_SERVER['REDIRECT_URL'] : ""));
	
	define("PAGESDIR", "oski_includes/resources/pages/");
	DEFINE("FAVICONDIR", "oski_includes/resources/favicons/");
	DEFINE("COMPDIR", "oski_content/components/");
	
	DEFINE("APPNAME", "oskiengine");
	DEFINE("FULLAPPNAME", "Oski Engine");
	DEFINE("DEVELTEAM", "OctFour");
	DEFINE("DEVELTEAMWEB", "http://oskiengine.co.cc");
	DEFINE("DEVELTEAMMAIL", "http://sabinmarcu@gmail.com");
	DEFINE("AUTHOR", "Sabin Marcu");
	DEFINE("AUTHORWEB", "http://sabinmarcu.com/");
	DEFINE("AUTHORMAIL", "sabinmarcu@gmail.com");
	DEFINE("APPVER", "v0.5");
	
	
	date_default_timezone_set("Europe/Bucharest");
?>