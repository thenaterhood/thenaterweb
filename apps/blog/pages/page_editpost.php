
<h1>Edit a Post</h1>

<?php

$postData = array();

if ( property_exists($page, 'isNew') && $page->isNew ){

	$postData['title'] = '';
	$postData['tags'] = '';
	$postData['content'] = '';
	$postData['file'] = '';

	$postData = (object)$postData;


} else {

	$postData = $page->post;


}




?>

<form name="create" action="

	<?php 

	if ( isset($page->isNew) ){
		print $page->urlBase.'savepost'; 
	}else {
		print $page->urlBase.'updatepost/node/'; 

	}


	?>" 

	method="post">
	<input type='hidden' name='<?php print $page->csrf_key; ?>' value='<?php print $page->csrf_token; ?>' />

	<br />
	<textarea name="title" rows="1" cols="100" placeholder='Post Title'><?php print $postData->title; ?></textarea><br />
	Tags - comma separated: <br />
	<textarea name="tags" rows="1" cols="100" placeholder='Tags'><?php print $postData->tags; ?></textarea><br />
	Write your post - <strong>full html required</strong>: <br />
	<textarea name="content" rows="50" cols="100" ><?php print $postData->content; ?></textarea><br />
	<input type="hidden" name="node" value="<?php print $page->node; ?>" />
	<input type="submit" class='btn btn-success' value="Save" />
	<a class='btn btn-danger' href='<?php print $page->urlBase.'manage'; ?>'>Discard</a>

</form>
<br />
<br />
