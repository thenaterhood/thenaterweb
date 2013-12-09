<form name="login" action="/auth/login" method="post">
	<input type='hidden' name='<?php echo $pageData['csrf_id']; ?>' value='<?php echo $pageData['csrf_token']; ?>' />
	Username: <input type="text" name="username" maxlength="30" />
	Password: <input type="password" name="pass" />
	<input type="submit" value="Continue" />
</form>