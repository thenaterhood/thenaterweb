<h1>Edit Blog Configuration</h1>
<p><strong>WARNING:</strong> proper PHP syntax must be observed, or your edits will crash blog! These tools are very primitive and will not validate your settings or your input.</p>
<?php
/**
 * Edits an existing blog configuration
 */
if ( $session->blogid ){

	$config = file_get_contents('../../'.$session->blogid.'/class_blogdef.php');
	$lines = count( explode( "\n", $config) )+4;

	if ( ! is_writable('../../'.$session->blogid.'/class_blogdef.php') )
		print '<p>Warning: Gnat cannot write to the configuration file selected, settings cannot be saved.</p>';

	print '<form action="index.php?id=saveconf" method="post">
		<input type="hidden" name="rcfile" value="../../'.$session->blogid.'/class_blogdef.php"/>
		<br />
		<textarea name="content" rows="'.$lines.'" cols="100" >'.$config.'</textarea>
		<br />
		<input type="submit" value="Save and Apply" />
		</form>';

}
else{

	print '<form name="create" action="index.php?id=editblog" method="post">
	Please enter a blog name to edit: <input type="text" name="blogid" />
	<input type="submit" value="Continue" />
	</form>';

}


?>
<br />
<br />
<p><a href="index.php">Back to webadmin panel (discarding changes)</a></p>