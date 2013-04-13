<?php 
include '../engine/core_blog.php';
include '../engine/core_redirect.php';


$session = new session( array('name', 'track', 'konami', 'node') );
$config = new config();

if ( $config->friendly_urls ){
	$redirect = new condRedirect( "post.php", "/blog/read/".$session->node.".htm", $session->uri );
	$redirect->apply( 301 );
}

$first_name = $session->name;
$track = $session->track;
$id = 'Blog';
?>

<?php include $config->webcore_root.'/html_doctype.html'; ?>
<head>
<meta name="keywords" content='<?#php echo file_get_contents($page_keywords_file);
echo 'Nate Levesque, TheNaterhood, the naterhood'?>' />
<?php echo '<meta name="description" content="Nate Levesque" />'; ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo "Nate Levesque :: $id"; ?></title>
<link href="/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Electrolize' rel='stylesheet' type='text/css' />

<style type="text/css">
</style>
<?php 
/*
 * Inserts any tracking code into the page if the user hasn't
 * requested to not be tracked.
 */
if ($track == "n"){
	print "<!-- Tracking code removed from page by request -->";
}
else {
	print $config->tracking_code;
}
?>
<?php if ($session->konami == "pride") print '<style type="text/css">body {background: url(images/rainbow.jpg) fixed}</style>'; ?>
</head>
<body>
<div id="wrapper">
<?php include chooseInclude( $config->webcore_root.'/template_header.php', 'layout_error.html');?>
	<div id="page">
		<div id="content">
				<div style="clear: both;">&nbsp;</div>
				<?php include $config->webcore_root.'/template_blognav.php'; ?>

				<div class="entry">
				<?php 
				if ( $session->node ){
					
					$displaypost = new postObj( $config->post_directory.'/'.$session->node );
				}
				else {
					$displaypost = new postObj( 'latest' );
				}
					
				print $displaypost->page_output();

				if ( True ){
					/*
					 * Shows suggestions for other posts only if the 
					 * current post being shown happens to exist- ie, 
					 * it has a datestamp. Since datestamps will be automatically
					 * added to posts, all posts will have a datestamp.
					 */
					echo '<h5>You might also be interested in:</h5>';
					echo '<ul>';
					getSuggestions(3, $displaypost->tags);
					echo '</ul>';
					echo '<p><a href="index.php">Back to Blog Home</a></p>';
				}
				if ( $displaypost->datestamp and $session->node){
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
					print "<p>You're viewing the latest available post without comments.  To comment, visit <a href='".$displaypost->link."'>the post's page</a>.</p>";
				}
				?>
			</div>
		</div>
		<!-- end #content -->
	<div style="clear:both;">&nbsp;</div>
	</div>
</div>
<?php include chooseInclude( $config->webcore_root.'/template_footer.php', '../layout_error.html'); ?>
</body>
</html>
