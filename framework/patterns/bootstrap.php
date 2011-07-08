<?php
/**
 * This file loads the development patterns that will be applied throughout the application.
 * @param Prototype
 * @param Singleton
 * @param Factory
 * @package ProjectX.Patterns
 *
 * @author Marcu Sabin
 */
	$dir = dirname(__FILE__)."/";
	include $dir."prototype.php";
	include $dir."singleton.php";
	include $dir."factory.php";
?>