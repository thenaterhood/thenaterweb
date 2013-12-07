<div id="blogHomeContent">
<?php
	$session = $pageData['session'];
	$bloguri = $pageData['blogid'];

		if ( $session->start == 42 ){
			print "<p style='font-size:2em;'>42! It's the meaning of life, the universe, and everything!</p><br />\n";
		}
		foreach ($pageData['articles'] as $key => $value) {

			print $value->toHtml();
			echo "<hr />";

		}

	if (! $session->start <= 0) echo "<a href='/?url=".$bloguri.'/home/start/'.($session->start - getConfigOption('posts_per_page') )."/end/".($session->end - getConfigOption('posts_per_page') )."'>Newer Posts</a>";
	if (! $session->start <= 0 and count($pageData['totalPosts'] ) > $session->end ) echo ' / ';
	if ( count($pageData['totalPosts'] > $session->end ) ) echo "<a href='/?url=".$bloguri.'/home/start/'.($start + getConfigOption('posts_per_page') )."/end/".($session->end + getConfigOption('posts_per_page') )."'>  Older Posts</a>  ";
?>
</div>
