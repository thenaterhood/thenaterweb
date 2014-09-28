<form name="login" action="<?php print $page->urlBase; ?>addgroup" method="post">
        <input type='hidden' name='<?php echo $page->csrf_key; ?>' value='<?php echo $page->csrf_token; ?>' />
 		<br />
 		<input type="text" name="name" maxlength="30" placeholder='Group Name' />

        <br /><br />
        <input type="submit" class='btn btn-success' value="Continue" />
</form>

<br />
<br />
