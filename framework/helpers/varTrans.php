<?php
/**
 * A simple helper class to facilitate the transformation of a data type into another.
 *
 * @package ProjectX.Helpers.Basic
 * @author Marcu Sabin
 **/
class varTrans extends Singleton	
{
	/**
	 * Attempts a string representation of a string value.
	 *
	 * @param mixed An input value. 
	 * @return string The string representation of the value input.
	 * @author Marcu Sabin
	 */
	public static function getString($variable = NULL)	{
		if (!$variable)	throw_exception("Cannot generate string representation of NULL");
		else if (is_string($variable)) 	return $variable;
		else if (is_array($variable))	return self::arrayToString($variable);
	}
	/**
	 * A specialized function that returns the string representation of an array.
	 * It pretty much generates a tree view that corresponds to that particular array
  	 * with spaces, tabs and new lines.
  	 * 
  	 * This is a reccursive function which uses the $tabs variable to keep track
  	 * of the number of tabs used to indent the current array.
  	 * When one of the values is an array, it creates a new level in the call stack
  	 * of the same function to take care of that particular array, with a new level
  	 * of indentation added.
	 *
	 * @param array $array The array to be converted 
	 * @param string $tabs A cumulative variable to collect the number of tabs to
  	 * include in the representation of the current array.
	 * @return string The string representation of the array.
	 * @author Marcu Sabin
	 */
	protected static function arrayToString($array, $tabs = "")	{
		$string = "";
		foreach ($array as $key => $value)	{
			if ($key === $value)	$string .= $tabs . self::getString($value);
			else if (is_string($value))	$string .= $tabs . $key . " => " . self::getString($value);
			else $string .= $tabs . $key . " => \n".self::arrayToString($value, $tabs."\t"); 
			$string .= "\n";
		}
		return $string;
	}
} // END class 
?>