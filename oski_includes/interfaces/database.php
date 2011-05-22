<?php
/**
* The database connection interface defines the template for a database connection extension.
* Any database connection should provide the means to connect to a database, search a specific table / collection, add, update and delete from one, describe it, create a new one or drop another, create a database, protect itself from malware (SQL Injection for instance) and the means to report any errors encountered.
*
* @package Databases
* @author Sabin Marcu
*/

interface database_connection	{
	
	public function connect($server, $username, $password, $database, $port);
	public function search($table, $what = array(), $where = array(), $ord = array(), $limit = '');
	
	public function add($table, $values);
	public function update($table, $what, $where);
	public function delete($table, $where);
	
	public function describe($table);
	
	public function createTable($name, $fields, $extras);
	public function dropTable($table);
	
	public function createDB($database, $check = true);
	public function errorReport();
	public function escape($string);
	
}
?>
