<?php 
include GNAT_ROOT.'/lib/core_blog.php';
include GNAT_ROOT.'/lib/core_redirect.php';
include 'class_blogdef.php';


$session = new session( array('name', 'track', 'konami', 'start', 'end', 'id', 'test', 'tag', 'node') );
$config = new config();
$blogdef = new blogdef();

# Manage friendly URL cases where supported
# on the blog system
if ( $config->friendly_urls ){
	if ( $session->id == 'post' ){
		$redirect = new condRedirect( "?id=post", '/'.$blogdef->id.'/read/'.$session->node.'.htm', $session->uri );
		$redirect->apply( 301 );
	}
}

# For compatibility with current header
$tagline = $blogdef->catchline;
$id = $blogdef->id;

include $config->webcore_root.'/html_doctype.html';
include $config->webcore_root.'/html_head.html';
?>

<body>
<div id="wrapper">
<?php include chooseInclude( $config->webcore_root.'/template_header.php', '../layout_error.html');?>
	<div id="page">
		<div id="content">
				<div style="clear: both;">&nbsp;</div>
				
				<?php include chooseInclude( 'static/template_blognav.php', '../layout_error.html'); ?>
				
				<div class="entry">
					
				<?php 

				$content = pullContent( array( 'static/page_'.$session->id , GNAT_ROOT.'/lib/pages/page_'.$session->id ) );

				if ( ! $content->isPhp() ){
					print $content->toHtml();
				}
				else{
					include $content->getFile();
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