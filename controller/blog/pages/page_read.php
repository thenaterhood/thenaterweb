<?php 

	$displaypost = $pageData['displaypost'];
	print $pageData['displaypost']->output( 'html' );

	if ( True ){
		/*
		 * Shows suggestions for other posts only if the 
		 * current post being shown happens to exist- ie, 
		 * it has a datestamp. Since datestamps will be automatically
		 * added to posts, all posts will have a datestamp.
		 */
		echo '<p><a href="/'.$pageData['appid'].'">Back to Blog Home</a></p>';
	}
	if ( $displaypost->datestamp and $pageData['session']->node != 'latest'){
		echo $blogdef->commentCode;
	
	}
	else {
		#print "<p>You're viewing the latest available post without comments.  To comment, visit <a href='".$displaypost->link."'>the post's page</a>.</p>";
	}
?>

