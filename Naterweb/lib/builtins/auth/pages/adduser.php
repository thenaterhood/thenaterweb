<form name="login" action="<?php print $page->urlBase; ?>adduser" method="post">
        <input type='hidden' name='<?php echo $page->csrf_key; ?>' value='<?php echo $page->csrf_token; ?>' />
 		<br />
 		<input type="text" name="first_name" maxlength="30" placeholder='First Name' />
 		<input type="text" name="last_name" maxlength="30" placeholder='Last Name' />
 		<br /><br />
		<input type="text" name="username" maxlength="30" placeholder='Username' />
		<input type="password" name="pass" placeholder='Password' />


        <br /><br />
        <input type="submit" class='btn btn-success' value="Continue" />
</form>

<br />
<br />
