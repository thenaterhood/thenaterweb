<?php 
// Picks up any variables
$first_name = $_GET['name'];
$track = $_GET['track'];
$konami = $_GET['konami'];
// Checks for cookies and sets them (or refreshes them) if necessary
if (empty($first_name)) $first_name = $_COOKIE['visitor_name'];
if (empty($track)) $track = $_COOKIE['track'];
setcookie('visitor_name',$first_name,time() + (86400 * 30),"/","thenaterhood.com"); // 86400 = 1 day
setcookie('visitor_name',$first_name,time() + (86400 * 30),"/","natelevesque.com"); // 86400 = 1 day
setcookie('track',$track,time() + (86400 * 30),"/","thenaterhood.com"); // 86400 = 1 day
setcookie('track',$track,time() + (86400 * 30),"/","natelevesque.com"); // 86400 = 1 day
// Sets page options and variables
if (empty($first_name)) $first_name = "Guest";
if (empty($track)) $track = "";
$id = $_GET['id'];
if (empty($id)) $id = "home";
$page_content_file = "page_$id.html";
$page_description_file = "page_$id_description.txt";
$page_keywords_file = "page_$id_keywords.txt";
if ("$track" != "n") $tracking = "template_tracking.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php print '<meta name="keywords" content="nate levesque, thenaterhood" />'; ?>
<?php print '<meta name="description" content="Nate Levesque" />'; ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php print "Nate Levesque :: $id"; ?>
</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css' />
<style type="text/css">
</style>
<?php include "$tracking"; ?>
</head>
<?php if ("$konami" == "pride") print '<style type="text/css">body {background: url(images/rainbow.jpg) fixed}</style>'; ?>
<body>
<div id="wrapper">
<?php include "template_header.php";?>
	<div id="page">
	
		<div id="content">
			<div class="post">

				<div style="clear: both;">&nbsp;</div>
				<div class="entry">
				<?php if ("$first_name" == "Guest") include "template_introduction.php"; ?>
				<?php if (file_exists("$page_content_file")) include "$page_content_file"; else include "template_error.php"; ?>
				</div>
			</div>
		<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
<?php include "template_sidebar.php"; ?> 
	</div>
</div>
<?php include "template_footer.php"; ?>
</body>
</html>
