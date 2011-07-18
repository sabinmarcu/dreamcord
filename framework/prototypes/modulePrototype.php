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
        if (!isset(self::$_plugins_dir)) self::$_plugins_dir = Config::directories("_plugins_");
        $this -> _name = str_replace("Module", "", $this -> _classname);
        $this -> plugin_path  = self::$_plugins_dir . "/" . $this -> _name . "/";
        foreach ($this -> _includeDir as $dir)  set_include_path(get_include_path() . PATH_SEPARATOR . Config::directories("_root_") . Config::directories("_plugins_") . $this -> _name . DIRECTORY_SEPARATOR . $dir);
    }
    public function render($view, $variables = array())    {
        foreach ($variables as $key => $value)    $$key = $value;
        include Config::directories("_root_") . Config::directories("_plugins_") . $this -> _name . "/" . $this -> _viewDir .  $view . ".view.php";
    }
    public static function getModel($model) {

    }
}