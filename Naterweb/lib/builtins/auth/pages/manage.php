
<a class="btn btn-success" href="<?php print $page->urlBase; ?>adduser">Add User</a>
<a class="btn btn-success" href="<?php print $page->urlBase; ?>managegroup">Manage Groups</a>
<br />
<br />
<table class="table table-striped">
	<th>Username</th>
	<th>Real Name</th>
	<th>Active</th>

<?php
foreach ($page->users as $u ) {

	echo '<tr>';
	$link = $page->urlBase.'changeuser/uid/'.$u->id;
	
	print '<td><a href="'.htmlentities( $link ).'">'.$u->username.'</a></td>';
	print '<td>'.$u->first_name.' '.$u->last_name.'</td>';

	if ( $u->active )
		print '<td>Yes</td>';
	else
		print '<td>No</td>';

	echo '</tr>';
}

?>

</table>
