
<h4>Administering <?php print $pageData['id'] .' on '. getConfigOption('site_domain'); ?></h4>

<ul>
<li style="list-style-image: url('/assets/tango/22x22/actions/document-new.png')">
	<a href="<?php print getConfigOption('site_domain').'/'.$pageData['id'].'/manage'; ?>/newpost">Create a new post or article</a>
</li>
<li style="list-style-image: url('/assets/tango/22x22/apps/accessories-text-editor.png')">
	<a href="<?php print getConfigOption('site_domain').'/'.$pageData['id'].'/manage'; ?>/editpost">Edit an existing post or article</a>
</li>
<li style="list-style-image: url('/assets/tango/22x22/emblems/emblem-system.png')">
	<a href="<?php print getConfigOption('site_domain').'/'.$pageData['id'].'/manage'; ?>/editblog">Edit blog configuration</a>
</li>
</ul>