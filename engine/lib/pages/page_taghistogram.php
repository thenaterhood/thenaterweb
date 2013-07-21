<?php
/*
 * Author: Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: tags
 * 
 * Description:
 * 	Manages retrieving tags and posts via tags.  Is not intented
 * 	to be called standalone and should be included in another file
 * 	that already has established config and session instances.
 */
include_once GNAT_ROOT.'/lib/core_histogram.php';

$tag = $session->tag;
$inventory = new inventory( $blogdef->post_directory );


if ( $tag ){
	/*
	 * If a tag was requested, display which tag is being browsed
	 * with a link back to the main page of tags
	 */
    print '<p><strong>Browsing tag "'.$tag.'".  </strong><a href="?id=tags">View all tags.</a></p>'."\n";

}

if ( !$tag ){
    /*
     * Check if a tag was requested, otherwise list the available
     * tags.
     */
    echo "<table>";

    $tags = $inventory->getFieldStatistics( 'tags' );
    ksort($tags);
    $histogram = generateHistogramNospace( $tags, "&#9646;" );
    $letter = "";
    foreach( $histogram as $item => $graph ){
        if ( substr($item, 0, 1) != $letter ){
            $letter = substr($item, 0, 1);
            print '<tr><td><a id="alph_'.$letter.'"></a><strong>'.$letter.'</strong></td></tr>'."\n";
        }
        echo '<tr><td><a href="index.php?id=tags&amp;tag='.$item.'">'.$item.'</a></td>';
        echo '<td>'.$graph.'<em> '.$tags[$item].' posts</em></td></tr>'."\n";
    }

    echo "</table>";

}
else{
    echo "<ul>";
    /*
     * If a tag was requested, call getByTag to print all posts
     * with the tag.
     */
    $matching = $inventory->select( 'tags', $tag );

    foreach ($matching as $item) {
        print '<li><a href="'.htmlentities( $item['link'] ).'">'.$item['title'].'</a></li>';
    }

    echo "</ul>";

}


?>
