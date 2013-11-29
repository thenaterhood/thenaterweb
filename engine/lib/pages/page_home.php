<div id="blogHomeContent">
<?php
		if ( $pageData['session']->start == 42 ){
			print "<p style='font-size:2em;'>42! It's the meaning of life, the universe, and everything!</p><br />\n";
		}
		getPosts( $blogdef->id, $blogdef->post_directory, $pageData['session']->start,$pageData['session']->end);
?>
</div>

<!-- 
<div id="blogside">
	<?php
		if ( $session->id != 'tags' ){
			print '<br /><h5>Looking for something?</h5>';
			include GNAT_ROOT.'/lib/pages/page_tags.php';
		}
	?>					
</div>
-->
