<?php
/**
* The No Database Connection object is a failsafe device to enable the administrator to modify the website and / or its pages in the absence of a regular database connection.
*
* @package Databases
* @author Sabin Marcu
*/

class NO_DATABASE_CONNECTION	{
	function __construct()	{
	}
	
	public function search($for, $what, $where)	{
		if ($for == "users")	{
			$this -> grandDatabase = new xml_reader(CACHEDIR."users.xml");
			$this -> grandDatabase = $this -> grandDatabase -> toArray();
			foreach($this -> grandDatabase -> user as $user){
				$d = 1;
				foreach($where as $key => $value) if (strval($user -> $key) !== $value) $d = 0;
				if ($d) : 
				$arr[0]['name'] = (string) $user -> name;
				$arr[0]['surname'] = (string) $user -> surname;
				$arr[0]['username'] = (string) $user -> username;
				$arr[0]['id'] = (string) $user -> id;
				$arr[0]['admin'] = (string) $user -> admin;
				$arr[0]['email'] = (string) $user -> email;
				$arr[0]['website'] = (string) $user -> website;
				return $arr;
				endif;
			}
			return NULL;
			unset($this -> grandDatabase);
		}
	}
	
	public function escape($string)	{
		return addslashes($string);
	}
	
	public function add()	{
		sysErr("nodb");
	}
	
	public function update()	{
		sysErr("nodb");
	}
	
	public function errorReport()	{
		sysErr("nodb");
	}
	
}
?>
