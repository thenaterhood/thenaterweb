<?php
include '/home/natelev/www/static/core_web.php';
$postDir='entries';
class postObj {
    /*
     * Defines a data object to
     * contain a post as it is
     * read from a file. 
     */
     public $title, $tags, $date, $datestamp, $content;
     
     public function __construct($nodefile){
         /*
          * Reads in a nodefile and returns a data object
          * containing all of the data from it.
          * Accepts plaintext and json, but will prefer json
          * over plaintext because it is superior.
          */
        if (file_exists("$nodefile.json")){
            $jsoncontents = file_get_contents("$nodefile.json");
            $json_array = json_decode($jsoncontents, True);
            
            $this->title = $json_array['title'];
            $this->date = $json_array['date'];
            $this->tags = $json_array['tags'];
            $this->datestamp = $json_array['datestamp'];
            $this->content = implode($json_array['content']);
            
        }
        else{
         $file = fopen($nodefile, 'r');
         $this->title = rtrim(fgets($file), "\n");
         $this->date = rtrim(fgets($file), "\n");
         $this->tags = rtrim(fgets($file), "\n");
         $this->datestamp = rtrim(fgets($file), "\n");         
         $contents='';
         
         while(!feof($file)){
            $contents .= "<p>".rtrim(fgets($file), "\n"). "</p>\n";
         }
         
         $this->content = $contents;
         
         fclose($file);
     }
         
         
     }
 }
function getPostList(){
    /*
     * Creates a list of files in the working directory, sorts
     * and reverses the list, and returns it.  Intended for working
     * with blog posts stored as text files with date-coded filenames
     */
    $avoid = avoidFiles();
    $posts = array();
    $handler = opendir("entries");
    $i = 0;
    while ($file = readdir($handler)){
      // if file isn't this directory or its parent, or itself, add it to the results
      // We check if it's there already because we're migrating from plaintext to json
      // so there may be duplicates.
      if (  !in_array($file, $avoid) and !in_array($file, $posts) and !in_array(substr($file, 0, -5), $posts) ){
          if ( strpos($file,"json") ){
            $posts[] = substr($file, 0, -5);
        }
        else{
            $posts[] = $file;
        }
            $i++;
      }

    }
    
    sort($posts);
    $posts = array_reverse($posts);
    
    return $posts;
}

function retrievePost($node){
    /*
     * Retrieves the post (file) received as an argument
     * and adds appropriate formatting for it to be displayed.
     * Designed for use with plaintext blog posts with the format
     * 
     * TITLE
     * DISPLAY DATE
     * TAGS
     * FEED DATESTAMP
     * CONTENT
     * 
     * Isn't plaintext specific anymore because it uses the postObj
     * object for retrieving posts rather than doing it directly.
     * 
     */
    if (file_exists("entries/$node") or file_exists("entries/$node.json") ){
        $postData = new postObj("entries/$node");
        
    	$title = $postData->title;
    	$date = $postData->date;
        $tags = $postData->tags;
        $datestamp = $postData->datestamp;
    	/*** return the current line ***/
    	echo '<h3><a href="post.php?node='.$node.'" >'.$title."</a></h3>\n";
    	echo "<h4>".$date."</h4>\n";
    	
        echo $postData->content;
        
        echo "<h5><i>Tags: ".$tags."</i></h5>\n";

    }
    else{
	include "/home/natelev/www/static/template_error.php";
    }
}

function checkInventory(){
    /*
     * Checks the number of files in the current directory and
     * compares it to how many are listed in the current inventory.
     * If the number doesn't match, it returns False.
     */
    
    if ( ! file_exists('inventory.html') ){
        return False;
    }
    
    $existing = count( getPostList() );

    $inventory = 'inventory.html';
    $recorded = count(file($inventory));
    if ( $recorded == $existing ){
        return True;
    }
    
    return False;
    
}

function regenInventory(){
    /*
     * Regenerates the inventory file
     */
    $inventory = fopen('inventory.html', 'w');
    
    $avoid = avoidFiles();
    $handler = opendir('./');
    
    $posts = getPostList();
    
    foreach( $posts as $input ){
        
        $postData = new postObj("entries/$input");
        
        $title = $postData->title;
        $date = $postData->date;
        $tags = $postData->tags;

        $item = '<li><a href="post.php?node='.$input.'">'.$title.'</a><i> - '.$tags.'</i></li>'."\n";
        fwrite($inventory, $item);

    }
    
    fclose($inventory);
}

function RandomLine($filename) {
    /*
     * Returns a random line of a given file.
     * Used mainly for generating random suggestions 
     * for additional blog posts to read.
     */
    $lines = file($filename) ;
    return $lines[array_rand($lines)] ;
}

function getSuggestions($number, $tag){
    /*
     * Generates and displays a list of additional 'suggested' blog
     * posts.  Right now picks them randomly, but in the future might
     * rely on a better algorithm.
     * 
     * Arguments:
     *  $number (int): how many to generate and display
     *  $tag (string): a tag or tags to use for generating suggestions
     */
        $i = 0;
        while ($i < $number){
            echo RandomLine("inventory.html");
            $i++;
        }
        
    }
?>
