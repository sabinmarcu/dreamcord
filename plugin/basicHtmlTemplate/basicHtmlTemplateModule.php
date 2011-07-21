<?php
/**
 * @todo stylesheet getter, javascript getter, font getter, etc.
 */
class basicHtmlTemplateModule extends modulePrototype   {
    protected $_viewDir = "views/";
    private static $_ct = NULL;

    public static function obj($c = __CLASS__)   {
        return parent::obj($c);
    }

    public function __construct()   {
        parent::__construct();
        Config::load($this -> plugin_path . "configs/config");
        self::$_ct = Config::themes("current");
    }

    public function renderHtmlPageAction()  {
        $this -> renderHeadSectionEvent();
        Amandla::trigger("bodyPlaceholder");
        $this -> renderBodySectionEvent();
    }
    private function renderHeadSectionEvent() {
        $this -> render("main/header");



    }
    protected static  function getStyles()    {
        self::getSomething("css");
    }
    protected static  function getFonts()    {
        $dir = self::obj() -> plugin_path . self::obj() -> _viewDir . "themes/" . self::$_ct . "/fonts/";
        $read = opendir($dir);  $files = array();
        while ($file = readdir($read))  $files[] = $file;
        $file = array(); $end = count($files) - 1;
        for ($i = 0; $i <= $end; $i++) {
            $name = stringHelper::trimext($files[$i]);
            if (strpos($name, ".") === 0) continue;
            $ext = stringHelper::ext($files[$i]);
            if (array_key_exists($name, $file)) array_push($file[$name]['exts'], $ext);
            else { $file[$name]['exts'] = array($ext); $file[$name]["path"] = "/" . str_replace(APPDIR, "", stringHelper::trimext($dir.$files[$i])); }
            unset($files[$i]);
        }
        $f = array("type" => "fonts", "files" => $file);
        echo fileHelper::inc($f);

    }
    protected static function getScripts()    {
        self::getSomething("js");
    }
    private function getSomething($what)    {
        $dir = self::obj() -> plugin_path . self::obj() -> _viewDir . "themes/" . self::$_ct . "/{$what}/";
        $read = opendir($dir);
        while ($file = readdir($read))   echo fileHelper::inc($dir.$file);
    }
    private function renderBodySectionEvent() {
        $this -> render("main/footer");

    }
}