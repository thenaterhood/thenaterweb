<h1>Edit Engine Configuration</h1>

<div class="alert alert-warning">
<button type="button" class="close" data-dismiss="alert">&times;</button>
Proper PHP syntax must be observed or your edits will crash the engine and will 
require fixing from a different tool. These tools are very primitive and will 
not validate your input.
</div>

<?php

if ( !is_writable('settings.php' ) ){
    print    '<div class="alert alert-danger">
<button type="button" class="close" data-dismiss="alert">&times;</button>
Thenaterweb is not able to write to this file so any changes made cannot be saved. 
You will need to log in to your server over SSH or FTP in order to make changes 
to Thenaterweb configuration.
</div>';
}

$config = file_get_contents('settings.php');
$lines = count( explode( "\n", $config) )+4;

// Generate the input area, with the right height
print '<textarea name="content" rows="'.$lines.'" cols="100" >'.$config.'</textarea>';
?>
<input type="hidden" name="rcfile" value="settings.php"/>
<br />
<input type="submit" value="Save and Apply" />
</form>