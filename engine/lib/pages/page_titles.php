<?php
/* Provides a nice filename (read: ID) for displaying the
 * inventory.  Simply includes the inventory file after adding
 * appropriate list tags for the html
 */
echo '<br />'; 
echo '<ul>';
$inventory = new inventory( $blogdef->post_directory );
$titles = $inventory->selectAll();
foreach ($titles as $item) {
	print '<li><a href="'.$item['link'].'">'.$item['title'].'</a>';
	# code...
}
echo '</ul>';

?>
