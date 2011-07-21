<?php

$GLOBALS["packages"] = array();

class dataHelper extends Singleton{

    public static $_reccords = array();
    public static $_colections = array();
    public static $_packages = array();
    public static $_index = 0;
    public static $_null_ = NULL;

    public static function obj()   {
        return parent::obj(__CLASS__);
    }

    public static function &startRecording()    {
        self::$_reccords[self::$_index] = ""; self::$_index++;
        $ret = &self::$_reccords[self::$_index - 1];
        ob_start();
        return $ret;
    }
    public static function stopReccording()    {
        self::$_reccords[self::$_index - 1] = ob_get_contents();
        ob_end_clean();
        return self::$_reccords[self::$_index - 1];
    }
    public static function getReccording($index = NULL)   {
        if (isset($index)) :
            $ret = self::$_reccords[$index];
            unset(self::$_reccords[$index]);
            self::$_index--;
            return $ret;
        endif;
        $ret = self::$_reccords[self::$_index - 1];
        unset(self::$_reccords[self::$_index - 1]);
        self::$_index--;
        return $ret;
    }
    public static function flush()    {
        self::$_reccords = array();
        self::$_colections = array();
        self::$_index = 0;
    }
    public static function clean()  {
        self::$_reccords = array();
        self::$_index = 0;
    }
    public static function &store($variable) {
        self::$_reccords[self::$_index] = new dataReccord($variable); self::$_index++;
        $ret = &self::$_reccords[self::$_index - 1];
        return $ret;
    }
    public static function alter(&$var, $new)   {
        for($i = 0; $i <= self::$_index - 1; $i++) {
            if (self::$_reccords[$i] == $var)  {
                self::$_reccords[$i] = $new;
            }
        }
    }
    public static function remove($variable) {
        for($i = 0; $i <= self::$_index - 1; $i++) {
            if (self::$_reccords[$i] == $index)  {
                unset(self::$_reccords[$i]);
                self::$_index--; $i--;
            }
        }
    }
    public static function &collect($what, $to, $call = NULL) {
        if (!isset(self::$_colections[$to]))    self::$_colections[$to] = new dataCollection( $to, $what, $call );
        else self::$_colections[$to] -> collect( $what, $call );
        return self::$_colections[$to];
    }
    public static function &getCollection($which)   {
        $r = isset(self::$_colections[$which]) ? self::$_colections[$which] : self::$_null_;
        return $r;
    }
    public static function &newReccord($value)   {
        return self::store($variable);
    }
    public static function &newCollection($name)    {
        self::$_colections[$name] = new dataCollection( $name );
        return self::$_colections[$name];
    }
    public static function &addCollection($col) {
        if (!is_object($col) || !get_class($col) == "dataCollection") return false;
        self::$_colections[strval($col)] = $col;
        self::collect( self::$_colections[strval($col)], $col -> _getBelonging(), strval($col) );
        return self::$_colections[strval($col)];
    }
    public static function &packCollection($col, $call = NULL)    {
        $id = isset($call) ? $call : count(self::$_packages);
        $is = isset(self::$_colections[$col]);
        self::$_packages[$id] = $is ? new dataPackage( self::$_colections[$col], $id ) : self::$_null_;
        if ($is) unset(self::$_colections[$col]);
        return self::$_packages[$id];
    }
    public static function unpackPackage($which, $call = NULL)    {
        $col = unserialize($data);
    }
    public static function &readPackage($package)   {
        $id = count($GLOBALS["packages"]);
        $GLOBALS["packages"][$id] = new dataPackage(file_get_contents($package), $id, true);
        return $GLOBALS["packages"][$id];
    }
    public static function &loadPackage($pkg)    {
        self::$_packages[$pkg] = $GLOBALS["packages"][$pkg];
        unset($GLOBALS["packages"][$pkg]);
        return self::$_packages[$pkg];
    }
    public static function &detachPackage($id)  {
        $id = count($GLOBALS['packages']);
        $GLOBALS["packages"][$id] = self::$_packages[$id];
        unset(self::$_packages[$id]);
        return $GLOBALS["packages"][$id];
    }
    public static function exportPackage($id, $path)   {
        return self::$_packages[$id] -> export($path);
    }
    public static function removePackage($id)   {
        unset(self::$_packages[$id]);
    }
}

