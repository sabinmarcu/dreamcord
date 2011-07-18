<?php
	set_include_path( BASEDIR. "" . PATH_SEPARATOR . get_include_path() );
	set_include_path( BASEDIR. "helpers" . PATH_SEPARATOR . get_include_path() );
	set_include_path( BASEDIR. "prototypes" . PATH_SEPARATOR . get_include_path() );
	set_include_path( BASEDIR. "factories" . PATH_SEPARATOR . get_include_path() );
	ini_set("auto_prepend_file", 1);
	ini_set("auto_append_file", 1);

    if (isset($_SERVER['PWD']))     DEFINE("APPDIR", $_SERVER['PWD']."/");
    else {
        $appdir = explode("/", $_SERVER['SCRIPT_FILENAME']);
        array_pop($appdir);
        define("APPDIR", implode("/", $appdir)."/");
        unset($appdir);
    }

    define("AMDLDIR", BASEDIR."amandla/");
	define("CFGDIR", BASEDIR."config/");
	define("PATDIR", BASEDIR."patterns/");
	define("PROTDIR", BASEDIR."prototypes/");
	define("FACTDIR", BASEDIR."factories/");
	define("RUNDIR", BASEDIR."runtime/");
	define("HLPDIR", BASEDIR."helpers/");
    define("SRVENV", stripos($_SERVER['SERVER_SOFTWARE'], "win") ? "win" : "unix");
?>