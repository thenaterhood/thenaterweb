<div style="width:570px;position:relative;float:left;margin-right: 0px;padding-right: 10px; border-right: 0px dotted;">
						<?php
						if ( $session->id != 'home' ){
							include chooseInclude( $session->id, $config->webcore_root.'/template_error.php' );
							echo '<hr /><p><a href="index.php">Back to Blog home</a></p>';
						}
						else{ 
							if ( $session->start == 42 ){
								print "<p style='font-size:2em;'>42! It's the meaning of life, the universe, and everything!</p><br />\n";
							}
							getPosts( $blogdef->id, $blogdef->post_directory, $session->start,$session->end);
						}
						?>
					</div>
					
					<div style="width:150px;float:right;position:relative;height: 100%;margin: 5px;">
							<?php
								if ( $session->id != 'tags' ){
									print '<br /><h5>Looking for something?</h5>';
									include GNAT_ROOT.'/lib/pages/page_tags.php';
								}
							?>					
					</div>