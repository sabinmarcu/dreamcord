<?php
/**
* The MySQL Database Connection object implements the connection and querying of MySQL databases.
*
* @package Databases
* @author Sabin Marcu
*/

	class MYSQL_DATABASE_CONNECTION	implements DATABASE_CONNECTION {
		
		public $server;
		public $port;
		public $username;
		public $password;
		public $database;
		private $connection;
		
	function __construct($returnVal = NULL)	{
			
			
			$this -> server = Oski::app() -> config['DB_DATA']['database_server'];
			$this -> username = Oski::app() -> config['DB_DATA']['database_username'];
			$this -> password = Oski::app() -> config['DB_DATA']['database_password'];
			$this -> database = Oski::app() -> config['DB_DATA']['database_database'];
			$this -> port = (Oski::app() -> config['DB_DATA']['database_port'] ? Oski::app() -> config['DB_DATA']['database_port'] : "3306");
			
			$this -> connect($this -> server, $this -> username, $this -> password, $this -> database, $this -> port);
			
			if (isset(Oski::app() -> config['DB_DATA']['database_prefix']))	$this -> prefix = Oski::app() -> config['DB_DATA']['database_prefix'];
			else $this -> prefix = 'oski';
		}
		
		public function connect($server, $username, $password, $database, $port)	{
			$this -> connection = new mysqli($server, $username, $password, $database, $port);
			if (mysqli_connect_error()) {
				$this -> connection = new mysqli($server);
				if (mysqli_connect_error())	{ die('Cannot connect to Server! The engine cannot function without server connection. Database, yes, but not server. <br />Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());}
			    $this -> error = 'Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error(); 
			}
			
		}		
		
		
		public function search($table, $what = array(), $where = array(), $ord = array(), $limit = "")	{
			$query = "SELECT ";	$i = 0;
			
			if (is_array($what) && count($what)) 
				foreach($what as $arg)	{ 
					if ($i) $query .= ", "; 
					$query .= secure($arg) . " "; 
					$i++;
				} 
			else $query .= "* "; 
							
			$query .= "FROM " . $this -> prefix . "_" . secure($table) . " ";	
			
			if ($where) { 
				$query .=" WHERE ";	
				$i = 0; 
				foreach($where as $key => $value)	{ 
					$signs = array(">", "<", "<=", ">=", "!=");
					if ($i) $query .= " AND ";
					if (!$key) {
						if (isset($value['sign'])) $query .= secure($value['a']) . " " . (in_array($value['sign'], $signs) ? $value['sign'] : "=") . " \"". secure($value['b']). "\" "; 
						else $query .= secure( (isset($value['a']) ? $value['a'] : key($value)) ) . " = \"". (isset($value['b']) ? $value['b'] : $value) . "\" ";
					}
					else $query .= secure( $key ) . " = \"". ( $value ) . "\" ";
					$i++; 
				}
			}
			
			if ($ord)	{ 
				$query .= "ORDER BY "; 
				$i = 0; 
				foreach($ord as $key => $value)	{
					if ($i) $query .= ", ";
					if ($key != $value) $query .= secure($key) . " " . secure($value) . " "; $i++;
				}
				if ($limit) $query .= "LIMIT ". secure($limit);
			}
			$rows = $this -> connection -> query($query);
			if ($rows)	{
				$result = array();
				while ($row = $rows -> fetch_array(MYSQLI_ASSOC))	{
					$result[count($result)] = $row;
				}
			}
			if (isset($result))	return $result;
			else return false;
		}
		
		public function add($table, $values)	{
			
			$query = "INSERT INTO " . $this -> prefix . "_" . $table . " (";
			$i = 0;
			foreach ($values as $key => $value)	{				
				$query .= $key;
				if ($i < count($values) - 1)	 $query .= ", ";
				$i++;
			}
			$query .= ") VALUES (";	$i = 0;		
			foreach ($values as $key => $value)	{				
				$query .= "'" . $value . "'";
				if ($i < count($values) - 1)	 $query .= ", ";
				$i++;
			}
			$query .=")";
			$rows = $this -> connection -> query($query);
			if ($rows)	return true;
			else return false;			
		}
		
		public function delete($table, $where)	{
			
			$query = "DELETE FROM " . $this -> prefix . "_" . $table;
			if ($where) { 
				$query .=" WHERE ";	
				if (is_string($where))	$query .= $where ." ";
				else if (is_array($where)){
					$i = 0; 
					foreach($where as $key => $value)	{ 
						if ($i) $query .= " AND "; 
						if ($key !== $value)	$string = $key . " = \"" . $value . "\"";	else $string = $key;
						$query .= $string . " "; $i++; 
					} 
				}
			}
			$rows = $this -> connection -> query($query);
			if ($rows)	return $rows;
			else return false;				
		}
		
		public function describe($table)	{
			$query = "DESCRIBE ". $this -> prefix . "_" .$table;
			$rows = $this -> connection -> query($query);
			if ($rows)	return $rows;
			else return false;		
		}
		
		public function dropTable($table)	{		
			$query = "DROP TABLE ". $this -> prefix . "_" .$table;
			$rows = $this -> connection -> query($query);
			if ($rows)	return true;
			else return false;		
		}
		
		public function createDB($database, $check = true)	{
			$query = "CREATE DATABASE ";
			if ($check) $query .= "IF NOT EXISTS ";
			$query .= $database;
			$rows = $this -> connection -> query($query);
			if ($rows)	return true;
			else return false;					
		}
		
		
		public function update($table, $what, $where)	{
			$query = "UPDATE " . $this -> prefix . "_" . $table . " SET ";
			$i = 0;
			foreach($what as $key => $value)	{
				if ($i)	$query .= ", ";
				$i++; $query .= $key . " = \"" . $value . "\"";	
			}
			if ($where) { 
				$query .=" WHERE ";	
				if (is_string($where))	$query .= $where ." ";
				else if (is_array($where)){
					$i = 0; 
					foreach($where as $key => $value)	{ 
						if ($i) $query .= " AND "; 
						if ($key !== $value)	$string = $key . " = " . $value;	else $string = $key;
						$query .= $string . " "; $i++; 
					} 
				}
			}
			$rows = $this -> connection -> query($query);
			if ($rows) return true;
			else return false;
		}
		
		public function createTable($name, $fields, $extras)	{
			if (!$this -> describe($name))	{
			$query = "CREATE  TABLE ". $this -> prefix . "_" .$name." ( ";
			$query .= "id int NOT NULL AUTO_INCREMENT ";
			foreach ($fields as $field => $type)	{
				$query .= ", ";
				$query .= $field." ".$type." ";
			}
			$query .= ", PRIMARY KEY (id) );";
			$rows = $this -> connection -> query($query);
			if ($rows) return true;
			else return false;
			} else  return true;
		}
		
		public function errorReport()	{
			return $this -> connection -> error;
		}
		
		public function escape($string)	{
			return	$this -> connection -> real_escape_string($string);
		}	
	
		
	}
	

?>
