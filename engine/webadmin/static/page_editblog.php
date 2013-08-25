<h1>Edit Blog Configuration</h1>
<p><strong>WARNING:</strong> proper PHP syntax must be observed, or your edits will crash blog! These tools are very primitive and will not validate your settings or your input.</p>
<?php
/**
 * Edits an existing blog configuration
 */


if ( $session->blogid ){

	$config = file_get_contents('../config/section.d/'.$session->blogid.'.conf.xml');
	$lines = count( explode( "\n", $config) )+4;

	if ( ! is_writable('../config/section.d/'.$session->blogid.'.conf.xml') )
		print '<p>Warning: Gnat cannot write to the configuration file selected, settings cannot be saved.</p>';

	print '<form action="index.php?id=saveconf" method="post">
		<input type="hidden" name="rcfile" value="../config/section.d/'.$session->blogid.'.conf.xml"/>
		<br />
		<textarea name="content" rows="'.$lines.'" cols="100" >'.$config.'</textarea>
		<br />
		<input type="submit" value="Save and Apply" />
		</form>';

}
else{

		$found = array();
		$handler = opendir(GNAT_ROOT.'/config/section.d');
		print '<ul>';

		while ($file = readdir($handler)){

			if ( $file != '.' && $file != '..' && !in_array($file, $found)){
				$blogid=substr($file, 0, strpos($file, ".") );
				$found[] = $file;
				print '<li><a href="index.php?id=editblog&blogid='.$blogid.'">'.$blogid.'</a></li>'."\n";
			}
		}
}


?>
<br />
<br />
<p><a href="index.php">Back to webadmin panel (discarding changes)</a></p>