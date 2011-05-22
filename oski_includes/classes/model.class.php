<?php

	class baseModel	{
	
	public $result = "";
	public $resultset = array();
	public $table = "";
	
		public function __construct($table = "")	{
			if ($table)	$this -> table = $table;
		}
	
		public function add($sets)	{
			
			Oski::app() -> database -> add($this -> table, $sets);
		}
		
		public function remove($where = array())	{
			
			Oski::app() -> database -> delete($this -> table, $where);			
		}
		
		public function tGetAll($where = array(), $ord = array(), $limit = "")	{		
			
			$this -> resultset = Oski::app() -> database -> search($this -> table, "", $where, $ord, $limit);
		}
		
		public function getAll($where = array(), $ord = array(), $limit = "")	{
			
			$resultset = Oski::app() -> database -> search($this -> table, array(), $where, $ord, $limit);
			return $resultset;			
		}
		
		public function tGet($where = array())	{
			
			$this -> result = Oski::app() -> database -> search($this -> table, "", $where);
			$this -> result = $this -> result[0];
		}
		
		public function get($where = array())	{
			
			$result = Oski::app() -> database -> search($this -> table, "", $where);
			return $result[0];
		}
		
	}

?>
