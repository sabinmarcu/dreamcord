<?php
/**
 *
 */
class config extends dataManagerPrototype{
    /**
     * @static
     * @param string $class
     * @return config The Singleton Config Object
     */

    private static $_databases = array();
    private static $_dbCur = NULL;
    private static $_dbLim = 0;

	public static function db($class = __CLASS__)	{
		return self::obj($class);
	}
    public static function load($database)  {
        self::$_databases[] = $database;
        self::$_dbLim++;
    }
	public function createTable($name = NULL) {
		if (!isset($name))	trigger_error('Table name cannot be NULL');
		else {
			$table = R::dispense($name);
			R::store($table);
		}
	}
    private static function switchDb($database = NULL)  {
        if (isset($database))   R::setup("sqlite:".fileHelper::ensureFile(self::$_databases[$database].".sqlite"));
        else R::setup("sqlite:".fileHelper::ensureFile(self::$_databases[self::$_dbCur - 1].".sqlite"));
    }
    private static function haveDb() {
        if (self::$_dbCur === NULL) self::$_dbCur = 0;
        if (self::$_dbCur >= self::$_dbLim) { self::$_dbCur = NULL; return false; }
        self::$_dbCur++;
        return true;
    }
	public function init($database = NULL)	{

	      if ($database === NULL)	$database = APPDIR."config/default";
            self::load($database);

	      R::setup("sqlite:".fileHelper::ensureFile($database.".sqlite"));
                if (!self::getAll("site_info"))    {
                    self::insertRow("site_info", array("key" => "site_title", "value" => "Amandla Website"));
                    self::insertRow("site_info", array("key" => "site_tagline", "value" => "Translates : Courageous website"));
                    self::insertRow("site_info", array("key" => "site_charset", "value" => "UTF-8"));
                    self::insertRow("site_info", array("key" => "site_language", "value" => "en_US"));
                }
        
          self::checkIncludePaths();
	}

    private static function checkIncludePaths()
    {
        $folders = self::getAll("directories");
        foreach ($folders as $dir) set_include_path($dir -> value . PATH_SEPARATOR . get_include_path());
    }
    public function addField() { }
	public function removeField() { }
	public function insertRow($table = NULL, $values = NULL, $db = NULL) {
        $db = isset($db) ? $db : 0;
        self::switchDb($db);
		if (!$table || !$values)	trigger_error("Table name of Values set cannot be NULL");
		else {
			$row = R::dispense($table);
			$row -> import($values);
			R::store($row);
		}
		return self::db();
	}
	public function findRow($table = NULL, $criteria = NULL) {
        if (!$table || !$criteria)	trigger_error("Table name or Criteria set cannot be NULL");
        else {
            $keys = array_keys($criteria);
            $values = array_values($criteria);
            $query = ""; unset($criteria);
            foreach($keys as $key)	$query .= $key."=? ";
            $r = array();
            while (self::haveDb())  {
                self::switchDb();
                $t = R::find($table, $query, $values);
                $r = array_merge($r, $t);
            }
            return count($r) ? $r : NULL;
        }
        return false;
	}
	public function getAll($table = NULL) {
		if (!isset($table))	trigger_error('Table name cannot be NULL');
		else    {
            $r = array();
            while(self::haveDb())   {
                self::switchDb();
                $t = R::find($table);
                $r = array_merge($r, $t);
            }
            return $r;
        }
        return false;
	}
    public static function getType($table = NULL)  {
        self::switchDb(0);
		if (!isset($table))	trigger_error('Table name cannot be NULL');
        else {
            $table = R::find("tbltype", "id=? ", array($table));
            foreach($table as $table);
            return count($table) ? $table -> type : "none";
        }
    }
	public function describeDatabase() { }
	protected function connectToDatabase() { }
	protected function connectToServer() { }
    public function findProp($table = NULL, $which = NULL) {
        if (!$table || !$which)	trigger_error("Table name of Values set cannot be NULL");
            $ret = self::findRow($table, array("key" => $which));
            return count($ret) ? $ret[0] -> value : NULL;
    }
    public function __call($method, $arguments)   {
        return self::__callStatic($method, $arguments);
    }
    public static function __callStatic($method, $arguments)   {
        $config = Amandla::config();
        if (method_exists($config, $method))   return $config -> $method();
        if (empty($arguments))    return $config::getAll($method);
        if ($config::getType($method) == "property"){
            if (count($arguments) === 1)    {
                if (!is_array($arguments[0])) return $config::findProp($method, $arguments[0]);
                $arr = array();
                foreach($arguments[0] as $arg)  $arr[] = $config::findProp($method, $arg);
                return $arr;
            }
            $arr = array();
            foreach($arguments as $arg)  $arr[] = $config::findProp($method, $arg);
            return $arr;
        }
        $r = array();
        foreach($arguments as $arg) {
            $ret = $config::findRow($method, is_array($arg) ? $arg : array("key" => $arg));
            if ($ret) $r = array_merge($r, $ret);
        }
        return $r;
    }
}
?>