<?php 

$hasPosts = False;

foreach ($page->posts as $node => $post) {
	$hasPosts = True;
	print '<p><a href="'.$page->urlBase.'editpost/node/'.$node.'">'.$post['title'].'</a></p>';

}

if ( ! $hasPosts ){
	print '	<div class="alert alert-warning">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	No posts exist!
	</div>';
}

?>
