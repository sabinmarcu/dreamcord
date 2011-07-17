<?php
class modulePrototype extends Singleton {
    protected $_name;
    protected $plugin_path;
    protected $_viewDir;
    protected $_modelDir;
    protected $_modelList;
    protected $_includeDir = array();
    protected static $_plugins_dir;
    public function __construct()    {
        parent::__construct();
        if (!isset(self::$_plugins_dir)) self::$_plugins_dir = Amandla::config() -> directories("plugins");
        $this -> _name = str_replace("Module", "", $this -> _classname);
        $this -> plugin_path  = self::$_plugins_dir . "/" . $this -> _name . "/";
        foreach ($this -> _includeDir as $dir)  set_include_path(get_include_path() . PATH_SEPARATOR . Amandla::config() -> directories("__plugins") . $this -> _name . "/" . $dir);
    }
    public function render($view, $variables = array())    {
        foreach ($variables as $key => $value)    $$key = $value;
        include $this -> _viewDir . $view . ".php";
    }
    public static function getModel($model) {

    }
}