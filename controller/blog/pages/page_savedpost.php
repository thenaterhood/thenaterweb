<?php

if ( $pageData['saved'] ){

	$link = getConfigOption('site_domain').'/'.$pageData['blogid'].'/read/'.$saved;
	
	print '<p>Post saved successfully! View it at <a href="'.$link.'.htm">'.$link.'</a></p>';

} else {

	$post = $pageData['post'];

	print '	<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Post could not be saved due to an error.
		</div>';

	print '	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		Your post is displayed below as entered so that you can copy and paste it elsewhere 
		until the problem gets resolved.
		</div>';

	print '<h1>'.htmlspecialchars( $post['title'] ).'</h1>';
	print '<p>'.htmlspecialchars( $post['date'] ).'</p>';
	print '<p>'.htmlspecialchars( $post['tags'] ).'</p>';
	print '<p>'.htmlspecialchars( $post['content'] ).'</p>';

}

?>