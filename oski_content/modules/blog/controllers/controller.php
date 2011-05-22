<?php
class blogController extends baseController	{
	
	public function __construct($arr1,$arr2)	{
		parent::__construct($arr1,$arr2);
		$this -> db = new baseModel();
		$this -> db -> table = $this -> data["instance_id"]."_posts";
	}
	
	public function _index()	{
		$this -> posts = $this -> db -> getAll(array(), array("date_added_t" => "asc"), 5);
	}
	public function _index_admin()	{
		$this -> posts = $this -> db -> getAll(array(), array("date_added_t" => "asc"));
	}
	public function _edit_admin() {
		if (count($_POST)) $this -> db -> update(array("title"=>$_POST["title"],"excerpt"=>$_POST["excerpt"],"body"=>$_POST["body"],"date_added_t"=>$_POST["date_added_t"]),array("permalink" => actp(7)) );
		$this -> post = $this -> db -> get(array("permalink" => actp(7)));
		$this -> view = "form";
	}
	public function _add_admin() {
		if (count($_POST)) 	{
		$this -> db -> add(array("title"=>$_POST["title"],"excerpt"=>$_POST["excerpt"],"body"=>$_POST["body"],"date_added_t"=>$_POST["date_added_t"], "permalink" => $_POST["permalink"]));
		redirect(str_replace("add/", "", actp('complete')));
}
	}
	public function _remove_admin() {
		if ($_GET["permalink"]) {
		$this -> db -> remove(  array("permalink"=>$_GET["permalink"]));
		redirect(str_replace("remove/", "", actp('complete')));
	}
	}
	public function __install()	{
		
		Oski::app() -> database -> createTable($this -> data["instance_id"]."_posts", array("title" => "text", "excerpt" => "text", "body" => "text", "date_added_t" => "date", "permalink" => "text"), array());
	}
}
?>
