
<h4>Administering <?php print $page->id .' on '. getConfigOption('site_domain'); ?></h4>

<ul>
<li style="list-style-image: url('/assets/tango/22x22/actions/document-new.png')">
	<a href="<?php print $page->urlBase.'newpost'; ?>">Create a new post or article</a>
</li>
<li style="list-style-image: url('/assets/tango/22x22/apps/accessories-text-editor.png')">
	<a href="<?php print $page->urlBase.'editpost'; ?>">Edit an existing post or article</a>
</li>
<li style="list-style-image: url('/assets/tango/22x22/emblems/emblem-system.png')">
	<a href="<?php print $page->urlBase.'editblog'; ?>">Edit blog configuration</a>
</li>
</ul>
