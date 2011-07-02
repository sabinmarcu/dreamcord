<?php
class dataManagerPrototype extends Singleton	{
	
	protected $_server;
	protected $_port;
	protected $_database;
	protected $_username;
	protected $_password;
	protected $_type = "dataCursor";
	
	public static function obj($class = __CLASS__)	{
		return parent::obj($class);
	}
	public function createTable()	{
		
	}
	public function addField()	{
		
	}
	public function removeField()	{
		
	}
	public function insertRow()	{
		
	}
	public function findRow()	{
		
	}
	public function getAll()	{
		
	}
	public function describeDatabase()	{
		
	}
	protected function connectToDatabase()	{
		
	}
	protected function connectToServer()	{
		
	}
}
?>