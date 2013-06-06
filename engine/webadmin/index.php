<?php 
include '../core_web.php';


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
				$include = getContent( 'static/page_'.$session->id, $config->webcore_root.'/template_error.php');

				print $include['pre'];

				if ( !$include['sanitize'] ){
					include $include['file'];
				}
				else{
					print htmlspecialchars( file_get_contents($include['file']) );
				}

				print $include['post'];
				//$content = new content( $session->id, $session );
				//$content->output();
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
