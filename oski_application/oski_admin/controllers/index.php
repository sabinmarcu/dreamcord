<?php
class indexController extends baseAdminController	{
	public function __construct()	{
		parent::__construct();
		$this -> adminSection = "Dashboard";
	}
	public function index()	{
		$this -> view = "index";
	}
}
?>