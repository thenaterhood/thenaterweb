

	

	<?php

	if ( $page->hasStack ){
		echo '<table class="table table-striped">';

		foreach ($page->stack as $l) {
			echo '<tr><td>' . $l . '</td></tr>';
		}

		echo '</table>';
	} else {
		echo '<p>' . $page->text . '</p>';
	}

	?>

