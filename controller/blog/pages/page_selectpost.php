<?php 

$hasPosts = False;

foreach ($blogdef->getPostList() as $post) {
	$hasPosts = True;
	$path = pathinfo($post->file);
	print '<p><a href="'.getConfigOption('site_domain').'/'.$pageData['appid'].'/editpost/node/'.$path['filename'].'">'.$post->title.'</a></p>';

}

if ( ! $hasPosts ){
	print '	<div class="alert alert-warning">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	No posts exist!
	</div>';
}

?>