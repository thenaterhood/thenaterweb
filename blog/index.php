<?php 
include '/home/natelev/www/static/core_blog.php';

$first_name = setVarFromURL('name', 'Guest', 42);
$track = setVarFromURL('track', '', 1);
$konami = setVarFromURL('konami', '', 0);
$start = setVarFromURL('start', 0, 0);
$end = setVarFromURL('end', 4, 0);
$id = setVarFromURL('id', blog, 18);
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
<?php include chooseInclude( getConfigOption('webcore_root').'/template_header.php', '../layout_error.html');?>
	<div id="page">
		<div id="content">
			<div class="post">
				<div style="clear: both;">&nbsp;</div>
				<?php include chooseInclude( getConfigOption('webcore_root').'/template_blognav.php', '../layout_error.html'); ?>
				<div class="entry">
				<?php
				if ( $id != "blog" ){
					include "$id";
					echo '<hr /><p><a href="index.php">Back to Blog home</a></p>';
				}
				else{ 
							getPosts($start,$end);
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
