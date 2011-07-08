<?php
/**
 * A simple helper to execute a number of low-level actions on strings.
 *
 * @package ProjectX.Helpers.Basic
 * @author Marcu Sabin
 */
class stringHelper extends Singleton{
	/**
	 * Returns the extension of a particular string.
	 * 
	 * The user can retrieve the second, the third, the nth extension, according to
	 * his needs. It may be necessary to extract the second extension of a file,
	 * let's say 'example.php.bak', a backup file.
	 *
	 * @param string $string The string to be parsed.
	 * @param string $r The number of the extension to be extracted.
	 * @param string $d The delimiter string used.
	 * @return string The extension requested.
	 * @author Marcu Sabin
	 */
	public static function ext($string, $r = 1, $d = ".")	{
		$ext = end(explode($d, $string)); 
		if ($r > 1)	return self::ext(str_replace(".".$ext, "", $string), $r - 1, $d);
		return $ext;
	}
	/**
	 * Trims the string input. It transforms tabs into spaces, eliminates double spaces
  	 * and makes sure the string does not start or end with a space.
	 *
	 * @param string $string The string input.
	 * @return string The trimmed string.
	 * @author Marcu Sabin
	 */
	public static function trimln($string)	{
		$string = str_replace("\t", " ", $string);
		while (strpos($string, "  ") !== FALSE)	$string = str_replace("  ", " ", $string);
		while (in_array($string[0], array(" ", "\t"))) $string = substr($string, 1);
		while (in_array($string[strlen($string) - 1], array(" ", "\t"))) $string = substr($string, 0, strlen($string) - 2);
		return $string;
	}
	/**
	 * Trims the string input. It first removes the line breaks of the string, and
  	 * afterwards trims the white spaces and tabs according to the trimln function.
	 *
	 * @see stringHelper::trimln()
	 * @param string $string 
	 * @return void
	 * @author Marcu Sabin
	 */
	public static function trim($string)	{
		$string = str_replace("\n", "", $string);
		return self::trimln($string);
	}
	/**
	 * Trims the extension of a given string.
	 * If specified, the function can remove any number of extensions of a given string.
	 * If there can be no more extensions removed, the last string calculated is returned
  	 * to the caller.
	 *
	 * @param string $string The string input.
	 * @param string $r The number of trims to execute
	 * @return string The trimmed string.
	 * @author Marcu Sabin
	 */
	public static function trimext($string, $r = 1)	{
		$stringr = str_replace(".".self::ext($string), "", $string);
		if ($r > 1) return self ::trimext($stringr, $r - 1);
		return $stringr ? $stringr : $string;
	}
}
?>