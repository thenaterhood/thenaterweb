	<div id="siteversion">
	<!-- <p>view in: <a href="m/index.html">mobile</a> standard</p> -->
	<?php print "<table class='introduction'><tr>";
				if ($session->track == "n") print "<td><p>Your visit is not being tracked. </p></td>" ;
				if ($session->name == getConfigOption('default_visitor_name') ) include "template_introduction.php";
				print "<td class='introduction'><p><a href='/?id=privacy'>privacy information</a></p></td>";
				print "</tr></table>";
	?>
	</div>
	<!-- This is the horizontal bar that runs across the top-ish area -->
	<div id="upperbar"></div>
	<div id="header">
		<div id="logo">
			<h1><a <?php if ("$id" != "home") print "id='altHeader' " ?>href='/?id=home'>Nate Levesque</a>{ <?php if ("$id" == "home") print ""; else print "$id"; ?> };</h1>
			<?php if ("$id" == "home") print "<h2 id='catchline'>Colloquially, TheNaterhood</h2>"; else print "<h2></h2>" ?>
		</div>
		<div id="logo2">
			<div class="siteurl">
			<ul class="siteurl">
			<li class="siteurl">{ <a class="nav" href="/?id=home">Home</a></li>
			<li class="siteurl"><a class="nav" href="/?id=projects">Projects</a></li>
			<li class="siteurl"><a class="nav" href="/?id=about">Bio</a></li>
			<li class="siteurl"><a class="nav" href="/?id=connect">Connect</a></li>
		    <li class="siteurl"><a class="nav" href="/blog">Blog</a> }</li>
			</ul>
		</div>
		</div>

	</div>
