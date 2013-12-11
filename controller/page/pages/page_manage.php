<h3>Manage Pages (<?php print $pageData['id']; ?>)</h3>

<table class='table table-striped table-condensed'>

	<tr>
		<th>Page name</th>
		<th>Edit</th>
		<th>View</th>
	</tr>

	<?php

	foreach ( $pageData['pages'] as $file => $page ) {

		print '<tr>';
		print '<td>';
		$pathinfo = pathinfo( $file );
		print $pathinfo['filename'];
		print '</td>';

		print '<td>';
		print '<a class="btn btn-success" href="'.$pageData['apphome'].'/?url=edit/page/'.$pathinfo['filename'].'">Edit</a>';
		print '</td>';
		print '<td>';
		print '<a class="btn btn-success" href="'.$page.'">View</a>';
		print '</td>';
		print '</tr>';

	
	}
	?>

</table>
