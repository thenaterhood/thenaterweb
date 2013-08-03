<?php
/* Provides a nice filename (read: ID) for displaying the
 * inventory.  Simply includes the inventory file after adding
 * appropriate list tags for the html
 */
echo '<br />'; 
echo '<ul>';
$inventory = new inventory( $blogdef->post_directory );
$titles = $inventory->selectAll();
$year = 0;
foreach ($titles as $item) {
	if ( substr($item['datestamp'], 0, 4) != $year ){
		$year = substr($item['datestamp'], 0, 4 );
		print '<li><strong>'.$year.'</strong></li>';
	}
	print '<li><a href="'.htmlentities( $item['link'] ).'">'.$item['title'].'</a></li>';
	# code...
}
echo '</ul>';

?>
