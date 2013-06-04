<?php

	$postpath = '../../'.$_POST['blog'].'/entries';

	$postFname = date("Y.m.d").'.json';

	$postData = array();

	$postData['content'] = $_POST['content'];
	$postData['title'] = $_POST['title'];
	$postData['date'] = $_POST['date'];
	$postData['tags'] = $_POST['tags'];
	$postData['datestamp'] = date(DATE_ATOM);
	$postData['updated'] = date(DATE_ATOM);

	$postJsonData = json_encode($postData);

	$jsonFile = fopen($postpath.'/'.$postFname, 'w');
	fwrite($jsonFile, $postJsonData);
	fclose($jsonFile);

	$postURL = getConfigOption('site_domain').'/'.$_POST['blog'].'/index.php?id=post&node='.date("Y.m.d");

	if ( is_writeable( $postpath.'/'.$postFname ) ){
		print '<h1>Post Saved</h1>';
		print '<p>View this post at <a href="'.$postURL.'">'.$postURL.'</a></p>';
	}
	else{
		print '<h1>Post could not be saved</h1>';
		print '<p>The post could not be saved, likely because Gnat does not have write access to the location. 
		Your post is displayed below so that you can copy and paste it elsewhere until the problem gets fixed.</p>';

		print htmlspecialchars( $_POST['title'] );
		print '<br />';
		print htmlspecialchars( $_POST['date'] );
		print '<br />';
		print htmlspecialchars( $_POST['tags'] );
		print '<br />';
		print htmlspecialchars( $_POST['content'] );
	}


?>