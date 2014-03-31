<?php 

	$displaypost = $page->displaypost;

	if ( $pageData->outdated ){
		print '	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		This post is more than a year old and its contents may refer to outdated information.
		</div>';
	}

	print $displaypost->toHtml();

	if ( True ){
		/*
		 * Shows suggestions for other posts only if the 
		 * current post being shown happens to exist- ie, 
		 * it has a datestamp. Since datestamps will be automatically
		 * added to posts, all posts will have a datestamp.
		 */
	echo '<h5><span style="">Formats: <a href="/?url='.$page->blogid.'/json/node/'.$page->displaypost->nodeid.'">json</a>, <a href="/?url='.$page->blogid.'/simple/node/'.$page->displaypost->nodeid.'">simple html</a></span></h5>';
	}
	if ( $displaypost->datestamp and $page->session->node != 'latest'){
		echo $page->commentCode;
	
	}
	else {
		#print "<p>You're viewing the latest available post without comments.  To comment, visit <a href='".$displaypost->link."'>the post's page</a>.</p>";
	}
?>

