<?php
getTPart("article", "", "admin");
	if (isset($this -> updateResult)){	
	getTPart("content fifty-fifty");
		if ($this -> updateResult['msg'] == "success") 
			announce('success', __("Successfull Update !"), __("Redirecting you to your profile page")." ... ", '/admin/userpanel'); 
 		else if ($this -> updateResult['msg'] == "fail") 
			announce('error', __("Houston, we have a problem !"), array(__("Reason")." : ".Oski::app() -> updateResult['reason']), '/admin/userpanel/edit'); 
	} else {
	getTPart("title");	__e("Edit your Profile ! "); getTPart("title");
	getTPart("content fifty-fifty");
		$form = new formBuilder("/admin/userpanel/edit/");
		$form -> addInput("name", "", "", "", "text", "input", $this -> user['name']);
		$form -> addLabel("name", __("Enter your Name"));
		$form -> addInput("surname", "", "", "", "text", "input", $this -> user['surname']);
		$form -> addLabel("surname", __("Enter your Surname"));
		$form -> addInput("password");
		$form -> addLabel("password", __("Enter your Password"));
		$form -> addInput("repassword");
		$form -> addLabel("repassword", __("Enter your Password yet again ... "));
		$form -> addInput("email", "", "", "", "text", "input", $this -> user['email']);
		$form -> addLabel("email", __("Enter your E-mail Address"));
		$form -> addInput("website", "", "", "", "text", "input", $this -> user['website']);
		$form -> addLabel("website", __("Enter your Website"));
		$form -> addInput("descr", "", "", "", "text", "textarea", $this -> user['descr']);
		$form -> addLabel("descr", __("Tell us something about yourself ... "));
		$form -> printForm();
	}
	getTPart("content");
getTPart("article", "", "admin");

/*
if (isset($this -> updateResult)) {
 	if ($this -> updateResult['msg'] == "success") 
			announce('success', __("Successfull Update !"), __("Redirecting you to your profile page")." ... ", '/admin/userpanel'); 
 	else if ($this -> updateResult['msg'] == "fail") 
			announce('error', __("Houston, we have a problem !"), array(__("Reason")." : ".Oski::app() -> updateResult['reason']), '/admin/userpanel/edit'); 
 } else { ?>
<form action="/admin/userpanel/edit/" method="post">
	<fieldset id='register' class='small'>
		<legend><?php __e("Edit your Profile ! ") ?></legend>
		<label for="name"><?php __e("Enter your Name") ?></label><input type="text" name="name" id="name" value='<?php echo $this -> user['name']; ?>' />
		<label for="surname"><?php __e("Enter your Surname") ?></label><input type="text" name="surname" id="surname" value='<?php echo $this -> user['surname']; ?>' />
		<label for="password"><?php __e("Enter your Password") ?></label><input type="password" name="password" id="password" value='' />
		<label for="repassword"><?php __e("Enter your Password yet again ... ") ?></label><input type="password" name="repassword" id="repassword" value='' />
		<label for="email"><?php __e("Enter your E-mail Address") ?></label><input type="email" name="email" id="email" value='<?php echo $this -> user['email']; ?>' />
		<label for="website"><?php __e("Enter your Website") ?></label><input type="url" name="website" id="website" value='<?php echo $this -> user['website']; ?>' />
		<label for="descr"><?php __e("Tell us something about yourself ... ") ?></label><textarea name="descr" id="descr" cols="30" rows="10" placeholder='...'><?php echo $this -> user['descr'] ?></textarea>
		<input type="submit" />
	</fieldset>
</form>
<?php }*/ ?>