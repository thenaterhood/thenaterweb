<?php
/*
 * Author: Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: tags
 * 
 * Description:
 *  Manages retrieving tags and posts via tags.  Is not intented
 *  to be called standalone and should be included in another file
 *  that already has established config and session instances.
 */
include_once GNAT_ROOT.'/lib/core_histogram.php';

$tag = $page->session->tag;


if ( $tag ){
    /*
     * If a tag was requested, display which tag is being browsed
     * with a link back to the main page of tags
     */
    print '<p><strong>Browsing tag "'.$tag.'".  </strong><a href="/?url='.$page->blogid.'/tags">View all tags.</a></p>'."\n";

}

if ( !$tag ){
    /*
     * Check if a tag was requested, otherwise list the available
     * tags.
     */
    echo "<table>";

    $tags = $page->tags;
    ksort($tags);
    $histogram = generateHistogram2D( $tags, "&#9646;" );
    $letter = "";
    foreach( $histogram as $item => $graph ){
        if ( substr($item, 0, 1) != $letter ){
            $letter = substr($item, 0, 1);
            print '<tr><td><a id="alph_'.$letter.'"></a><strong>'.$letter.'</strong></td></tr>'."\n";
        }
        $clean = str_replace( " ", '%20', $item);
        echo '<tr><td><a href="/?url='.$page->blogid.'/tags/tag/'.$clean.'">'.$item.'</a></td>';
        echo '<td>'.$graph.'<em> '.count($tags[$item]).' posts</em></td></tr>'."\n";
    }

    echo "</table>";

}
else{
    echo "<ul>";
    /*
     * If a tag was requested, call getByTag to print all posts
     * with the tag.
     */
    $tags = $page->tags;

    if ( array_key_exists($tag, $tags)){
        $matching = $tags[$tag];

        foreach ($matching as $nodeid => $title ) {
            $link = getConfigOption( 'site_domain' ).'/?url='.$page->blogid.'/read/'.$nodeid.'.htm';
            print '<li><a href="'.htmlentities( $link ).'">'.$title.'</a></li>';
        }

        echo "</ul>";
    } else {
        echo "<p>No posts found.</p>";
    }

}


?>
