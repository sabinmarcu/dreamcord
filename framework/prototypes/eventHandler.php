<?php

/**
 * Description of eventHandler
 *
 * @author marcusabin
 */
class eventHandler extends Singleton {

    private static $_plugins;
    /**
     * @static
     * @return eventHandler The eventHandler Object
     */
    public static function obj()    {
        return parent::obj(__CLASS__);
}
    public static function trigger($event, $args = array())  {
        $list = Amandla::config() -> event_hooks( array("event_name" => $event) );
        $dir = Amandla::config() -> directories( "__plugins" ); $dir = $dir[0];
        foreach($list as $plugin)   {
	        $override = Amandla::config() -> overrides( array("overridden_plugin" => $plugin -> handler_name) );
            while ($override)	  {
	            $plugin -> handler_name = $override[0] -> overriding_plugin;
	            $override = Amandla::config() -> overrides( array("overridden_plugin" => $plugin -> handler_name) );
	        }
	        if (!self::checkExistance($dir -> value, $plugin -> handler_name)) continue; self::ensurePlugin($dir -> value, $plugin -> handler_name);
            if (!self::checkMethods($plugin -> handler_name, $plugin -> handler_action)) continue; self::executeAction($plugin -> handler_name, $plugin -> handler_action);
        }
    }
    public static function __callStatic($func, $args)   {
        if ($func === "trigger") eventHandler::obj() -> trigger($args[0]);
        else eventHandler::obj() -> trigger($func, $args);
    }
    public function __call($func, $args) {
        return self::__callStatic($func, $args);
    }




    private static function executeAction($plugin, $action)	{
        $action .= "Action";
        return  self::$_plugins[$plugin] -> $action();
    }
    private static function checkExistance($path, $plugin) {
        if (!file_exists($path.$plugin)) {
	        self::logEvent("Plugin '{$plugin }' (path : {$path}{$plugin}) is not installed!");
	        return false;
        }
        if (!file_exists($path.$plugin."/".$plugin."Juju.php")) {
	        self::logEvent("Plugin '{$plugin}' (path : {$path}{$plugin}/{$plugin}Juju.php) has missing files!");
	        return false;
        }
        return true;
    }
    private static function checkMethods($plugin, $action)	{
        $n = $plugin."Juju";
        if (!method_exists($n, $action."Action") || !is_callable($n, $plugin."Action"))  {
	        self::logEvent("Plugin '{$plugin}' is corrupted!");
	        return false;
        }
        return true;
    }
    private static function ensurePlugin($path, $plugin)	{
        include $path.$plugin."/".$plugin."Juju.php";   $n= $plugin."Juju";
        if (!isset(self::$_plugins[$plugin]))   self::$_plugins[$plugin] = new $n();
    }


}

?>
