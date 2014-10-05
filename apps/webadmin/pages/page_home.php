
<h4>Administering <?php print \Naterweb\Engine\Configuration::get_option('site_domain'); ?></h4>

<ul>
<li style="list-style-image: url('/assets/tango/22x22/emblems/emblem-system.png')">
	<a href="<?php print $page->urlBase.'editconf'?>">Modify engine configuration</a>
</li>
<li style="list-style-image: url('/assets/tango/22x22/apps/utilities-system-monitor.png')">
	<a href="<?php print $page->urlBase; ?>checkinstall">Installation details/status</a>
</li>
</ul>
<br />
<h4>Manage Installed Applications</h4>
<p><em>Note: Not all applications can be shown in this list. To list all applications, visit the installation details page.</em></p>
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
			if ( method_exists($app, 'manage') ){
				print "<a class='btn btn-success' href='/?url=".$app->id.'/manage'."'>Manage</a>";
                        } else {
				print "<a class='btn disabled' href='#'>No Management Services.</a>";
                        }
			print '</td>';
		print '</tr>';

	}

	?>

</table>
