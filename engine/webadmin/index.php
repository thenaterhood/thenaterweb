<?php 
include GNAT_ROOT.'/lib/core_web.php';


$session = new session( array('name', 'track', 'konami', 'start', 'end', 'id', 'test', 'tag', 'node', 'blogid') );
$config = new config();

# For compatibility with current header
$id = $session->id;

include $config->webcore_root.'/html_doctype.html';
include $config->webcore_root.'/html_head.html';
?>

<body>
<div id="wrapper">
<?php include chooseInclude( $config->webcore_root.'/template_header.php', '../layout_error.html');?>
	<div id="page">
		<div id="content">
				<div style="clear: both;">&nbsp;</div>
								
				<div class="entry">
					
				<?php 

				$content = pullContent( array( 'static/page_'.$session->id, 'static/hidden_'.$session->id ) );

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
