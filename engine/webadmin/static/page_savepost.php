<?php

	$postpath = '../../'.$_POST['blog'].'/entries';

	$postFname = date("Y.m.d").'.json';

	$postData = array();

	$postData['content'] = $_POST['content'];
	$postData['title'] = $_POST['title'];
	$postData['date'] = $_POST['date'];
	$postData['tags'] = $_POST['tags'];
	$postData['datestamp'] = date(DATE_ATOM);

	$postJsonData = json_encode($postData);

	$jsonFile = fopen($postpath.'/'.$postFname, 'w');
	fwrite($jsonFile, $postJsonData);
	fclose($jsonFile);

	print '<h1>Post Saved</h1>';
	print '<p>View this post at '.getConfigOption('site_domain').'/'.$_POST['blog'].'/'.$postFname.'</p>';


?>