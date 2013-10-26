<?php
/**
* Encodes and saves an article into the 
* appropriate location. Just a basic script,
* nothing too fancy.
*
* @since 06/05/2013
*/
$postpath = $_POST['postpath'];

if ( $_POST['file'] == '' ){
	$nodeDate = date("Y.m.d");
	$nodename = $nodeDate.'.0';
	$postFname = $nodename.'.json';

	$i = 0;
	while ( file_exists( $postpath.'/'.$postFname ) ){
		$i++;
		$nodename = $nodeDate.'.'.$i;
		$postFname = $nodename.'.json';

	}
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
$postFile = $postpath.'/'.$postFname;

$lock = new lock( $postFile );

$postURL = getConfigOption('site_domain').'/'.$_POST['blog'].'/index.php?id=post&node='.$nodename;
$writetest = fopen( $postpath.'/writetest.txt', 'w' );
fclose( $writetest );

if ( is_writeable( $postpath.'/writetest.txt' ) && !$lock->isLocked() ){

	$lock->lock();

	$jsonFile = fopen($postpath.'/'.$postFname, 'w');
	fwrite($jsonFile, $postJsonData);
	fclose($jsonFile);

	$lock->unlock();

	unlink( $postpath.'/writetest.txt');

	print '<h1>Post Saved</h1>';
	print '<p>View this post at <a href="'.$postURL.'">'.$postURL.'</a></p>';
}
else{
	print '<h1>Post could not be saved</h1>';
	print '<p>The post could not be saved, likely because Gnat does not have write access to the location. 
	Your post is displayed below as entered so that you can copy and paste it elsewhere until the problem gets fixed.</p>
	<hr />';
	print '<h1>'.htmlspecialchars( $_POST['title'] ).'</h1>';
	print '<p>'.htmlspecialchars( $_POST['date'] ).'</p>';
	print '<p>'.htmlspecialchars( $_POST['tags'] ).'</p>';
	print '<p>'.htmlspecialchars( $_POST['content'] ).'</p>';
}



?>
<p><a href="<?php print getConfigOption('site_domain'); ?>/webadmin">Back to webadmin panel</a></p>