<?php
if ( $session->type != 404 ){
	print "<h2>Whoa there, ".$first_name."!</h2>";
	print "<p>Sorry, but that page doesn't seem to exist.  You tried to visit a page named <strong>".$id."</strong>.  Check your typing, look for a link, and if it seems like it should be here but isn't, <a href='http://www.google.com/recaptcha/mailhide/d?k=01IlSUddP8u2W2uex3mqRCNw==&amp;c=7kPbBTw9KPWPwtHnqBtTdgAoYqGYvdmWLrQHemAePOI=' onclick='window.open('http://www.google.com/recaptcha/mailhide/d?k\07501IlSUddP8u2W2uex3mqRCNw\75\75\46c\0757kPbBTw9KPWPwtHnqBtTdgAoYqGYvdmWLrQHemAePOI\075', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=300'); return false;' title='Reveal this e-mail address'>let me know.</a></p>";
}
else{
	print "<h2>Holy 404, ".$first_name."!</h2>";
	print "<p>Sorry, but that page doesn't exist on this website!  You might want to <a href='index.php'>go home</a>.</p>";
}
