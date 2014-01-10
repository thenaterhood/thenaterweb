<?php



?>

<form name="login" action="/?url=auth/changeuser" method="post">
		<input type='hidden' name='uid' value='<?php echo $page->user->id; ?>' />
        <input type='hidden' name='<?php echo $page->csrf_key; ?>' value='<?php echo $page->csrf_token; ?>' />
 		<br />
 		<input type="text" name="first_name" maxlength="30" placeholder='First Name' value="<?php echo $page->user->first_name; ?>"/>
 		<input type="text" name="last_name" maxlength="30" placeholder='Last Name' value="<?php echo $page->user->last_name; ?>"/>
 		<br /><br />
		<input type="text" name="username" maxlength="30" placeholder='Username' value="<?php echo $page->user->username; ?>"/>

        <br /><br />
        <h3>Groups</h3>
        <?php 

        foreach ($page->groups as $g ) {
        	
        	if ( ! in_array($g, $page->ingroups) )
        		echo '<input type="checkbox" name="groups[]" value="'.$g->name.'">'.$g->name.'</input>';
        	else
        		echo '<input type="checkbox" name="groups[]" value="'.$g->name.'" checked>'.$g->name.'</input>';

        	echo '<br />';

        }

        ?>
        <br />
        <br />
        <input type="submit" class='btn btn-success' value="Save and Continue" />
        <a class="btn btn-danger" href="/?url=auth/deluser/uid/<?php echo $page->user->id; ?>">Delete User</a>

</form>

<br />
<br />