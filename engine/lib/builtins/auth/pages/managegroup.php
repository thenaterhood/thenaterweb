
<a class="btn btn-success" href="/?url=auth/addgroup">Add Group</a>
<a class="btn btn-success" href="/?url=auth/manage">Manage Users</a>
<br />
<br />
<table class="table table-striped">
	<th>Group Name</th>

<?php
foreach ($page->groups as $g ) {

	echo '<tr>';
	$link = getConfigOption('site_domain').'/?url=auth/changegroup/gid/'.$g->id;
	
	print '<td><a href="'.htmlentities( $link ).'">'.$g->name.'</a></td>';
}

?>

</table>
