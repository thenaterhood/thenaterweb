<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php print $page->title .' | '. $page->id; ?></title>

  <!-- Bootstrap core CSS -->
  <link href="/assets/bootstrap3/css/bootstrap.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="/assets/bootstrap3/css/sticky-footer-navbar.css" rel="stylesheet">

  <!-- Just for debugging purposes. Don't actually copy this line! -->
  <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
      <![endif]-->
      <?php print getConfigOption('tracking_code'); ?>
    </head>

    <body>

      <!-- Wrap all page content here -->
      <div id="wrap">

        <!-- Fixed navbar -->
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="/">TheNaterhood</a>
            </div>
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
               <li><a href="/">Home</a></li>
               <li><a href="/page/about">About</a></li>
               <li><a href="/page/connect">Connect</a></li>  
               <li><a href="/page/projects">Projects</a></li>
               <li><a href="/devrants">Devrants</a></li>
               <li><a href="/blog">Blog</a></li>

               <?php 

               if ( file_exists( $page->static.'/template_dropdown.php' ) ){

                print '<li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$page->blogid.'<b class="caret"></b></a>
                <ul class="dropdown-menu">';
                include $page->static.'/template_dropdown.php';
                print '
                
                </ul>
                </li>
                </ul>';

              }
              ?>
            </ul>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>

  <!-- Begin page content -->
  <div class="container">
    <div class="page-header" >
      <br />
      <br />
      <br />
      <h1 style="display:inline;"><?php print $page->title; ?></h1>
      <?php if ( file_exists( $page->static.'/template_head-icons.html' ) ){
        echo '<span style="margin-left:20px;">';
        include $page->static.'/template_head-icons.html';
        echo '</span>';
      }
      ?>
    </div>
    <?php 



    if ( ! $page->content->isPhp() ){
      print $page->content->toHtml();
    }
    else{
      include $page->content->getFile();
    }


    ?>
  </div>
</div>
<hr />
<div id="footer">
  <div class="container">
    <p class="text-muted">Template based on <a href="http://getbootstrap.com">Bootstrap</a>.</p>
    <p class="text-muted">All content is available under the <a href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons ShareAlike Attribution 4.0 license</a> unless otherwise stated, and in a variety of formats. <a href="/?url=page/license">More Information</a></p>
    
    <p> <a href="/sitemaps/page">View sitemap</a>.</p>
  </div>
</div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery.js"></script>

    <script src="/assets/bootstrap3/js/bootstrap.js"></script>  </body>
    </html>
