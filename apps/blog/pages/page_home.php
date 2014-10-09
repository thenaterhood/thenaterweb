<div id="blogHomeContent">
<?php
	$session = $page->session;
	$bloguri = $page->blogid;

		if ( $session->start == 42 ){
			print "<p style='font-size:2em;'>42! It's the meaning of life, the universe, and everything!</p><br />\n";
		}
		foreach ($page->articles as $id => $post) {

			print '<h3><a href='.$page->urlBase.'/read/'.$id.'.htm>' . $post->title . '</a></h3>';
			print '<h4>'. date( "F j, Y, g:i a", strtotime($post->datestamp) ). '</h4>';

			if ( is_array($post->content) ){
				echo '<p>' . implode($post->content) . '</p>';
			} else {
				echo $post->content;
			}

			print '<p><strong>Tags: </strong>' . $post->tags . '</p>';
			echo "<hr />";

		}

	if (! $session->start <= 0) echo "<a href='".$page->urlBase.'/home/start/'.($session->start - \Naterweb\Engine\Configuration::get_option('posts_per_page') )."/end/".($session->end - \Naterweb\Engine\Configuration::get_option('posts_per_page') )."'>Newer Posts</a>";
	if (! $session->start <= 0 and count($page->totalPosts ) > $session->end ) echo ' / ';
	if ( $page->totalPosts > $session->end ) echo "<a href='".$page->urlBase.'/home/start/'.($session->start + \Naterweb\Engine\Configuration::get_option('posts_per_page') )."/end/".($session->end + \Naterweb\Engine\Configuration::get_option('posts_per_page') )."'>  Older Posts</a>  ";
?>
</div>
