<?php
class userpanelController extends baseAdminController{
		
	public function __construct(){
		
		if (get_class(Oski::app() -> database) == "NO_DATABASE_CONNECTION") signal('nodb');
		parent::__construct();
		$this -> nonadmin = 1;
	}
	
	public function _index()	{
		$this -> getUser();
	}
	
	public function getUser()	{		
		
		$this -> user = Oski::app() -> database -> search("users", "", array("username" => $_SESSION['username']));
		$this -> user = $this -> user[0];
	}
	
	public function _edit()	{
		$this -> getUser();
		if (count($_POST))	 {		
		$result['msg'] = 'valid';
		$result['reason'] = "";
		if (!preg_match('/^[A-Za-z0-9_]*$/',$_POST['name']) || !preg_match('/^[A-Za-z0-9_]*$/',$_POST['surname']))	{
			$result['msg'] = 'invalid';
			$result['reason'] .= "Either the Name or the Surname is invalid. ";
		}
		if (!$_POST['password'] && !$_POST['repassword'])	{
			$data['password'] = $this -> user['password'];
		}
		else {
			if ($_POST['password'] != $_POST['repassword'])	{
				$result['msg'] = 'invalid';
				$result['reason'] .= "The two passwords do not match. ";
			}	else 	{
				$data['password'] = hash("sha256", Oski::app() -> database -> escape($_POST['password']));
			}
		}
		if ($_POST['email'] && !preg_match('/^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $_POST["email"]))	{		
			$result['msg'] = 'invalid';
			$result['reason'] .= "The e-mail is invalid. ";
		}
			
		if ($result['msg'] == 'valid')	{
			$query = Oski::app() -> database -> update('users', 
			array(
				"name" => secure($_POST['name']), 
				"surname" => secure($_POST['surname']), 
				"password" => $data['password'], 
				"email" => secure($_POST['email']), 
				"website" => secure($_POST['website']), 
				"descr" => secure($_POST['descr'])
				), array("username" => $_SESSION['username']));
			if ($query)	{		
				if (isset($_COOKIE['user_username']))	{
					setcookie('user_name', Oski::app() -> database -> escape($_POST['name']), time() + 60 * 60 * 24 * 7, "/");
					setcookie('user_surname', Oski::app() -> database -> escape($_POST['surname']), time() + 60 * 60 * 24 * 7, "/");
				}
				$_SESSION['name'] = $this -> userData['name'] = Oski::app() -> database -> escape($_POST['name']);
				$_SESSION['surname'] = $this -> userData['surname'] = Oski::app() -> database -> escape($_POST['surname']);
				$this -> updateResult['msg'] = 'success';
			}
			else { $this -> updateResult['msg'] = 'fail'; $this -> updateResult['reason'] = "Database error : <br>". Oski::app() -> database -> errorReport(); }
		}	else { $this -> updateResult['msg'] = 'fail'; $this -> updateResult['reason'] = $result['reason']; }	
	}
	}
}
?>