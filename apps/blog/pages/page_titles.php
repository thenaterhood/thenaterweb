<h2>Title List</h2>
<p style="font-size: .9em;">More formats: <a href="<?php print getConfigOption('site_domain').'/?url='.$page->blogid.'/titles/as/json'; ?>"><img height="16px" src="http://www.dev411.com/images/cats/json_54.png" /> json</a></p>
<?php
echo '<br />'; 
echo '<ul>';
$year = 0;
foreach ($page->titles as $node => $title ) {

	$link = getConfigOption('site_domain').'/?url='.$page->blogid.'/read/'.$node.'.htm';
	
	print '<li><a href="'.htmlentities( $link ).'">'.$title.'</a></li>';
	# code...
}
echo '</ul>';

?>
