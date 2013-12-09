<form name="login" action="/auth/login" method="post">
        <input type='hidden' name='<?php echo $pageData['csrf_id']; ?>' value='<?php echo $pageData['csrf_token']; ?>' />
 		<input type="text" name="username" maxlength="30" placeholder='Username' />
		<input type="password" name="pass" placeholder='Password' />
        <br /><br />
        <input type="submit" class='btn btn-success' value="Continue" />
</form>

<br />
<br />
