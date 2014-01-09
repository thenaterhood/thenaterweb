<h1>Edit Engine Configuration</h1>
<p><strong>WARNING:</strong> proper PHP syntax must be observed, or your edits will crash the engine and will require fixing from an external tool! These tools are very primitive and will not validate your settings or your input.</p>
<form action="<?php print getConfigOption('site_domain'); ?>/webadmin/saveconf" method="POST">

<?php

$config = file_get_contents('settings.php');
$lines = count( explode( "\n", $config) )+4;

// Generate the input area, with the right height
print '<textarea name="content" rows="'.$lines.'" cols="100" >'.$config.'</textarea>';
?>
<input type="hidden" name="rcfile" value="settings.php"/>
<br />
<input type="submit" value="Save and Apply" />
</form>