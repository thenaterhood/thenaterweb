<?php 
	
	if ( $pageData['session']->node == "" ){
		$displaypost = new article( "", $blogdef->id );

	}
	else{
		$displaypost = new article( $blogdef->post_directory.'/'.$pageData['session']->node, $blogdef->id );
	}
		
	print $displaypost->output( 'html' );

	if ( True ){
		/*
		 * Shows suggestions for other posts only if the 
		 * current post being shown happens to exist- ie, 
		 * it has a datestamp. Since datestamps will be automatically
		 * added to posts, all posts will have a datestamp.
		 */
		echo '<h5>You might also be interested in:</h5>';
		echo '<ul>';
		getSuggestions(3, $displaypost->tags, $blogdef->post_directory);
		echo '</ul>';
		echo '<p><a href="/'.$blogdef->id.'">Back to Blog Home</a></p>';
	}
	if ( $displaypost->datestamp and $session->node != 'latest'){
		echo $blogdef->commentCode;
	
	}
	else {
		#print "<p>You're viewing the latest available post without comments.  To comment, visit <a href='".$displaypost->link."'>the post's page</a>.</p>";
	}
?>

