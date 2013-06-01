<h1>Edit Engine Configuration</h1>
<p><strong>WARNING:</strong> proper PHP syntax must be observed, or your edits will crash the engine and will require fixing from an external tool!</p>
<form action="index.php?id=saveconf" method="POST">
<textarea name="content" rows="100" cols="100" ><?php print file_get_contents('../class_config.php'); ?></textarea><br />
<input type="submit" value="Save and Apply" />
</form>