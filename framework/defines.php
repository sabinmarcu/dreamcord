<?php
	set_include_path( BASEDIR. "" . PATH_SEPARATOR . get_include_path() );
	set_include_path( BASEDIR. "helpers" . PATH_SEPARATOR . get_include_path() );
	set_include_path( BASEDIR. "prototypes" . PATH_SEPARATOR . get_include_path() );
	set_include_path( BASEDIR. "factories" . PATH_SEPARATOR . get_include_path() );
	
	// var_dump(get_include_path()); die();
	
        define("APPDIR", $_SERVER['DOCUMENT_ROOT']."/");
	define("AMDLDIR", BASEDIR."amandla/");
	define("CFGDIR", BASEDIR."config/");
	define("PATDIR", BASEDIR."patterns/");
	define("PROTDIR", BASEDIR."prototypes/");
	define("FACTDIR", BASEDIR."factories/");
	define("RUNDIR", BASEDIR."runtime/");	
	define("HLPDIR", BASEDIR."helpers/");
?>