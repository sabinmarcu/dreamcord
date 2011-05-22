<?php

class module_loader	{

	public $plugin_id;
	public $instancedata = array();
	public $plugindata = array();
	public $subinstances = array();
	public $layoutNr;

	function __construct($data, $layoutNr)	{

		$this -> instdata = $data;
		$this -> layoutNr = $layoutNr;
		if ($data['instance_name'] && $data['instance_id'] && $data['plugin_id'])	:
			$this -> instance_name = $data['instance_name'];
			$this -> instance_id = $data['instance_id'];
			$this -> plugin_id = $data['plugin_id'];

		$this -> loadData();
		$this -> loadMVC();

		endif;
	}

	public function loadMVC()	{

		Oski::app() -> libInc("oski_content/modules/".$this -> plugin_id."/models");
		Oski::app() -> libInc("oski_content/modules/".$this -> plugin_id."/controllers");
		if (class_exists($this -> plugin_id."Controller"))	{ $controller = $this -> plugin_id."Controller";  $this -> controller = new $controller($this -> plugindata, $this -> instdata);	}
		else $this -> controller = new baseController();

		$this -> initPlugin();

	}

	public function loadAuxiliaries($data)	{
		if (count($data))	foreach ($data as $key => $value) 	$this -> instancedata[count($this -> instancedata)][$key] = $value;
	}

	public function loadSubinstances($data)	{
		if (count($data))	foreach ($data as $key => $value) 	$this -> subinstances[count($this -> subinstances)][$key] = $value;
	}

	public function loadData()	{
		$this -> plugindata = new ini_reader("oski_content/modules/".$this -> plugin_id."/config.ini");
		$this -> plugindata = $this -> plugindata -> toDict();
	}

	public function initPlugin()	{
	if (isset($this -> plugindata['init']))
		if (file_exists("oski_content/modules/".$this -> plugin_id."/".$this-> plugindata['init']))
		include "oski_content/modules/".$this -> plugin_id."/".$this-> plugindata['init'];
	}

	public function loadController()	{
		if (isset($this -> controller))	$this -> controller -> getFunction();
	}

	public function loadView()	{
		if (isset($this -> controller))	$this -> controller -> getView($this -> layoutNr);
	}

}
class ADMIN_LOADER extends MODULE_LOADER	{

	public $plugin_id;
	public $instancedata = array();
	public $plugindata = array();

	function __construct()	{
			$this -> instance_name = "Admin";
			$this -> instance_id = "Admin";
			$this -> plugin_id = "Admin";

		if (isset($_SESSION['username']))	{
			if (Oski::app() -> getActp('action') === "") $loader = "index"; else $loader = Oski::app() -> getActp('action');
			if (file_exists('oski_application/oski_admin/controllers/'.$loader.".php")) include 'oski_application/oski_admin/controllers/'.$loader.".php";
			else syserr('404');	$loader = $loader."Controller";
			if (class_exists($loader))	{ $this -> controller = new $loader(); }
			else $this -> controller = new baseController;
			DEFINE("ADMDIR", "oski_application/oski_admin/");
		}
		else syserr(403);
	}

	public function loadController()	{
		if (isset($_SESSION['username']))
			if (isset($this -> controller -> nonadmin) || (isset($_SESSION['admin']) && $_SESSION['admin'] == 1))	{ parent::loadController(); }
			else syserr(403);
	}
	public function loadView()	{
		if (isset($_SESSION['username']))
			if (isset($this -> controller -> nonadmin) || (isset($_SESSION['admin']) && $_SESSION['admin'] == 1))	{ parent::loadView(); }
			else syserr(403);
	}
}
class PAGE_LOADER	{

	public $page;
	public $content;
	public $layoutNr;

