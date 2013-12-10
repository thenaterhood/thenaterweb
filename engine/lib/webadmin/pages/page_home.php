
<h4>Administering <?php print getConfigOption('site_domain'); ?></h4>
<table class="table table-striped table-condensed">
	<tr>
		<th>Application Name</th>
		<th>Manage</th>
	</tr>

	<?php 

	foreach ($pageData['apps'] as $app) {
		echo '<tr>';
			echo '<td>'
			echo $app->id;
			echo '</td>';
			echo '<td>';
			if ( method_exists($app, 'manage') )
				echo "<a class='btn btn-success' href='".getConfigOption('site_domain').'/'.$app->id.'/manage'.">Manage</a>";
			else
				echo "Cannot be managed from webadmin.";
			echo '</td>';
		echo '</tr>';

	}

	?>

</table>


<ul>
<li style="list-style-image: url('/assets/tango/22x22/emblems/emblem-system.png')">
	<a href="<?php print getConfigOption('site_domain'); ?>/webadmin/editconf">Modify engine configuration</a>
</li>
<li style="list-style-image: url('/assets/tango/22x22/apps/utilities-system-monitor.png')">
	<a href="<?php print getConfigOption('site_domain'); ?>/webadmin/checkinstall">Check installation</a>
</li>
</ul>