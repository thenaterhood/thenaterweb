<?php 
include GNAT_ROOT.'/lib/core_blog.php';


$session = new session( array('name', 'track', 'konami', 'start', 'end', 'id', 'test', 'tag', 'node') );
$config = new config();

# Set the ID of the blog here
$id = "Blog";

$blogdef = loadBlogConf( strtolower($id) );


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

				//print getPreface( $extensions );

				$content = pullContent( array( 'static/page_'.$session->id, 'static/hidden_'.$session->id, GNAT_ROOT.'/lib/pages/page_'.$session->id ) );

				if ( ! $content->isPhp() ){
					print $content->toHtml();
				}
				else{
					include $content->getFile();
				}

				//print getPost( $extensions );
				
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
