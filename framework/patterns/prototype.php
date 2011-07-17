<?php
/**
 * The Prototype Object is the foundation for any other object mentained within the
 * Amandla Engine.
 *
 * @package ProjectX.Patterns
 * @author Marcu Sabin
 */
class Prototype{
	/**
	 * Every classname can be accessed via this value.
	 *
	 * @var string	The name of the object instance.
	 **/
	protected static $_classname = "";
	/**
	 * The history value will hold any log information available of the current session.
	 *
	 * @var string The log history.
	 **/
	protected static $_history = array();
	/**
	 * The construction function just saves the classname of the current object and
  	 * logs a successful instantiation.
	 * @author Marcu Sabin
	 */
    protected static $_methods = array();
	protected function __construct(){
		$this -> _classname = get_class($this);
        $this -> _methods = get_class_methods($this);
		self::logEvent("Creating new object from Prototype", "success");
	}
	/**
	 * Logs any information passed as arguments.
	 *
	 * @return bool Wether the logging was successful or not.
	 * @author Marcu Sabin
	 */
	protected static function logEvent()	{
		try {
			self::$_history[] = func_get_args();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	/**
	 * Grabs the necessary variable wether it is protected, private, or not
	 *
	 * @param string $var The value that is to be grabbed.
	 * @return mixed Either the value required or NULL in case it does not exist.
	 * @author Marcu Sabin
	 */
	public function getVar($var = NULL)	{
		if (!$var)	trigger_error("There can be no variable registered as NULL");
		return isset($this -> $var) ? $this -> $var : NULL;
	}
	/**
	 * Sets a value.
	 *
	 * @param string $var The Variable name that will retain the value
	 * @param string $value The Value to be retained
	 * @return void
	 * @author Marcu Sabin
	 */
	public function setVar($var = NULL, $value = NULL)	{
		if (!$var)	trigger_error("The variable name cannot be NULL");
		$this -> $var = $value;
	}
	/**
	 * Prints the log history either to a file or to the web page / screen.
	 * If the parameter is specified it will print to file.
	 *
	 * @param string $location The path to the file in which to write the log.
	 * @return void
	 * @author Marcu Sabin
	 */
	public static function printHistory($location = NULL, $mode = 'a')	{
		if ($location)	{
			$file = fopen($location, $mode); fwrite($file, "\n\n\n\n".varTrans::getString(self::$_history)); fclose($file);
		}	else var_dump(self::$_history);
		self::logEvent("Printing of the current session's history.", "success");
	}
	/**
	 * Retrieves the curent Object's class name.
	 *
	 * @return string The class name.
	 * @author Marcu Sabin
	 */
	public function name()	{
		return get_class($this);
	}
	/**
	 * The ability to clone is available.
	 *
	 * @return void
	 * @author Marcu Sabin
	 */
	public function __clone()	{

	}
}
?>