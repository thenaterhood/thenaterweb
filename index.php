<?php 
include 'engine/core_web.php';
include 'engine/core_redirect.php';

$session = new session( array('name', 'track', 'konami', 'id', 'type') );
$config = new config();
$page = new page( $session->id );

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
	
include $config->webcore_root.'/html_doctype.html';

$page->display();
?>
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
