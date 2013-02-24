<?php 
include '/home/natelev/www/static/core_blog.php';


$session = new session( array('name', 'track', 'konami', 'node') );
$config = new config();

$first_name = $session->name;
$track = $session->track;
$id = 'Blog';
?>

<?php include $config->webcore_root.'/core_xhtml.html'; ?>
<head>
<meta name="keywords" content='<?#php echo file_get_contents($page_keywords_file);
echo 'Nate Levesque, TheNaterhood, the naterhood'?>' />
<?php echo '<meta name="description" content="Nate Levesque" />'; ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo "Nate Levesque :: $id"; ?></title>
<link href="../style.css" rel="stylesheet" type="text/css" media="screen" />
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
				$displaypost = new postObj( $config->post_directory.'/'.$session->node );
				print $displaypost->page_output();
				
				if ( $displaypost->datestamp ){
					/*
					 * Shows suggestions for other posts only if the 
					 * current post being shown happens to exist- ie, 
					 * it has a datestamp. Since datestamps will be automatically
					 * added to posts, all posts will have a datestamp.
					 */
					echo '<h5>If you liked this, you might also like...</h5>';
					echo '<ul>';
					getSuggestions(3, $displaypost->tags);
					echo '</ul>';
					echo '<p><a href="index.php">Back to Blog Home</a></p>';
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