class dataReccord extends Prototype {
    protected $_identifier = NULL;
    protected $_belongsTO = NULL;
    protected static $_gd_ = array("God", "Allah", "Murphy", "Us", "Annonymous");
    protected static $_me_ = array("Bill Gates", "Steve Jobbs", "Mark Zuckerberg");
    public $val;

    public function __toString()    {
        if (is_array($this -> val)) return $this -> val[0];
        if (is_string($this -> val)) return $this -> val;
        return get_class($this);
    }
    public function __construct($id, $val = NULL, $call = NULL) {
        $this -> _belongsTO = $this::$_gd_[rand(0, count($this::$_gd_) - 1)];
        $this -> _identifier = $this::$_me_[rand(0, count($this::$_me_) - 1)];
        return $this -> _init($id, $val, $call);
    }
    protected function _init($id, $val, $call)  {
        return $this -> store($id);
    }
    public function addTo($col, $call = NULL) {
        $r = dataHelper::collect($this, $col, $call);
        if ($r) return $r;
        return false;
    }
    public function &store($val)   {
        $this -> val = $val;
        return $this;
    }
    public function __invoke()  {
        return $this -> val;
    }
}

class dataCollection extends dataReccord  {

    protected function _init($id, $value = NULL, $call = NULL)   {
        $this -> _identifier = $id;
        if (isset($value)) return $this -> collect($value, $call);
        return $this;
    }
    public function __toString()    {
        return $this -> _identifier;
    }
    public function _getBelonging()  {
        return $this -> _belongsTO;
    }
    public function collect($item, $call = NULL) {
        $id = isset($call) ? $call : count($this -> val);
        if (is_array($item)) {
            $arr = $item;
            $item = dataHelper::newCollection($id);
            foreach($arr as $key => $value)    $item -> collect($value, $key);
        }
        if (!is_object($item) || !in_array( get_class($item), array("dataReccord", "dataCollection"))) $item = dataHelper::store($item);
        $item -> _belongsTO = $this -> _identifier;
        $this -> val[$id] = &$item;
        return $this;
    }
    public function add(&$item, $call = NULL)    {
        return $this -> collect($item, $call);
    }
    public function __get($item)   {
        return $this -> getReccord($item);
    }
    public function __call($method, $args)  {
        if (method_exists($this, $method)) return parent::__call($method, $args);
        else return $this -> getReccord($method) -> val;
    }
    public function getReccord($item)    {
        $r = NULL;
        if (strpos($item, "_e") === 0) $item = str_replace("_e", "", $item);
        $t = array_key_exists($item, $this -> val); if ($t) $r = $this -> val[$item];
        $t = array_search($item, $this -> val); if ($t) $r = $this -> val[$t];
        return $r;
    }
}

class dataPackage extends dataReccord   {
    protected function _init($id, $value, $call = false)   {
        if ($call && self::is_serialized($id)) $this -> val = $id;
        else if (!$call) $this -> val = serialize($id);
        else $this -> val = serialize(null);
        $this -> _identifier = $value;
    }
    public function unpack()    {
        return unserialize($this -> val);
    }
    public function detach()    {
        return dataHelper::detachPackage($this -> _identifier);
    }
    public function export($path)    {
        $file = fopen($path.".pkg", 'w');
        fwrite($file, $this -> val);
        fclose($file);
        return $path;
    }
    public function load()  {
        $r =& dataHelper::addCollection( dataHelper::loadPackage($this -> _identifier) -> unpack() );
        dataHelper::removePackage($this -> _identifier);
        return $r;
    }
    private static function is_serialized($string) {
        return (@unserialize($string) !== false || in_array($string, array("b:0;", "N;")));
    }
}
