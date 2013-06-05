<?php

	$postpath = '../../'.$_POST['blog'].'/entries';

	if ( !isset($_POST['file'] ) ){
		$postFname = date("Y.m.d").'.json';
		$nodename = date("Y.m.d");
	}

	else{
		$postFname = $_POST['file'];
		$nodename = substr($postFname, 0, strpos($postFname, '.json') );
	}


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

	$postURL = getConfigOption('site_domain').'/'.$_POST['blog'].'/index.php?id=post&node='.$nodename;

	if ( is_writeable( $postpath.'/'.$postFname ) ){
		print '<h1>Post Saved</h1>';
		print '<p>View this post at <a href="'.$postURL.'">'.$postURL.'</a></p>';
	}
	else{
		print '<h1>Post could not be saved</h1>';
		print '<p>The post could not be saved, likely because Gnat does not have write access to the location. 
		Your post is displayed below as entered so that you can copy and paste it elsewhere until the problem gets fixed.</p>';

		print '<h1>'.htmlspecialchars( $_POST['title'] ).'</h1>';
		print '<p>'.htmlspecialchars( $_POST['date'] ).'</p>';
		print '<p>'.htmlspecialchars( $_POST['tags'] ).'</p>';
		print '<p>'.htmlspecialchars( $_POST['content'] ).'</p>';
	}


?>