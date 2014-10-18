
<a class="btn btn-success" href="<?php print $page->urlBase; ?>addgroup">Add Group</a>
<a class="btn btn-success" href="<?php print $page->urlBase; ?>manage">Manage Users</a>
<br />
<br />
<table class="table table-striped">
	<th>Group Name</th>

<?php
foreach ($page->groups as $g ) {

	echo '<tr>';
	$link = $page->urlBase.'changegroup/gid/'.$g->id;
	
	print '<td><a href="'.htmlentities( $link ).'">'.$g->name.'</a></td>';
}

?>

</table>
