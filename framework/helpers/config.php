<?php
class config extends dataManagerPrototype{
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
                $is = file_exists($database);
		R::setup("sqlite:".fileHelper::ensureFile($database.".sqlite"));
                if (!$is || !self::getAll("site_info"))    {
                    self::insertRow("site_info", array("key" => "site_title", "value" => "Amandla Website"));
                    self::insertRow("site_info", array("key" => "site_tagline", "value" => "Translates : Courageous website"));
                    self::insertRow("site_info", array("key" => "site_charset", "value" => "UTF-8"));
                    self::insertRow("site_info", array("key" => "site_language", "value" => "en_US"));
                }
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
}
?>