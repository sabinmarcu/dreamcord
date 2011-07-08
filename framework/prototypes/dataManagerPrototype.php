<?php
abstract class dataManagerPrototype extends Singleton	{
	
	protected $_server;
	protected $_port;
	protected $_database;
	protected $_username;
	protected $_password;
	protected $_type = "dataCursor";
	
	public static function obj($class = __CLASS__)	{
		return parent::obj($class);
	}
	
	public static function db($class = NULL)	{
		return self::obj($class);
	}

	abstract public function init();
	abstract public function createTable();
	abstract public function addField();
	abstract public function removeField();	
	abstract public function insertRow();	
	abstract public function findRow();	
	abstract public function getAll();	
	abstract public function describeDatabase();
	abstract protected function connectToDatabase();	
	abstract protected function connectToServer();	
}
?>