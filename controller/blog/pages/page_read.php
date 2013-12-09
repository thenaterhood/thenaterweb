<?php 

	$displaypost = $pageData['displaypost'];

	if ( $pageData['outdated'] ){
		print '	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		This post is more than a year old and its contents may refer to outdated information.
		</div>';
	}

	print $pageData['displaypost']->output( 'html' );

	if ( True ){
		/*
		 * Shows suggestions for other posts only if the 
		 * current post being shown happens to exist- ie, 
		 * it has a datestamp. Since datestamps will be automatically
		 * added to posts, all posts will have a datestamp.
		 */
		echo '<p><a href="/'.$pageData['blogid'].'">Back to Blog Home</a></p>';
	}
	if ( $displaypost->datestamp and $pageData['session']->node != 'latest'){
		echo $pageData['commentCode'];
	
	}
	else {
		#print "<p>You're viewing the latest available post without comments.  To comment, visit <a href='".$displaypost->link."'>the post's page</a>.</p>";
	}
?>

