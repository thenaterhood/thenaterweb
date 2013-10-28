<?php 
#error_reporting(E_ALL);
#ini_set( 'display_errors','1'); 
include_once GNAT_ROOT.'/lib/core_blog.php';
include_once GNAT_ROOT.'/lib/core_redirect.php';

$session = new session( array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );
$config = new config();
$registerExtensions = array( );
$extensions = loadExtensions( $session, $registerExtensions );

# Grab variables from the URL. Syntax for this is...
# name of variable, default value of variable, maxlength of variable
$static = $blogdef->page_directory;

$content = pullContent( array( $static.'/page_'.$session->id, $static.'/hidden_'.$session->id, GNAT_ROOT.'/lib/pages/page_'.$session->id ) );

$htmlTitle = $blogdef->title.' | '.$content->title;
$visibleTitle = $blogdef->title;

$id = $content->title;
$tagline = $blogdef->catchline;
$type = '404';

if ( $config->friendly_urls ){
        $redirect = new condRedirect( '/?url', '/'.$_GET['url'], substr( $config->site_domain, 7 ).$session->uri );
        $redirect->apply( 301 );
        $redirect = new condRedirect( "?id=post", '/'.$blogdef->id.'/read/'.$session->node.'.htm', $session->uri );
        $redirect->apply( 301 );
        $redirect = new condRedirect( '/?id', "page/".$session->id, substr( $config->site_domain, 7 ).$session->uri );
        $redirect->apply( 301 );
}


// Checks for cookies and sets them (or refreshes them) if necessary

setcookie('name',$session->name,time() + (86400 * 30),"/",$session->domain); // 86400 = 1 day
setcookie('track',$session->track,time() + (86400 * 30),"/",$session->domain); // 86400 = 1 day
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php print $blogdef->title .' | '. $content->title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSS -->
    <link href="/assets/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">

      /* Sticky footer styles
      -------------------------------------------------- */

      html,
      body {
        height: 100%;
        /* The html and body elements cannot have any padding or margin. */
      }

      /* Wrapper for page content to push down footer */
      #wrap {
        min-height: 100%;
        height: auto !important;
        height: 100%;
        /* Negative indent footer by it's height */
        margin: 0 auto -60px;
      }

      /* Set the fixed height of the footer here */
      #push,
      #footer {
        height: 60px;
      }
      #footer {
        background-color: #f5f5f5;
      }

      /* Lastly, apply responsive CSS fixes as necessary */
      @media (max-width: 767px) {
        #footer {
          margin-left: -20px;
          margin-right: -20px;
          padding-left: 20px;
          padding-right: 20px;
        }
      }



      /* Custom page CSS
      -------------------------------------------------- */
      /* Not required for template or sticky footer method. */

      #wrap > .container {
        padding-top: 60px;
      }
      .container .credit {
        margin: 20px 0;
      }

      code {
        font-size: 80%;
      }

    </style>
    <link href="/assets/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>


    <!-- Part 1: Wrap all page content here -->
    <div id="wrap">

      <!-- Fixed navbar -->
      <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
          <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="brand" href="/">TheNaterhood</a>
            <div class="nav-collapse collapse">
              <ul class="nav">
                <li><a href="/">Home</a></li>
                <li><a href="/page/about">About</a></li>
                <li><a href="/page/connect">Connect</a></li>  
                <li><a href="/page/projects">Projects</a></li>                              

                <?php 

                        if ( file_exists( $static.'/template_dropdown.php' ) ){

                                print '<li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$blogdef->id.'<b class="caret"></b></a>
                                        <ul class="dropdown-menu">';
                                include $static.'/template_dropdown.php';
                                print '
          
                                </ul>
                                </li>
                                </ul>';

                        }
                ?>


            </div><!--/.nav-collapse -->
          </div>
        </div>
      </div>

      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h1><?php print $blogdef->title; ?></h1>
        </div>
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

      <div id="push"></div>
    </div>

    <div id="footer">
      <div class="container">
        <p class="muted credit">Template based on <a href="http://getbootstrap.com">Bootstrap</a>. Copyright 2012-2013 Nate Levesque (TheNaterhood). <a href="/sitemap.php">View sitemap</a>.</p>
      </div>
    </div>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery.js"></script>

    <script src="/assets/bootstrap/js/bootstrap.js"></script>

  </body>
</html>

