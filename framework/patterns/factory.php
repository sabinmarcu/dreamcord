<?php
/**
 * The Factory pattern is used to construct diffrent Singleton / Prototype objects
 * of a certain class / family.
 *
 * @package ProjectX.Patterns
 * @author Marcu Sabin
 **/
class Factory extends Singleton
{
	public static function create($what, $dir)
    {
        if (include_once $dir . $what . '.php') {
            $classname = $what;
            return new $classname;
        } else {
            throw new Exception('Driver not found');
        }
    }
} // END class 
?>