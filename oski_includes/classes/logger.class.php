<?php
class logger	{
	public function __construct()	{
		$dir = LOGDIR;
		$read = opendir($dir);
		while($file = readdir($read))	
			if (ext($file) == "log") {
				$temp = new file_reader(LOGDIR.$file, 'r');
				$temp = $temp -> toString();
				$this -> logs[str_replace('.'.ext($file), "", $file)] = ($temp ? $temp : "");
				unset($temp);
			}	
	}
	
	public function log($content, $log = 'misc')	{
		if (!file_exists(LOGDIR.$log.".log"))	{
			$file = fopen(LOGDIR.$log.".log", 'w'); fclose($file); unset($file);
			$this -> logs[$log] .= "\n" . date("[D jS M Y][G:i:s T(P)] : ", time()) . $content;
		}
		if (isset($this -> logs[$log]))	$this -> logs[$log] .= "\n" . date("[D jS M Y][G:i:s T(P)] : ", time()) . $content;
		else {
			$line = $this -> log($content, 'dump');
			$this -> log("Cannot access log file : ".$log." Logging in dump, line : ".$line, "error");	
		}
		return count(explode('\n', $this -> logs[$log]));
	}
	
	public function endSession()	{	
		foreach($this -> logs as $log => $content)	{
			$temp = new file_reader(LOGDIR.$log.".log", 'w');
			$temp -> writeFile($content);
			unset($temp);
		}
	}	
	
}
?>