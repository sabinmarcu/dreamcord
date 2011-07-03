<?php
class mysqlDataManager extends dataManagerPrototype{
	protected $_type = "mysqlCursor";

	 public function createTable() {  return false; }
	 public function addField() {  return false; }
	 public function removeField() {  return false; }	
	 public function insertRow() {  return false; }	
	 public function findRow() {  return false; }	
	 public function getAll() {  return false; }	
	 public function describeDatabase() {  return false; }
	 protected function connectToDatabase() {  return false; }	
	 protected function connectToServer() {  return false; }
}
?>