	public function __construct($data, $layoutNr)	{

		$this -> layoutNr = $layoutNr;
		if (isset($data['title']))
		$this -> instance_name = $data['title'];
		if (isset($data['id']))	$this -> instance_id = $data['id']; else $this -> instance_id = "";
		$this -> plugin_name = "static_page";
		if (isset($data['page']))
		$this -> page = $data['page'];
		$pagename = end(explode("/", $this -> page));
		Oski::app() -> setTitle(ucwords($pagename) . " Page");

		$this -> loadData();
	}

	public function loadData()	{

		if (file_exists('oski_includes/resources/pages/'.Oski::app() -> instance."/". $this -> page.".md"))	{$this -> content = new file_reader('oski_includes/resources/pages/'.Oski::app() -> instance."/" . $this -> page.".md"); $this -> type = "md";}
		else if (file_exists('oski_includes/resources/pages/'.Oski::app() -> instance."/". $this -> page.".html"))	{
			$this -> content = new file_reader('oski_includes/resources/pages/'.Oski::app() -> instance."/" . $this -> page.".html"); $this -> type = "html"; }
	}

	public function loadController(){

	}

	public function loadView()	{
		if ($this -> content)	{
			if ($this -> type == "md") $this -> content -> printMarkdown();
			else $this -> content -> printPlain();
		}
	}
}

class ERROR_LOADER implements LOADER 	{

	public $error;

	public function __construct($data)	{
		$this -> error = MVCS;
		$this -> loadData();
	}

	public function loadData()	{
		$this -> instance_name = "Error Mode";
		$this -> plugin_name = "Error Handler : ".$this -> error." Error";
		$this -> instance_id = $this -> error;
		$this -> plugin_id = "server_error";
	}

	public function loadController(){
		sysErr($this -> error);
	}

	public function loadView()	{
		server_error($this -> error);
	}
}

class folder_loader{
	public function __construct()	{

		$this -> path = Oski::app() -> actp;
		$this -> path = substr($this -> path['complete'], 1, strlen($this -> path['complete']) - 2);
		$this -> loadData();
	}

	public function loadData()	{
		$this -> instance_name = "Folder Listing";
		$this -> plugin_name = "Listing the folder : /".$this -> path;
		$this -> instance_id = $this -> path;
		$this -> plugin_id = "folder_listing";
	}

	public function loadController(){
		$this -> prev = explode("/", $this -> path);
		$aux = array_pop($this -> prev);
		$this -> prev[count($this -> prev)] = "";
		$this -> prev = "/".implode("/", $this -> prev);
		$this -> path .= "/";
	}

	private function format_bytes($size) {
	    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
	    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
	    return round($size, 2).$units[$i];
	}

	public function loadView()	{
		$dir = $this -> path;
		$read = opendir($dir);	$i = 1;
		echo "<table border='0' cellspacing='5' cellpadding='0'><tr><th colspan='5'><a href='", $this -> prev, "'>Previous folder</a></th></tr><tr><th width='25px'>No.</th><th>File Name</th><th width='100px'>File Size</th><th width='200px'>File Type</th></tr>";
		while ($file = readdir($read))
			if (substr($file, 0, 1) !== ".") {
				$isdir = is_dir($dir.$file);
				echo "<tr><td>", $i++, "</td>";
				echo "<td><a href='", $file, "'>", $file, "</a></td>";
				echo "<td>";
				if ($isdir) echo 0;
				else echo $this ->  format_bytes(filesize($dir.$file));
				echo "</td>";
				echo "<td>";
				$finfo = finfo_open(FILEINFO_MIME);
				$mimeData = finfo_file($finfo, $dir.$file);
				$mimeData = explode(";", $mimeData);
				echo $mimeData[0];
				echo "</td>";
				echo "</tr>";
			}
		echo "</table>";
	}
}

class userRel	{


	public function __construct()	{

		$this -> section = Oski::app() -> getActp('module');
		$this -> instance_name = $this -> instance_id = $this -> plugin_id = $this -> section;
	}

