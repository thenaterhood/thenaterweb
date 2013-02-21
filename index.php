<?php 
include '/home/natelev/www/static/core_web.php';

# Grab variables from the URL. Syntax for this is...
# name of variable, default value of variable, maxlength of variable
$first_name = setVarFromURL('name', $GLOBAL_CONFIG['default_visitor_name'], 42);
$track = setVarFromURL('track', '', 1);
$konami = setVarFromURL('konami', '', 0);
$id = setVarFromURL('id', 'home', 15);
$id = setIfEmpty($id, $_SERVER['REQUEST_URI']);

$current_domain = ";";
$current_domain = preg_replace('/^www\./i', '', $_SERVER['HTTP_HOST']);


// Checks for cookies and sets them (or refreshes them) if necessary

setcookie('name',$first_name,time() + (86400 * 30),"/","$current_domain"); // 86400 = 1 day
setcookie('track',$track,time() + (86400 * 30),"/","$current_domain"); // 86400 = 1 day
// Sets page options and variables

$page_content_file = "page_$id.html";
$page_description_file = "page_description_page_$id.txt";
$page_keywords_file = "page_keywords_$id.txt";

if ($track == "n"){
	$tracking = "/home/natelev/www/static/template_no-track.php";
	}
else {
	$tracking = "/home/natelev/www/static/template_tracking.php";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content='<?#php echo file_get_contents($page_keywords_file);
echo 'Nate Levesque, TheNaterhood, the naterhood'?>' />
<?php echo '<meta name="description" content="Nate Levesque" />'; ?>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo "Nate Levesque :: $id"; ?></title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css' />
<style type="text/css">
</style>
<?php include "$tracking"; ?>
</head>
<?php if ("$konami" == "pride") print '<style type="text/css">body {background: url(images/rainbow.jpg) fixed}</style>'; ?>
<body>
<div id="wrapper">
<?php include chooseInclude('/home/natelev/www/static/template_header.php', 'layout_error.html');?>
	<div id="page">
		<div id="content">
			<div class="post">
				<div style="clear: both;">&nbsp;</div>
				<div class="entry">
				<?php 
                include chooseInclude("/home/natelev/www/static/$page_content_file", '/home/natelev/www/static/template_error.php');
				?>
				</div>
			</div>
		<div style="clear: both;">&nbsp;</div>
		</div>
		<!-- end #content -->
<?php include chooseInclude('/home/natelev/www/static/template_sidebar.php', 'layout_error.html'); ?>
	</div>
</div>
<?php include chooseInclude('/home/natelev/www/static/template_footer.php', 'layout_error.html'); ?>
</body>
</html>
