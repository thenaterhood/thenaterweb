<h1>Edit Controller Configuration</h1>
<p><strong>WARNING:</strong> proper PHP syntax must be observed, or your edits will crash blog! These tools are very primitive and will not validate your settings or your input.</p>
<?php
/**
 * Edits an existing blog configuration
 */


if ( $admSession->blogid ){

	include_once 'controller/'.$admSession->blogid.'/main.php';
	define( strtoupper($admSession->blogid).'_ROOT', 'controller/'.$admSession->blogid );

	$blogController = new $admSession->blogid();
	$configFile = $blogController->configFile;


	if ( ! is_writable($configFile) )
		print '	<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Thenaterweb is unable to write to this configuration file. Your settings cannot 
		be saved.
		</div>';

	if ( file_exists($configFile) ){
		$config = file_get_contents($configFile);
		$lines = count( explode( "\n", $config) )+4;


		print '<form action="'.getConfigOption('site_domain').'/webadmin/saveconf" method="post">
			<input type="hidden" name="rcfile" value="'.$configFile.'"/>
			<br />
			<textarea name="content" rows="'.$lines.'" cols="400" >'.$config.'</textarea>
			<br />
			<input type="submit" value="Save and Apply" />
			</form>';
	} else{

		print '	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Thenaterweb could not find the configuration file. The application may not be 
		configured to allow Thenaterweb to modify it.
		</div>';

	}

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