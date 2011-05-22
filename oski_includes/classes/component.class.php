<?php
class baseComponent	{
	public function __construct()	{
		
	}
	public function getFunction($function = NULL)	{
		if (method_exists($this, $function))	$this -> $function();
	}
}
?>