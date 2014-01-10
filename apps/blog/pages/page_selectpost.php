<?php 

$hasPosts = False;

foreach ($page->posts as $post) {
	$hasPosts = True;
	print '<p><a href="'.getConfigOption('site_domain').'/'.$page->appid.'/editpost/node/'.$post->nodeid.'">'.$post->title.'</a></p>';

}

if ( ! $hasPosts ){
	print '	<div class="alert alert-warning">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	No posts exist!
	</div>';
}

?>