<?php
/* Provides a nice filename (read: ID) for displaying the
 * inventory.  Simply includes the inventory file after adding
 * appropriate list tags for the html
 */
echo '<br />'; 
echo '<ul>';
$year = 0;
foreach ($pageData['titles'] as $node => $title ) {

	$link = getConfigOption('site_domain').'/?url='.$pageData['blogid'].'/read/'.$node.'.htm';
	
	print '<li><a href="'.htmlentities( $link ).'">'.$title.'</a></li>';
	# code...
}
echo '</ul>';

?>
