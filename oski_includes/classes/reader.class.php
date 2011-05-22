<?php
/**
* The basic file interface class. It contains functions for writing and reading from files.
*
* @package Readers
* @author Sabin Marcu
*/

class file_reader	{
	public $read;
	public $file;
	public $filename;
	function __construct($filename = "", $method = 'r')	{
		$this -> openFile($filename, $method);
	}
	public function openFile($filename = NULL, $method = 'r')	{
		if ($filename && file_exists($filename))	:
			try {
				if (!file_exists($filename) && $method == 'w')	{
					$file = fopen($filename, 'w');	fclose($file); unset($file);
				}
				$this -> filename = $filename;
				$this -> file = $filename;
				$this -> file = fopen($filename, $method);
				if ($method === 'r' && filesize($filename)) $this -> read = fread($this -> file , filesize($filename));
			} catch (Exception $e)	{
				echo "Cannot open file " . $filename . " \n Reason : " . $e -> getMessage();
			}
		endif;
	}
	public function __destruct()	{
		$this -> save();
	}
	public function toString()	{
		return $this -> read;
	}
	public function toMarkdown()	{
		return Markdown($this -> read);
	}

	public function printPlain()	{
		echo $this -> read;
	}
	public function printCode()	{
		echo "<code>" . $this -> read . "</code>";
	}
	public function printMarkdown()	{
		print Markdown($this -> read);
	}
	public function printFilename()	{
		print "<span class='filename'>" . $this -> file . "</span>";
	}
	public function writeFile($string)	{
	    if (isset($this -> file))
		fwrite($this -> file, $string);
	}
	public function save()	{
		if (isset($this -> file))		fclose($this -> file);
	}
}

/**
* The iniReader object extends the basic file reader. It has the capacity to parse and write arrays to .ini files.
*
* @package Readers
* @author Sabin Marcu
*/


class ini_reader extends file_reader	{


	public function atoFile($array, $org = true)	{

		$it = new RecursiveArrayIterator($array);
		$content = "";
		while ($it -> valid()) {
				if ($it -> hasChildren())	{
					if ($org)	:
						$content .= "[" . stripslashes($it -> key()) . "]\n\n";
						foreach( $it -> getChildren() as $key => $value)
							$content .=  "\t" . stripslashes($key) . " = \"" . stripslashes($value) . "\"\n";
						$content .=  "\n";
					else :
						foreach( $it -> getChildren() as $key => $value)
					 		$content .=  stripslashes($key) . " = \"" . stripslashes($value) . "\"\n";
					endif;
				}
				else $content .= stripslashes($it -> key()) . " = \"" . $it -> current() . "\"\n";
			$it -> next();
		}
		fwrite ($this -> file, $content);
	}

	public function toDict()	{
		return parse_ini_file($this -> filename, true);
	}
	public function toArray()	{
		return parse_ini_file($this -> filename);
	}
}
/**
* The xmlReader object extends the basic file reader object. It has the capacity to read and write to an XML file, allong with parsing it.
*
* @package Readers
* @author Sabin Marcu
*/


class xml_reader extends file_reader {
	public function toArray()	{
		return new SimpleXMLElement($this -> read);
	}
	public function aToFile($xml)	{
		$doc = new DOMDocument("1.0");
		$doc -> formatOutput = true;

		$domnode = dom_import_simplexml($xml);
		$domnode -> preserveWhiteSpace = false;
		$domnode = $doc -> importNode($domnode, true);
		$domnode = $doc -> appendChild($domnode);

		$xml = $doc -> saveXML();
		fwrite($this -> file, $xml);
	}
}

/**
* The lgParser object extends the basic file reader class but has the capacity to parse language translation files.
*
* @package Readers
* @author Sabin Marcu
*/


class lgParser extends file_reader	{
	public function parseFile()	{
		$this -> db = array();
		$sets = explode("#=:", $this -> read);
		foreach($sets as $set)	{
			$set = explode("\n", $set);
			$msgsrc = $msgstr = 0;
			foreach($set as $line)	{
				if (strpos($line, "msgsrc") !== FALSE) $msgsrc = $this -> parseLine($line);
				if (strpos($line, "msgstr") !== FALSE) $msgstr = $this -> parseLine($line);
				if ($msgsrc && $msgstr) break;
			}
			$this -> db[$msgsrc] = $msgstr;
		}
	}
	private function parseLine($line)	{
		$arr = explode(":=:", $line);
		$arr[0] = $this -> trim($arr[0]);
		$arr[1] = $this -> trim($arr[1]);
		$arr = substr($arr[1], 1, strlen($arr[1]) - 3);
		return $arr;
	}
	private function trim($string)	{
		while(substr($string, 0, 1) == " ") $string = substr($string, 1);
		while(substr($string, strlen($string) - 1) == " ") $string = substr($string, 0, strlen($string) - 2);
		return $string;
	}
}
?>
