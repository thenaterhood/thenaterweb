<?php 
include '/home/natelev/www/static/core_blog.php';


function setTitle($node){
	if (file_exists("$node")){
	$file = fopen($node, 'r');
	$title = fgets($file);
	fclose($file);
	return $title;
	}
	else{
	return "Error";
	}
}
$first_name = setVarFromURL('name', getConfigOption('default_visitor_name'), 42);
$track = setVarFromURL('track', '', 1);
$konami = setVarFromURL('konami', '', 0);
$node = setVarFromURL('node', '', 25);
$id = 'Blog';
?>

<?php include getConfigOption('webcore_root').'/core_xhtml.html'; ?>
<head>
<meta name="keywords" content='<?#php echo file_get_contents($page_keywords_file);
echo 'Nate Levesque, TheNaterhood, the naterhood'?>' />
<?php echo '<meta name="description" content="Nate Levesque" />'; ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo "Nate Levesque :: $id"; ?></title>
<link href="../style.css" rel="stylesheet" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css' />
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
	print getConfigOption('tracking_code');
}
?>
</head>
<?php if ("$konami" == "pride") print '<style type="text/css">body {background: url(images/rainbow.jpg) fixed}</style>'; ?>
<body>
<div id="wrapper">
<?php include chooseInclude( getConfigOption('webcore_root').'/template_header.php', 'layout_error.html');?>
	<div id="page">
		<div id="content">
			<div class="post">
				<div style="clear: both;">&nbsp;</div>
				<?php include getConfigOption('webcore_root').'/template_blognav.php'; ?>

				<div class="entry">
				<?php 
				$displaypost = new postObj( getConfigOption('post_directory').'/'.$node );
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
					getSuggestions(3, '');
					echo '</ul>';
					echo '<p><a href="index.php">Back to Blog Home</a></p>';
				}
				?>
				</div>
			</div>
		<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
<?php include chooseInclude( getConfigOption('webcore_root').'/template_sidebar.php', '../layout_error.html'); ?>
	</div>
</div>
<?php include chooseInclude( getConfigOption('webcore_root').'/template_footer.php', '../layout_error.html'); ?>
</body>
</html>
