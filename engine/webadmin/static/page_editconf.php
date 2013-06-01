<h1>Edit Engine Configuration</h1>
<p><strong>WARNING:</strong> proper PHP syntax must be observed, or your edits will crash the engine and will require fixing from an external tool!</p>
<form action="index.php?id=saveconf" method="POST">

<?php

$config = file_get_contents('../class_config.php');
$lines = count( explode( "\n", $config) );

// Generate the input area, with the right height
print '<textarea name="content" rows="'.$lines.'" cols="100" >'.$config.'</textarea>';
?>
<br />
<input type="submit" value="Save and Apply" />
</form>