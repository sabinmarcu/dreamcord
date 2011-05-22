<?php
/**
* The installer class.
*
* @package MainApplication
* @author Sabin Marcu
*/

class oski_engine_installer	{
	
	/**
	* The install function controlls the installation of the Oski Engine application.
	*
	*/
	
	
	public function	install()	{
		
		if (Oski::app() -> getActp('module') === "")	Oski::app() -> setProp("actp", "step1", 'module');
			switch (Oski::app() -> getActp('module'))	{
				case "step1": $this -> step1(); break;
				case "step2": $this -> step2(); break;
				case "step3": $this -> step3(); break;
				case "step4": $this -> step4(); break;
			}
		include Oski::app() -> baseURL.'oski_includes/resources/pages/install/'.Oski::app() -> getActp('module').'.html'; 
	}

	/**
	* The uninstall function removes any configuration files used by the Oski Engine application. The actual source files remain on the disk to be reused.
	*
	*/
	
	
	public function uninstall()	{
		if (count($_POST))	{
			if (count($_COOKIE)) foreach($_COOKIE as $key => $value)	setcookie($key, "", time() - 60*60, "/");
			if (isset($_POST['data']))	{
				$prev = "oski_application/oski_instances"; $dir = opendir($prev); $prev .= "/"; $read = new ini_reader();
				while ($readfile = readdir($dir))	{
					if (!is_dir($prev.$readfile))	{
						$read -> openFile($prev.$readfile);	$read = $read -> toDict();
						$remover = new module_optics($read['ESENTIALS']['plugin_id']);
						$remover -> deleteInstance(str_replace(".".end(explode(".", $readfile)), "", $readfile), true);
					}
				}	
			}
			unlink("oski_application/config.ini");
			unlink(".htaccess");		
			header("Location: /");
		}
	}
	
	private function step1()	{
		if (count($_COOKIE)) foreach($_COOKIE as $key => $value)	setcookie($key, "", time() - 60*60, "/");
		$file = new file_reader();
		$file -> openFile(".htaccess", 'w');
		$file -> writeFile("Options +FollowSymLinks\nIndexIgnore */*\nRewriteEngine on\n\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\n\nRewriteRule . index.php");
		unset($file);
	}
	
	private function step2()	{
		
		if (count($_POST))	{
			$data['SITE_INFO']['site_title'] = $_POST['title'];
			$data['SITE_INFO']['site_tagline'] = $_POST['tagline'];
			$data['SITE_SETUP']['language'] = $_POST['language'];
			$data['SITE_SETUP']['charset'] = $_POST['charset'];
			$data['SITE_SETUP']['domain'] = $data['SITE_SETUP']['root'] = $_SERVER['HTTP_HOST'];
			$data['SITE_SETUP']['global_login'] = 1;
			$file = new ini_reader(); $file -> openFile("oski_application/oski_config/engines/default.tmp", 'w');
			$file -> aToFile($data);
			header ("Location: " . Oski::app() -> baseURL . "/step3/");
		}
	}
	
	private function step3()	{
		
		if (count($_POST))	{
			$file = new ini_reader("oski_application/oski_config/engines/default.tmp");	$data = $file -> toDict();
			if (isset($_POST['prefix']))	$data['DB_DATA']['database_prefix'] = $_POST['prefix'];
			$data['DB_DATA']['database_type'] = $_POST['type'];
			$data['DB_DATA']['database_server'] = $_POST['server'];
			$data['DB_DATA']['database_username'] = $_POST['username'];
			$data['DB_DATA']['database_password'] = $_POST['password'];
			$data['DB_DATA']['database_database'] = $_POST['database'];
			$data['DB_DATA']['database_port'] = (isset($_POST['port']) ? $_POST['port'] : "");
			Oski::app() -> config['DB_DATA'] = $data['DB_DATA'];
			$file -> openFile("oski_application/oski_config/engines/default.tmp", 'w');
			$file -> aToFile($data);
			unset($file);
			$database = $_POST['type']."_DATABASE_CONNECTION";
			Oski::app() -> database = new $database();
			Oski::app() -> database -> connect(
				$data['DB_DATA']['database_server'], 
				$data['DB_DATA']['database_username'],
				$data['DB_DATA']['database_password'], 
				"",
				$data['DB_DATA']['database_port']
			);
			Oski::app() -> database -> createDB($data['DB_DATA']['database_database']);
			Oski::app() -> database -> connect(
				$data['DB_DATA']['database_server'], 
				$data['DB_DATA']['database_username'],
				$data['DB_DATA']['database_password'],
				$data['DB_DATA']['database_database'],
				$data['DB_DATA']['database_port']
			);
			if (isset($_POST['prefix']))	Oski::app() -> database -> prefix = $data['DB_DATA']['database_prefix'];
			Oski::app() -> database -> createTable("users", array("id" => "INT", "name" => "TEXT", "surname" => "TEXT", "username" => "TEXT", "password" => "TEXT", "email" => "TEXT", "website" => "TEXT", "descr" => "TEXT", "admin" => "INT"), array("autoincrement" => "id", "primary" => "id"));
			if (isset($_POST['admuser']))	$admin['username'] = $_POST['admuser'];
			else $admin['username'] = 'admin';
			if (isset($_POST['admpass']))	$admin['password'] = $_POST['admpass'];
			else $admin['password'] = '123456';
			Oski::app() -> database -> add("users", array("name" => "System", "surname" => "Administrator", "username" => $admin['username'], "password" => hash("sha256", $admin['password']), "admin" => 1, "email" => "email@server.com", "website" => "http://".$_SERVER["SERVER_NAME"]."/"));
			$xml = new SimpleXMLELement("<users></users>");
			$user = $xml -> addChild("user");
			$user -> addChild("name", "System");
			$user -> addChild("surname", "Administrator");
			$user -> addChild("username", $admin['username']);
			$user -> addChild("password", hash("sha256", $admin['password']));
			$user -> addChild("admin", 1);
			$user -> addChild("email", "email@server.com");
			$user -> addChild("website", "http://".$_SERVER["SERVER_NAME"]."/");			
			$file = new xml_reader(CACHEDIR."users.xml", 'w'); $file -> aToFile($xml); unset($file); 
			header ("Location: " . Oski::app() -> baseURL . "/step4/");
		}
	}
	
	private function step4()	{
		$file = new ini_reader("oski_application/oski_config/engines/default.tmp");	$content = $file -> toDict();
		$content['THEME_SETUP']['default_theme'] = 'default';		
		$content['THEME_SETUP']['error_theme'] = 'error';
		$content['THEME_SETUP']['login_theme'] = 'login';
		$content['THEME_SETUP']['admin_theme'] = 'admin';
		$content['THEME_SETUP']['user_theme'] = 'admin';
		$file -> openFile("oski_application/oski_config/engines/default.tmp", 'w');	$file -> aToFile($content);
		unset($file);
		if (!file_exists(LODIR."default"))	mkdir(LODIR."default");
		if (!file_exists(INSTDIR."default"))	mkdir(INSTDIR."default");
		if (!file_exists(CACHEDIR."default"))	mkdir(CACHEDIR."default");
		if (!file_exists(ULDIR."default"))	mkdir(ULDIR."default");
		rename("oski_application/oski_config/engines/default.tmp", "oski_application/oski_config/engines/default.ini");

	}
	
}
?>
