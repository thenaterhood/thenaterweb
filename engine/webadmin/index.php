<?php
	
include '../core_web.php';

$session = new session( array( 'user', 'pass', 'id', 'active' ) );

if ( !$session->active ){
	print '<form name="login" action="login.php" method="post">
    Username: <input type="text" name="user" />
    Password: <input type="password" name="pass" />
    <input type="submit" value="Login" />
	</form>';
}

?>