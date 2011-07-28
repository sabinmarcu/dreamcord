<?php
/**
 * The main configuration handling engine.
 */
class config extends dataManagerPrototype{
    /**
     * @var array The databases loaded in the current session.
     */
    private static $_databases = array();
    /**
     * @var int The current database in use.
     */
    private static $_dbCur = NULL;
    /**
     * @var int The number of databases loaded.
     */
    private static $_dbLim = 0;
    /**
     * @var array
     */
    private static $_dump = array();
    /**
     * @static Returns the singleton object of the Config class.
     * @param string $class If the config class is overridden, the parameter will serve as a connector to the singleton repo.
     * @return config The singleton object.
     */
	public static function db($class = __CLASS__)	{
		return self::obj($class);
	}
    /**
     * @static Loads a database 
     * @param string $database The path to the database.
     * @return bool Wether the database was loadded successfuly or not.
     */
    public static function load($database, $type = "sqlite", $host = "", $username = NULL, $password = NULL)  {
        try {
            $dbnr = count(self::$_databases);
            if ($type == "mysql") {
                if (!$host) trigger_error("MySQL Database must have a host.");
                if (!isset($username)) trigger_error("MySQL Database must have a username.");
                if (!isset($password)) trigger_error("MySQL Database must have a password.");
                self::$_databases[$dbnr]['dbname'] = $database;
                $database = "host=".$host.";dbname=".$database;
            }
            self::$_databases[$dbnr]['path'] = $database;
            self::$_databases[$dbnr]['type'] = $type;
            self::$_databases[$dbnr]['username'] = $username;
            self::$_databases[$dbnr]['password'] = $password;
            $name = $type == "mysql" ? $database : stringHelper::trimext(str_replace(APPDIR, "", $database));
            self::$_databases[$dbnr]['name'] = $name;
            self::$_dbLim++;
            return $dbnr;
        } catch(Exception $e)   {
            self::logEvent("Could not load database {$database}, error : \"{$e}\"");
            return false;
        }
    }
	public function createTable($name = NULL) {
		if (!isset($name))	trigger_error('Table name cannot be NULL');
		else {
			$table = R::dispense($name);
			R::store($table);
		}
	}
    private static function switchDb($database = NULL)  {
        try{
            if (!isset($database))   $database = self::$_dbCur - 1;
            $str = self::$_databases[$database]['type'].":".fileHelper::ensureFile(self::$_databases[$database]['path']);
            if (self::$_databases[$database]['type'] == "mysql") {
                R::exec("CREATE DATABASE IF NOT EXISTS ".self::$_databases[$database]['dbname']);
                R::setup($str, self::$_databases[$database]['username'], self::$_databases[$database]['password']);
            }
            else R::setup($str);
            return true;
        }   catch(Exception $e) {
            $p = self::$_databases[$database]['path'];
            self::logEvent("Could not connect to database #{$database}, path:'{$p}}'");
            return false;
        }
    }
    private static function haveDb() {
        if (self::$_dbCur === NULL) self::$_dbCur = 0;
        if (self::$_dbCur >= self::$_dbLim) { self::$_dbCur = NULL; return false; }
        self::$_dbCur++;
        return true;
    }
	public function init($database = NULL, $type = 'sqlite', $host = "", $un = NULL, $ps = NULL)	{
            
	      if ($database === NULL)	$database = APPDIR."config/default.sqlite";
            self::load($database, isset($type) ? $type : "sqlite", $host, $un, $ps);


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
            R::exec("CREATE TABLE IF NOT EXISTS `".$table."` ( `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT , PRIMARY KEY ( `id` ) ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci thrown" );
			$row = R::dispense($table);
			$row -> import($values);
			R::store($row);
		}
		return self::db();
	}
	public function findRow($table = NULL, $criteria = NULL, $sorting = array(), $limit = NULL) {
        if (!$table || !$criteria)	trigger_error("Table name or Criteria set cannot be NULL");
        else {
            $keys = array_keys($criteria);
            $values = array_values($criteria);
            $query = ""; unset($criteria);
            foreach($keys as $key)	$query .= $key."=? ";
            if (isset($sorting) && count($sorting)) {
                $query .= "ORDER BY "; $ok = $ov = array();
                foreach($sorting as $set)   { $ok = array_merge($ok, array_keys($set)); $ov = array_merge($ov, array_values($set)); }
                for($i = 0; $i < count($ok); $i++) $query .= ($ok[$i] ? $ok[$i] . " " . $ov[$i] : $ov[$i] . " ASC") . ", ";
                $query = substr($query, 0, count($query) - 3) . " ";
            }
            if (isset($limit)) $query = "LIMIT " . $limit;
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
    public function findProp($table = NULL, $which = NULL, $order = array(), $limit = NULL) {
        if (!$table || !$which)	trigger_error("Table name of Values set cannot be NULL");
            $ret = self::findRow($table, array("key" => $which), $order, $limit);
            return count($ret) ? $ret[0] -> value : NULL;
    }
    public function __call($method, $arguments)   {
        return self::__callStatic($method, $arguments);
    }
    public static function __callStatic($method, $arguments)   {
        $config = Amandla::config();
        if (method_exists($config, $method))   return $config -> $method();
        if (empty($arguments))    return $config::getAll($method);
        $h = 0;
        $order = array(); $limit = null;
        foreach($arguments as $set)
        if (is_array($set)) {
            if (array_key_exists("_limit_", $set)) { $limit = $set["_limit_"]; unset($set['_limit_']); }
            if (array_key_exists("_order_", $set)) { $order[] = $set["_order_"]; unset($set['_order_']); }
        }
        if ($config::getType($method) == "property"){
            if (count($arguments) === 1)    {
                if (!is_array($arguments[0])) return $config::findProp($method, $arguments[0], $order, $limit);
                $arr = array();
                foreach($arguments[0] as $arg)  $arr[] = $config::findProp($method, $arg, $order, $limit);
                return $arr;
            }
            $arr = array();
            foreach($arguments as $arg)  $arr[] = $config::findProp($method, $arg, $order, $limit);
            return $arr;
        }
        $r = array();
        foreach($arguments as $arg) {
            $ret = $config::findRow($method, is_array($arg) ? $arg : array("key" => $arg), $order, $limit);
            if ($ret) $r = array_merge($r, $ret);
        }
        return $r;
    }
    public static function backUp($database = NULL, $location = "config/backups"){
        if (!isset($database))  for($i = 0; $i < self::$_dbLim; $i++) $database[$i] = $i;
        if (!is_array($database))   self::dumpDb($database);
        else foreach($database as $d)  self::dumpDb($d);
        self::saveDump($location);
    }
    public static function migrate($db, $d, $dt, $dh = NULL, $du = NULL, $dp = NULL)   {
        $dbi = self::load($d, $dt, $dh, $du, $dp);
        $dmp = self::dumpDb($db);
        self::switchDb($dbi);
        foreach(self::$_dump[$dmp] as $table => $data)   {
            foreach($data as $dat)   {
                $r = R::dispense($table);
                $r -> import( $dat -> export() );
                $i = R::store($r); var_dumP(R::load($table, $i));
            }
        }

;    }
    private static function dumpDb($id) {
        $tables = array();
        self::switchDb($id);
        if (self::$_databases[$id]['type'] == "sqlite")      $tables = R::getAll("SELECT name FROM sqlite_master WHERE type='table'");
        if (self::$_databases[$id]['type'] == "mysql")       $tables = R::getAll("SHOW TABLES");
        $data = array();
        foreach($tables as $key => $table)  if (strpos($table['name'], "sqlite") === false) {
            $data[$table['name']] = R::find($table['name']);
        }
        self::$_dump[self::$_databases[$id]['name']] = $data;
        return self::$_databases[$id]['name'];
    }
    private static function saveDump($location = "config/backups")  {
        $file = APPDIR.$location; $p = -1;  while ($p !== false) $p = strpos($file, "/", $p + 1);
        if ($p !== strlen($file) - 1) $file .= "/";
        $file .= date("G:i:s-Y:j:n", time()).".dbk";
        var_dump($file);
        $b = fopen(fileHelper::ensureFile($file), 'w');
        fwrite($b, serialize(self::$_dump));
        fclose($b);
    }
}
?>