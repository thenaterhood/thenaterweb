<?php 
include '../static/core_blog.php';


$session = new session( array('name', 'track', 'konami', 'start', 'end', 'id', 'test', 'tag') );
$config = new config();

# For compatibility with current header
$track = $session->track;
$first_name = $session->name;
$id = 'blog';
?>

<?php include $config->webcore_root.'/core_xhtml.html'; ?>
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
if ($session->track == "n"){
	print "<!-- Tracking code removed from page by request -->";
}
else {
	print $config->tracking_code;
}
?>
<?php if ($session->konami == "pride") print '<style type="text/css">body {background: url(/images/rainbow.jpg) fixed}</style>'; ?>

</head>
<body>
<div id="wrapper">
<?php include chooseInclude( $config->webcore_root.'/template_header.php', '../layout_error.html');?>
	<div id="page">
		<div id="content">
				<div style="clear: both;">&nbsp;</div>
				<?php include chooseInclude( $config->webcore_root.'/template_blognav.php', '../layout_error.html'); ?>
				<div class="entry">
				<?php
				if ( $session->id != 'home' ){
					include chooseInclude( $session->id, $config->webcore_root.'/template_error.php' );
					echo '<hr /><p><a href="index.php">Back to Blog home</a></p>';
				}
				else{ 
					if ( $session->start == 42 ){
						print "<p style='font-size:2em;'>42! It's the meaning of life, the universe, and everything!</p><br />\n";
					}
					getPosts($session->start,$session->end);
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
