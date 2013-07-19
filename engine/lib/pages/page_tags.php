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

$tag = $session->tag;
$inventory = new inventory( $blogdef->post_directory );


if ( $tag ){
    /*
     * If a tag was requested, display which tag is being browsed
     * with a link back to the main page of tags
     */
    print '<p><strong>Browsing tag "'.$tag.'".  </strong><a href="?id=tags">View all tags.</a></p>'."\n";

}
echo "<ul>";

if ( !$tag ){
    /*
     * Check if a tag was requested, otherwise list the available
     * tags.
     */
    $tags = $inventory->selectField( 'tags' );
    sort($tags);
    $letter = "";
    foreach( $tags as $item ){
        if ( substr($item, 0, 1) != $letter ){
            $letter = substr($item, 0, 1);
            print '<li><a id="alph_'.$letter.'"></a><strong>'.$letter.'</strong></li>'."\n";
        }
        echo '<li><a href="index.php?id=tags&amp;tag='.$item.'">'.$item.'</a></li>'."\n";
    }
}
else{
    /*
     * If a tag was requested, call getByTag to print all posts
     * with the tag.
     */
    $matching = $inventory->select( 'tags', $tag );

    foreach ($matching as $item) {
        print '<li><a href="'.htmlentities( $item['link'] ).'">'.$item['title'].'</a></li>';
    }

}

echo "</ul>";

?>
