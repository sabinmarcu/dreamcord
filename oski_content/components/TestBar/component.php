<?php
class testbarComponent extends baseComponent	{
	public function postHead()	{
		if (actp('module') != "admin")
		include(dirname(__FILE__)."/testbar.html");
	}
}
?>