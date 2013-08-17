<?php 
include GNAT_ROOT.'/lib/core_web.php';
include GNAT_ROOT.'/lib/core_redirect.php';

$session = new session( array('name', 'track', 'konami', 'id', 'type') );
$config = new config();
$registerExtensions = array( "evenExtension" );
$extensions = loadExtensions( $session, $registerExtensions );

# Grab variables from the URL. Syntax for this is...
# name of variable, default value of variable, maxlength of variable
$id = $session->id;
$tagline = "";
$type = '404';

if ( $config->friendly_urls ){
	$redirect = new condRedirect( "?id", "/page/".$session->id, $session->uri );
	$redirect->apply( 301 );
}


// Checks for cookies and sets them (or refreshes them) if necessary

setcookie('name',$session->name,time() + (86400 * 30),"/",$session->domain); // 86400 = 1 day
setcookie('track',$session->track,time() + (86400 * 30),"/",$session->domain); // 86400 = 1 day
// Sets page options and variables

$page_content_file = "page_$id.html";
	
include $config->webcore_root.'/html_doctype.html';
include $config->webcore_root.'/html_head.html';
?>


<body>
<div id="wrapper">
	
<?php include chooseInclude( $config->webcore_root.'/template_header.php', 'layout_error.html');?>

	<div id="page">
		<div id="content">
				<div style="clear: both;">&nbsp;</div>
				<div class="entry">
					
				<?php 

				print getPreface( $extensions );

				$content = pullContent( array( 'static/page_'.$session->id, 'static/hidden_'.$session->id ) );

				if ( ! $content->isPhp() ){
					print $content->toHtml();
				}
				else{
					include $content->getFile();
				}

				print getPost( $extensions );
				
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
