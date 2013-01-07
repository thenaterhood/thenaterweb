	<div id="siteversion">
	<!-- <p>view in: <a href="m/index.html">mobile</a> standard</p> -->
	<?php if ("$track" == "n") print "<table border='0' width='780' background='gray'><tr><td valign='middle' width='180px'><p>Your visit is not being tracked. </p></td>" ;
		  if ("$first_name" == "Guest") include "template_introduction2.php";
		  print "<td align='left'><p><a href='/?id=privacy'>(privacy information)</a></p></td></tr></table>";
	?>
	</div>
	<!-- This is the horizontal bar that runs across the top-ish area -->
	<div id="upperbar"></div>
	<div id="header">
		<div id="logo">
			<h1>Nate Levesque<?php if ("$id" == "home") print ""; else print "//$id"; ?></h1>
			<h2 id="catchline">Colloquially, TheNaterhood</h2>
		</div>
		<div id="logo2">
			<div class="siteurl">
			<ul class="siteurl">
			<li class="siteurl"><a class="nav" href="/?id=home">Home</a></li>
			<li class="siteurl"><a class="nav" href="/projects">Projects</a></li>
			<li class="siteurl"><a class="nav" href="/?id=about">About</a></li>
			<li class="siteurl"><a class="nav" href="/?id=connect">Connect</a></li>
			<li class="siteurl"><a class="nav" href="http://blog.thenaterhood.com">Blog</a></li>
			</ul>
		</div>
		</div>

	</div>
