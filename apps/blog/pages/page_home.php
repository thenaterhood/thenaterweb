<div id="blogHomeContent">
<?php
	$session = $page->session;
	$bloguri = $page->blogid;

		if ( $session->start == 42 ){
			print "<p style='font-size:2em;'>42! It's the meaning of life, the universe, and everything!</p><br />\n";
		}
		foreach ($page->articles as $key => $value) {

			print $value->toHtml();
			echo "<hr />";

		}

	if (! $session->start <= 0) echo "<a href='/?url=".$bloguri.'/home/start/'.($session->start - getConfigOption('posts_per_page') )."/end/".($session->end - getConfigOption('posts_per_page') )."'>Newer Posts</a>";
	if (! $session->start <= 0 and count($page->totalPosts ) > $session->end ) echo ' / ';
	if ( $page->totalPosts > $session->end ) echo "<a href='/?url=".$bloguri.'/home/start/'.($session->start + getConfigOption('posts_per_page') )."/end/".($session->end + getConfigOption('posts_per_page') )."'>  Older Posts</a>  ";
?>
</div>
