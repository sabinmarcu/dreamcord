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
	public static function db($class = __CLASS__)	{
		return self::obj($class);
	}
	public function createTable($name = NULL) {
		if (!isset($name))	trigger_error('Table name cannot be NULL');
		else {
			$table = R::dispense($name);
			R::store($table);
		}
	}
	public function init($database = NULL)	{
	      if ($database === NULL)	$database = APPDIR."config/default";
	      $this -> _database = $database;
//        var_dump($database);exit;
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
        foreach ($folders as $dir) set_include_path($dir->value . PATH_SEPARATOR . get_include_path());
    }
    public function addField() { }
	public function removeField() { }
	public function insertRow($table = NULL, $values = NULL) {
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
			return R::find($table, $query , $values);
		}
	}
	public function getAll($table = NULL) {
		if (!isset($table))	trigger_error('Table name cannot be NULL');
		else {
			return R::find($table);
		}
	}
	public function describeDatabase() { }
	protected function connectToDatabase() { }
	protected function connectToServer() { }
        public function findProp($table = NULL, $which = NULL) {
		if (!$table || !$which)	trigger_error("Table name of Values set cannot be NULL");
                $ret = self::findRow($table, array("key" => $which));
                foreach($ret as $ret);
                return isset($ret) ? $ret -> value : NULL;
        }
        public function __call($method, $arguments)   {
            return self::__callStatic($method, $arguments);
        }
        public static function __callStatic($method, $arguments)   {
            $config = Amandla::config();
            if (method_exists($config, $method))   return $config -> $method();
            if (empty($arguments))    return $config::getAll($method);
            $r = array();
            foreach($arguments as $arg) {
                $ret = $config::findRow($method, is_array($arg) ? $arg : array("key" => $arg));
                $r = array_merge($r, $ret);
            }
            return $r;
        }
}
?>