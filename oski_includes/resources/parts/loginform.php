<form action="<?php linkTo("login/") ?>" method="post" accept-charset="utf-8">
	<fieldset id="" class="">
		<legend><?php __e("Login") ?></legend>
		<label for="username"><?php __e("Enter your Username") ?></label><input type="text" name="username" value="" id="username" placeholder='Your Username goes Here' autofocus>
		<label for="password"><?php __e("Enter your Password") ?></label><input type="password" name="password" value="" id="password" placeholder='Your Password goes Here'>
		<label for="remember"><?php __e("Do you want to remain logged in for 7 days?") ?></label><input type="checkbox" name="remember" id="remember">		
		<input type="submit" value="Continue &rarr;">
	</fieldset>
</form>
