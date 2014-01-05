
<h4>Administering <?php print getConfigOption('site_domain'); ?></h4>
<table class="table table-striped table-condensed">
	<tr>
		<th>Application Name</th>
		<th>Manage</th>
	</tr>

	<?php 

	foreach ($page->apps as $app) {
		print '<tr>';
			print '<td>';
			print $app->id;
			print '</td>';
			print '<td>';
			if ( method_exists($app, 'manage') )
				print "<a class='btn btn-success' href='".getConfigOption('site_domain').'/?url='.$app->id.'/manage'."'>Manage</a>";
			else
				print "<a class='btn disabled' href='#'>No Management Services.</a>";
			print '</td>';
		print '</tr>';

	}

	?>

</table>


<ul>
<li style="list-style-image: url('/assets/tango/22x22/emblems/emblem-system.png')">
	<a href="<?php print getConfigOption('site_domain'); ?>/?url=webadmin/editconf">Modify engine configuration</a>
</li>
<li style="list-style-image: url('/assets/tango/22x22/apps/utilities-system-monitor.png')">
	<a href="<?php print getConfigOption('site_domain'); ?>/?url=webadmin/checkinstall">Check installation</a>
</li>
</ul>