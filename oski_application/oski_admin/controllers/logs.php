<?php
class logsController extends baseAdminController	{
	public function __construct()	{
		parent::__construct();
		$this -> has_other = 1;
	}
	public function _index()	{
		$this -> listLog("error");
	}
	
	public function _catchall()	{
		
		if (isset(Oski::app() -> logger -> logs[actp('action')])) $this -> listLog(actp('action'));
		else $this -> noLog = 1;
	}
	
	private function listLog($log)	{
		
		$this -> view = "view";
		$this -> logname = $log;
		$this -> logcontent = Oski::app() -> logger -> logs[$log];
	}
}
?>