	public function loadController()	{

		if ($this -> section == "register" && get_class(Oski::app() -> database) == "NO_DATABASE_CONNECTION") signal('nodb');
		if (count($_GET) && isset($_GET['code']))	if ($this -> activate()) redirect("login");
		if (!count($_POST))	return 0;
		$cont = new usrController();
		switch ($this -> section)	{
			case "login" : $cont -> login(); break;
			case "logout" : $cont -> logout(); break;
			case "register" :  $cont -> register(); break;
		}
	}

	public function loadView()	{
		if (count($_POST))	$this -> valuesPosted($_POST);
		else $this -> noValues();
	}

	private function noValues()	{

		switch ($this -> section)	{
			case "login" : if (isset($_SESSION['username'])) announce("warning", "Houston, we have a Problem!", array("It seems like you are allready logged in! ... ", "I don't know what you're trying to do, but I don't like it ... ", "You're goin' to the logout page, in case you wanted to log out but didn't know how ..."), '/logout/', "Hey ... ");
		else {
			getTPart("title"); echo __("Login"); getTPart("title");
			$form = new formBuilder("login/");
			$form -> addInput("username");
			$form -> addLabel("username", __("Enter your Username"));
			$form -> addInput("password", "", "", "", "password");
			$form -> addLabel("password", __("Enter your Password"));
			$form -> addInput("remember", "", "", "", "checkbox");
			$form -> addLabel("remember", __("Do you want to remain logged in for 7 days?"));
			$form -> printForm();
		} break;
			case "logout" : if (!isset($_SESSION['username'])) announce("warning", "Houston, we have a Problem!", array("It seems like you are not logged in! ... ", "How can we log you OUT when you aren't loggend IN?", "You're goin' to the login page, buddy, just in case you missed the right page ..."), '/login/', "Hey ... ");	else {
			getTPart("title"); echo __("Logout"); getTPart("title");
			$form = new formBuilder("logout/");
			$form -> addInput("logout", "", "", "", "hidden", "input", 1);
			$form -> addLabel("logout", __("You can always log in back."));
			$form -> printForm();
		} break;
			case "register" : if (isset($_SESSION['username'])) announce("warning", "Houston, we have a Problem!", array("It seems like you are allready logged in! ... ", "I don't know what you're trying to do, but I don't like it ... ", "You're goin' to the logout page, in case you wanted to log out but didn't know how ..."), '/logout/', "Hey ... ");
		else {
			getTPart("title"); echo __("Register"); getTPart("title");
			$form = new formBuilder("register/");
			$form -> addInput("name");
			$form -> addLabel("name", __("Enter your Name"));
			$form -> addInput("surname");
			$form -> addLabel("surname", __("Enter your Surname"));
			$form -> addInput("username");
			$form -> addLabel("username", __("Enter your Username"));
			$form -> addInput("password");
			$form -> addLabel("password", __("Enter your Password"));
			$form -> addInput("repassword");
			$form -> addLabel("repassword", __("Enter your Password yet again ... "));
			$form -> addInput("email");
			$form -> addLabel("email", __("Enter your E-mail Address"));
			$form -> addInput("website");
			$form -> addLabel("website", __("Enter your Website"));
			$form -> addInput("descr", "", "", "", "", "textarea");
			$form -> addLabel("descr", __("Tell us something about yourself ... "));
			$form -> printForm();
			__e("<strong>Observation : </strong> The fields marked in '<strong>*</strong>' are not mandatory. The registration can continue without them!");
			} break;
		}
	}

