<?php
include '/home/natelev/www/static/core_sitemap.php';
include '/home/natelev/www/static/core_web.php';

function createSitemap($localpath, $webpath, $delimeters){
    /*
     * Generates a sitemap given a list of local and web paths
     * which correspond to each other
     * 
     * Arguments:
     *  $localpath (list): a list of local paths to search for files in
     *  $webpath (list): a list of web addresses, which correspond to the localpaths
     *  $delimeters (list): a list of file prefixes to search for in the dir
     * 
     * Returns:
     *  $sitemap (sitemap): an xml sitemap
     */
    $sitemap = new urlset();
    
    for ($i = 0; $i < count($localpath); $i++){
        $path = $localpath[$i];
        $dir = opendir("$path");
        $search = $delimeters[$i];
        while (false !== ($file = readdir($dir))) {

                if ( strpos($file, $search) === 0 and !in_array($file, avoidFiles()) ){
                    $pageName = explode(".", substr($file,strpos($file, '_')+1) );
                    $last_modified = filemtime("$path/$file");
                    $sitemap->new_item("$webpath[$i]$pageName[0]", date(DATE_ATOM, $last_modified));
                }
        } 
    }
    
    return $sitemap;
    
    
}

$sitemap = createSitemap(array('/home/natelev/www/static'), array('http://www.thenaterhood.com/?id='), array('page'));
print $sitemap->output();


?>
