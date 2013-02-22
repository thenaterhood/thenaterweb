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
$first_name = setVarFromURL('name', 'Guest', 42);
$track = setVarFromURL('track', '', 1);
$konami = setVarFromURL('konami', '', 0);
$node = setVarFromURL('node', '', 25);
$id = 'Blog';
?>

<?php include "/home/natelev/www/static/core_xhtml.html"; ?>
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
</head>
<?php if ("$konami" == "pride") print '<style type="text/css">body {background: url(images/rainbow.jpg) fixed}</style>'; ?>
<body>
<div id="wrapper">
<?php include chooseInclude('/home/natelev/www/static/template_header.php', 'layout_error.html');?>
	<div id="page">
		<div id="content">
			<div class="post">
				<div style="clear: both;">&nbsp;</div>
				<?php include "/home/natelev/www/static/template_blognav.php"; ?>

				<div class="entry">
				<?php 
				retrievePost($node);
				echo '<h5>If you liked this, you might also like...</h5>';
				echo '<ul>';
				getSuggestions(3, '');
				echo '</ul>';
				echo '<p><a href="index.php">Back to Blog Home</a></p>';
				?>
				</div>
			</div>
		<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
<?php include chooseInclude('/home/natelev/www/static/template_sidebar.php', '../layout_error.html'); ?>
	</div>
</div>
<?php include chooseInclude('/home/natelev/www/static/template_footer.php', '../layout_error.html'); ?>
</body>
</html>
