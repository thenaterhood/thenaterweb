<?php 
include 'static/core_web.php';
include 'static/core_redirect.php';

$session = new session( array('name', 'track', 'konami', 'id', 'type') );
$config = new config();

# Grab variables from the URL. Syntax for this is...
# name of variable, default value of variable, maxlength of variable
$first_name = $session->name;
$track = $session->track;
$id = $session->id;
$type = '404';

if ( $config->friendly_urls ){
	$redirect = new condRedirect( "?id", "/page/".$session->id, $session->uri );
	$redirect->apply( 301 );
}


// Checks for cookies and sets them (or refreshes them) if necessary

setcookie('name',$first_name,time() + (86400 * 30),"/",$session->domain); // 86400 = 1 day
setcookie('track',$track,time() + (86400 * 30),"/",$session->domain); // 86400 = 1 day
// Sets page options and variables

$page_content_file = "page_$id.html";
	
include $config->webcore_root.'/core_xhtml.html';
?>

<head>
<meta name="keywords" content='Nate Levesque, TheNaterhood, the naterhood' />
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
<?php if ($session->konami == "pride") print '<style type="text/css">body {background: url(images/rainbow.jpg) fixed}</style>'; ?>
</head>
<body>
<div id="wrapper">
<?php include chooseInclude( $config->webcore_root.'/template_header.php', 'layout_error.html');?>
	<div id="page">
		<div id="content">
				<div style="clear: both;">&nbsp;</div>
				<div class="entry">
				<?php 
				include chooseInclude( $config->webcore_root."/$page_content_file", $config->webcore_root.'/template_error.php');
				?>
				</div>
		</div>
		<!-- end #content -->
	<div style="clear:both;">&nbsp;</div>

	</div>
</div>
<?php include chooseInclude( $config->webcore_root.'/template_footer.php', 'layout_error.html'); ?>
</body>
</html>
