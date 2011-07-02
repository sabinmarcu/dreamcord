<?php
/**
 * undocumented class
 *
 * @package default
 * @author Marcu Sabin
 **/
class varTrans extends Singleton	
{
	public static function getString($variable = NULL)	{
		if (!$variable)	throw_exception("Cannot generate string representation of NULL");
		else if (is_string($variable)) 	return $variable;
		else if (is_array($variable))	return self::arrayToString($variable);
	}
	
	protected static function arrayToString($array, $tabs = "")	{
		$string = "";
		foreach ($array as $key => $value)	{
			if ($key === $value)	$string .= $tabs . $value;
			else if (is_string($value))	$string .= $tabs . $key . " => " . $value;
			else $string .= $tabs . $key . " => \n".self::arrayToString($value, $tabs."\t"); 
			$string .= "\n";
		}
		return $string;
	}
} // END class 
?>