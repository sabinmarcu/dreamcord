<?php 
interface LOADER	{
	function __construct($data);
	public function loadData();
	public function loadController();
	public function loadView();
}
?>
