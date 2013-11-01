<h1>Edit Controller Configuration</h1>
<p><strong>WARNING:</strong> proper PHP syntax must be observed, or your edits will crash blog! These tools are very primitive and will not validate your settings or your input.</p>
<?php
/**
 * Edits an existing blog configuration
 */


if ( $admSession->blogid ){

	$config = file_get_contents('controller/'.$admSession->blogid.'conf.xml');
	$lines = count( explode( "\n", $config) )+4;

	if ( ! is_writable(GNAT_ROOT.'/config/section.d/'.$admSession->blogid.'.conf.xml') )
		print '	<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Thenaterweb is unable to write to this configuration file. Your settings cannot 
		be saved.
		</div>';

	print '<form action="'.getConfigOption('site_domain').'/webadmin/saveconf" method="post">
		<input type="hidden" name="rcfile" value="'.GNAT_ROOT.'/config/section.d/'.$session->blogid.'.conf.xml"/>
		<br />
		<textarea name="content" rows="'.$lines.'" cols="400" >'.$config.'</textarea>
		<br />
		<input type="submit" value="Save and Apply" />
		</form>';

}
else{

		$controllers = getControllers();

		print '<ul>';

		foreach ($controllers as $blogid) {


				print '<li><a href="'.getConfigOption('site_domain').'/webadmin/editblog/blogid/'.$blogid.'">'.$blogid.'</a></li>'."\n";
		}

		echo '</ul>';
}


?>
<br />
<br />
<p><a href="<?php print getConfigOption('site_domain'); ?>/webadmin">Back to webadmin panel (discarding changes)</a></p>