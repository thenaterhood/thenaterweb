<?php

include '/home/natelev/www/static/core_atom.php';
include '/home/natelev/www/static/core_blog.php';

function generateFeed(){
    $posts = getPostList();
    
    $atom = new atom_channel("The Philosophy of Nate", "http://blog.thenaterhood.com/", "It's the cyber age, stay in the know.", date(DATE_ATOM) );

    /*
     * Lists the files in a directory and returns an array of them
     * out to the given length section
     * 
     * Arguments:
     *  $section (int): a range of posts to retrieve
     * Returns:
     *  $posts (array): an array of posts retrieved
     * 
    */
    
    for ($i = 0; $i < count($posts); $i++){
        
        $newitem = new postObj("entries/$posts[$i]");
        
        #$file = fopen("entries/$posts[$i]", 'r');
        $title = $newitem->title;
        $date = $newitem->date;
        $datestamp = $newitem->datestamp;
        $content = htmlspecialchars($newitem->content);
        
        $link = "http://www.thenaterhood.com/blog/post.php?node=".$posts[$i];
        $atom->new_item($title, $link, $content, $datestamp);

        
        #fclose($file);
    }


    #$rss->new_item("Earnings Report", "http://example.com/earnings", "Earnings Report for Example.com");

    #$rss->new_item("Stockholder's Meeting", "http://example.com/shmeeting", "Stockholder's Meeting Announcement");

    #$rss->new_item("CEO Speech", "http://example.com/ceospeech", "Example CEO Delivers Speech");
    return $atom;
}

$regen = setVarFromURL(regen, '', 4);
$feedIsCurrent = checkInventory();
$autoRegen = getConfigOption('auto_feed_regen');
$feedLocation = getConfigOption('dynamic_directory');
$saveFeed = getConfigOption('save_dynamics');

if ( ! $autoRegen && ! $regen && file_exists("$feedLocation/feed.xml") ){
    /*
     * If the inventory matches the existing number of items in the
     * directory, return the static feed file
     */
    include "$feedLocation/feed.xml";
}

else{
    /*
     * If the inventory doesn't match the existing number of items in
     * the directory, regenerate the inventory and the feed file
     * then return the feed file
     */
    if ( $regen || ! $feedIsCurrent || ! file_exists("$feedLocation/feed.xml") ){
        regenInventory();
        $feed = generateFeed();
        $file = fopen("$feedLocation/feed.xml", 'w');
        fwrite($file, $feed->output());
        fclose($file);
    }
    include "$feedLocation/feed.xml";

}


?>
