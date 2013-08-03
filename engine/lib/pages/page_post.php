<?php 
	
	if ( $session->node == "" ){
		$displaypost = new article( "", $blogdef->id );

	}
	else{
		$displaypost = new article( $blogdef->post_directory.'/'.$session->node, $blogdef->id );
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
		echo '<p><a href="/'.$blogdef->id.'/index.php">Back to Blog Home</a></p>';
	}
	if ( $displaypost->datestamp and $session->node != 'latest'){
		echo "
	<!-- BEGIN DISQUS CODE -->
	<div id='disqus_thread'></div>
	<script type='text/javascript'>
	/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
	var disqus_shortname = 'nphilosophy'; // required: replace example with your forum shortname

	/* * * DON'T EDIT BELOW THIS LINE * * */
	(function() {
		var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
		dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	})();
	</script>
	<noscript>Please enable JavaScript to view the <a href='http://disqus.com/?ref_noscript'>comments powered by Disqus.</a></noscript>
	<a href='http://disqus.com' class='dsq-brlink'>comments powered by <span class='logo-disqus'>Disqus</span></a>

	</div>
	<!-- END DISQUS CODE -->";
	
	}
	else {
		#print "<p>You're viewing the latest available post without comments.  To comment, visit <a href='".$displaypost->link."'>the post's page</a>.</p>";
	}
?>
