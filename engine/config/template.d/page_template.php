<?php 
#error_reporting(E_ALL);
#ini_set( 'display_errors','1'); 
include GNAT_ROOT.'/lib/core_blog.php';
include GNAT_ROOT.'/lib/core_redirect.php';

$session = new session( array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );
$config = new config();
$registerExtensions = array( );
$extensions = loadExtensions( $session, $registerExtensions );

# Grab variables from the URL. Syntax for this is...
# name of variable, default value of variable, maxlength of variable
$blogdef = loadBlogConf( strtolower( $sectionId ) );

$content = pullContent( array( 'pages/page_'.$session->id, 'pages/hidden_'.$session->id, GNAT_ROOT.'/lib/pages/page_'.$session->id ) );

$htmlTitle = $blogdef->title.' | '.$content->title;
$visibleTitle = $blogdef->title;

$id = $content->title;
$tagline = $blogdef->catchline;
$type = '404';

if ( $config->friendly_urls ){
        $redirect = new condRedirect( substr($config->site_domain, 7).'/?id', "page/".$session->id, substr( $config->site_domain, 7 ).$session->uri );
        $redirect->apply( 301 );
}


// Checks for cookies and sets them (or refreshes them) if necessary

setcookie('name',$session->name,time() + (86400 * 30),"/",$session->domain); // 86400 = 1 day
setcookie('track',$session->track,time() + (86400 * 30),"/",$session->domain); // 86400 = 1 day

include $config->webcore_root.'/html_doctype.html';
include $config->webcore_root.'/html_head.html';
?>


<body>
<div id="wrapper">

<?php include chooseInclude( $config->webcore_root.'/template_header.php', 'layout_error.html');?>

        <div id="page">
                <div id="content">
                                <div style="clear: both;">&nbsp;</div>

                                <?php if ( file_exists( 'static/template_subnav.php' ) ) include 'static/template_subnav.php'; ?>


                                <div class="entry">

                                <?php 

                                print getPreface( $extensions );


                                if ( ! $content->isPhp() ){
                                        print $content->toHtml();
                                }
                                else{
                                        include $content->getFile();
                                }

                                print getPost( $extensions );

                                ?>

                                </div>
                </div>
                <!-- end #content -->
        <div style="clear:both;">&nbsp;</div>

        </div>
</div>

<?php include chooseInclude( $config->webcore_root.'/template_footer.php', 'layout_error.html'); ?>

</body>
</html>

