<?php 
include '../engine/core_blog.php';


$session = new session( array('name', 'track', 'konami', 'start', 'end', 'id', 'test', 'tag') );
$config = new config();

# For compatibility with current header
$id = 'blog';

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
					
					<?php include chooseInclude( 'static/page_'.$session->id.'.html', $config->webcore_root.'template_error.html' ); ?>
					
				</div>
		</div>
		<!-- end #content -->
	<div style="clear:both;">&nbsp;</div>
	</div>
</div>
<?php include chooseInclude( $config->webcore_root.'/template_footer.php', '../layout_error.html'); ?>
</body>
</html>
