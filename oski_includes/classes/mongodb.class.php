<?php
/**
* The MongoDB Database Connection object implements the connecting and querying of a Mongo Database.
*
* @package Databases
* @author Sabin Marcu
*/

class MONGODB_DATABASE_CONNECTION	{
	public function __construct(&$returnVal = NULL)	{
		
		if (isset(Oski::app() -> config)) : 
			$this -> server = Oski::app() -> config['DB_DATA']['database_server'];
			$this -> username = Oski::app() -> config['DB_DATA']['database_username'];
			$this -> password = Oski::app() -> config['DB_DATA']['database_password'];
			$this -> database = Oski::app() -> config['DB_DATA']['database_database'];
			$this -> port = (Oski::app() -> config['DB_DATA']['database_port'] ? Oski::app() -> config['DB_DATA']['database_port'] : "27017");
		
			$result = $this -> connect($this -> server, $this -> username, $this -> password, $this -> database, $this -> port);
		
			if (isset(Oski::app() -> config['DB_DATA']['database_prefix']))	$this -> prefix = Oski::app() -> config['DB_DATA']['database_prefix'];
			else $this -> prefix = 'oski';
			if (!$result) $returnVal = 0;
			else $returnVal = 1;
			
		endif;
	}
	
	public function connect($server, $username, $password, $database, $port)	{
		try {			
			if (!$port) $port = 27017;
			if (!class_exists("Mongo")) return 0;
			$this -> database = new Mongo("mongodb://".$server.":".$port, array("username" => $username, "password" => $password));
			if ($database)	$this -> connection = $this -> database -> $database;
			return 1;
		} catch (MongoConnectionException $e) {	
			$this -> error = $e;
			return 0;
		}	
	}
	
	public function search($collection, $what = array(), $where = array(), $ord = array(), $limit = "")	{
		$col = $this -> connection -> $collection;	
		if (is_string($what)) $what = array();
		if (is_string($where)) $where = array();
		if (is_string($ord)) $ord = array();
		try {
		$query1 = array();
		foreach($where as $key => $value)	{ 
			$signs = array(">" => '$gt', "<" => '$lt', "<=" => '$gte', ">=" => '$lte', "!=" => '$ne');
			if (!$key) {
				if (isset($value['sign']) && in_array($value['sign'], array_keys($signs))) $query1[$value['a']] = array($signs[$value['sign']] => $value['b']);
				else $query1[(isset($value['a']) ? $value['a'] : key($value))] = (isset($value['b']) ? $value['b'] : $value);
			}
			else $query1[$key] = $value;
		}
		$query2 = array();
		foreach($what as $arg)	$query2[$arg] = 1; 
		
		$result = $col -> find($query1, $query2);
		if (count($ord)){
			foreach($ord as $key => $value)	{
				$signs = array("asc" => 1, "desc" => -1);
				if ($value && in_array($value, $signs)) $arr[$key] = $signs[$value];
				else $arr[$key] = 1; 
			}
			$result -> sort($arr);
		}
		if ($limit)	$result -> limit($limit);
				
		$arr = array();
		foreach($result as $obj)	$arr[count($arr)] = $obj;
		return $arr;
			
		} catch (MongoCursorException $e) {
		$this -> error .= $e;
			return 0;
		}	
	}
	
	public function add($collection, $values)	{
		if (!isset($this -> connection)) return false;
		foreach($values as $key => $value)	$values[$key] = secure($value);
		$col = $this -> connection -> $collection;
		try {
			$col -> insert($values);
			return 1;			
		} catch (MongoCursorException $e) {
			return 0;
			$this -> error .= $e;
		}
	}
	
	public function update($collection, $what, $where)	{
		$col = $this -> connection -> $collection;
		try {
			$what = array('$set' => $what);
			$col -> update($where, $what, array("upsert" => true));	
			return 1;
		} catch (MongoCursorException $e) {
			return 0;
			$this -> error .= $e;
		}
	}
	
	public function delete($table, $where){
		/* CODE */
	}
	
	public function describe($table){
		/* CODE */
	}
		
	public function createTable($name, $fields, $extras){
		/* CODE */
	}
	
	public function dropTable($table){
		/* CODE */
	}
	
	public function createDB($database, $check = true){
		/* CODE */
	}
	
	public function escape($string)	{
		return addslashes($string);
	}
	
	public function errorReport()	{
		return (isset($this -> error) ? $this -> error : NULL);
	}
	
}
?>