	private function valuesPosted()	{
		$view = new usrView();
		switch ($this -> section)	{
			case "login" : $view -> login(); break;
			case "logout" : $view -> logout(); break;
			case "register" : $view -> register(); break;
		}
	}

}

 class usrView	{
	public function login()	{

		if (isset(Oski::app() -> userdata))	{
			$paragraphs = array("We managed to log you in! ... ", "Stand by while we redirect you to the main page!");
			if (isset($_POST['remember']))	$paragraphs[count($paragraphs)] = "And plus, your credentials will be saved for the next 7 days";
			announce("success", "Success!", $paragraphs, '/', "Logged IN");
		}
		else announce("error", "Houston, we have a problem!", array("We can't log you in! ... ", "Please retry ... it may be that you got your username and / or password wrong!"), '/login/');
	}

	public function logout()	{
		$paragraphs = array("We managed to log you out! ... ", "Stand by while we redirect you to the main page!");
		announce("success", "Success!", $paragraphs, "/", "Logged OUT");
	}

	public function register()	{

		if (Oski::app() -> regRez['msg'] === "success")	announce("success", "Succesful registration !", array("Well, you are now a proud member of this website !", "You can now procede to login!"), "/login/");
		else announce("error", "That didn't sound good ...", array("Looks like we have a slight problem in registering you ... ", "The reason is :". Oski::app() -> regRez['reason'], "Please try again!"), "/register/", "Yipes ...", 5);
	}
}



 class usrController	{
	public function login()	{

if ($user = Oski::app() -> database -> search('users', "", array("username" => $_POST['username'], "password" => hash("sha256", $_POST['password']))))	:
	Oski::app() -> userdata = $user = $user[0];
	if (isset($_POST['remember']))	{
		setcookie('user_name', ($user['name'] ? $user['name'] : 0), time() + 60 * 60 * 24 * 7, "/");
		setcookie('user_surname', ($user['surname'] ? $user['surname'] : 0), time() + 60 * 60 * 24 * 7, "/");
		setcookie('user_username', ($user['username'] ? $user['username'] : 0), time() + 60 * 60 * 24 * 7, "/");
		setcookie('user_admin', ($user['admin'] ? $user['admin'] : 0), time() + 60 * 60 * 24 * 7, "/");
	}
	if (isGL()) {
		setcookie('tuser_name', ($user['name'] ? $user['name'] : 0), time() + 60 * 5, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
		setcookie('tuser_surname', ($user['surname'] ? $user['surname'] : 0), time() + 60 * 5, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
		setcookie('tuser_username', ($user['username'] ? $user['username'] : 0), time() + 60 * 5, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
		setcookie('tuser_admin', ($user['admin'] ? $user['admin'] : 0), time() + 60 * 5, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
		setcookie('t_rec', 1, time() + 60 * 60 * 24, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
	}
	$_SESSION['name'] = Oski::app() -> userdata['name'];
	$_SESSION['surname'] = Oski::app() -> userdata['surname'];
	$_SESSION['username'] = Oski::app() -> userdata['username'];
	$_SESSION['admin'] = Oski::app() -> userdata['admin'];
	session_write_close();
endif;
	}

	public function logout()	{


	unset($_SESSION['username']);
	unset($_SESSION['name']);
	unset($_SESSION['surname']);
	unset($_SESSION['admin']);
	unset($_SESSION['username']);
	setcookie('user_id', "", 1, "/");
	setcookie('user_name', "", 1, "/");
	setcookie('user_surname', "", 1, "/");
	setcookie('user_username', "", 1, "/");
	setcookie('user_admin', "", 1, "/");

	setcookie('tuser_id', "", 1, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
	setcookie('tuser_name', "", 1, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
	setcookie('tuser_surname', "", 1, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
	setcookie('tuser_username', "", 1, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
	setcookie('tuser_admin', "", 1, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);
	setcookie('t_rec', "", 1, "/", ".".Oski::app() -> config['SITE_SETUP']['root']);

	session_write_close();

}

	public function register()	{

	$result['msg'] = 'valid';
	$result['reason'] = "";
	if (!($_POST['username'] && $_POST['name'] && $_POST['surname'] && $_POST['password'] && $_POST['repassword']))	{
		$result['msg'] = 'invalid';
		$result['reason'] .= "One of the mandatory fields is empty. ";
	}
	if (!preg_match('/^[A-Za-z0-9_]*$/', $_POST['username'])) {
		$result['msg'] = 'invalid';
		$result['reason'] .= "The username is invalid. ";
	}
	if (!preg_match('/^[A-Za-z0-9_]*$/',$_POST['name']) || !preg_match('/^[A-Za-z0-9_]*$/',$_POST['surname']))	{
		$result['msg'] = 'invalid';
		$result['reason'] .= "Either the Name or the Surname is invalid. ";
	}
	if ($_POST['password'] != $_POST['repassword'])	{
		$result['msg'] = 'invalid';
		$result['reason'] .= "The two passwords do not match. ";
	}
	if ($_POST['email'] && !preg_match('/^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $_POST["email"]))	{
		$result['msg'] = 'invalid';
		$result['reason'] .= "The e-mail is invalid. ";
	}
	if (Oski::app() -> database -> search("users", "id", array("username" => $_POST['username'])))	{
		$result['msg'] = 'invalid';
		$result['reason'] .= "The username is taken. ";
	}
	if ($result['msg'] == 'valid')	{
		$query = Oski::app() -> database -> add('users',
		array(
			"name" => Oski::app() -> database -> escape($_POST['name']),
			"surname" => Oski::app() -> database -> escape($_POST['surname']),
			"username" => Oski::app() -> database -> escape($_POST['username']),
			"password" => hash("sha256", Oski::app() -> database -> escape($_POST['password'])),
			"email" => Oski::app() -> database -> escape($_POST['email']),
			"website" => Oski::app() -> database -> escape($_POST['website']),
			"descr" => Oski::app() -> database -> escape($_POST['descr'])
		));
		if ($query)	{
			$result['msg'] = 'success';
				$host = (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']);/*
				$mail = new PHPMailer(true);
				$mail -> AddAddress($_POST['email'], $_POST['name'] . " " . $_POST['surname']);
				$mail -> setFrom("noreply@".$host, Oski::app() -> config['SITE_INFO']['site_title']);
				$mail -> Subject = "Activate your account at ".Oski::app() -> config['SITE_INFO']['site_title'];
				$mail -> Body = 'Confirm your email adress !\n\nDear ' . $_POST['name'] . ' ' . $_POST['surname'] . ',\n\nTo validate your registration to ' . Oski::app() -> config['SITE_INFO']['site_title'] . ' you have tofollow the link down bellow.\n\n<a href="http://'.$host.'/activate/?code='. hash("sha256", $_POST['username'].$_POST['name']) .'&username=' . $_POST['username'] . '">Activate !</a>\n\nIf you encounter problems while activating the account please contact an administrator.\n\nThe Oski team wishes you the best of luck !/n/n&copy; M&MTek 2010 - ' . date("Y", time());
				$mail -> send();*/
				$body = 'Confirm your email adress !\n\nDear ' . $_POST['name'] . ' ' . $_POST['surname'] . ',\n\nTo validate your registration to ' . Oski::app() -> config['SITE_INFO']['site_title'] . ' you have tofollow the link down bellow.\n\n<a href="http://'.$host.'/activate/?code='. hash("sha256", $_POST['username'].$_POST['name']) .'&username=' . $_POST['username'] . '">Activate !</a>\n\nIf you encounter problems while activating the account please contact an administrator.\n\nThe Oski team wishes you the best of luck !/n/n&copy; M&MTek 2010 - ' . date("Y", time());
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
				 mail($_POST['email'],  "Activate your account at ".Oski::app() -> config['SITE_INFO']['site_title'], wordwrap($body, 70), $headers);
		} else {
			$result['msg'] = 'fail';
			$result['reason'] = Oski::app() -> database -> errorReport();
		 }
	}

	Oski::app() -> regRez = $result;
}
	public function activate() {

	$user = Oski::app() -> database -> search("users", "", array("username" => $_GET['username']));
	if ($_GET['code'] == hash("sha256", $_POST['username'].$_POST['name'])) $result = Oski::app() -> database -> update("users",array("activate" => 1),array("username" => $_GET['username']));
	if ($result) return true;
	else return false;
	}
}
?>
