<form action="<?php linkTo("register/") ?>" method="post" accept-charset="utf-8">
	<fieldset id="register" class="">
		<legend><?php __e("Register") ?></legend>
		<label for="name"><?php __e("Enter your Name") ?></label><input type="text" name="name" value="" id="name" placeholder='Doe' autofocus>
		<label for="surname"><?php __e("Enter your Surname") ?></label><input type="text" name="surname" value="" id="surname" placeholder='John'>
		<label for="username"><?php __e("Enter your Username") ?></label><input type="text" name="username" value="" id="username" placeholder='john.doe_666'>
		<label for="password"><?php __e("Enter your Password") ?></label><input type="password" name="password" value="" id="password" placeholder='1234'>
		<label for="repassword"><?php __e("Enter your Password yet again ... ") ?></label><input type="password" name="repassword" value="" id="repassword" placeholder='1234'>
		<label for="email"><?php __e("Enter your E-mail Address") ?> *</label><input type="email" name="email" value="" id="email" placeholder='johndoe@nowhere.com'>
		<label for="website"><?php __e("Enter your Website") ?> *</label><input type="url" name="website" value="" id="website" placeholder='http://johndoe.com/'>
		<label for="description"><?php __e("Tell us something about yourself ... ") ?> *</label>
		<textarea name="descr" rows="8" cols="40" placeholder = "<?php __e("My name is John Doe ... ")?>"></textarea>	
		<div class="clear"></div>
		<p><?php __e("<strong>Observation : </strong> The fields marked in '<strong>*</strong>' are not mandatory. The registration can continue without them!") ?></p>			
		<input type="submit" value="<?php __e("Continue") ?> &rarr;">
	</fieldset>
</form>
