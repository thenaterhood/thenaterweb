<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php print $pageData['title'] .' | '. $pageData['id']; ?></title>
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

                        if ( file_exists( $pageData['static'].'/template_dropdown.php' ) ){

                                print '<li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$pageData['appid'].'<b class="caret"></b></a>
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
          <h1><?php print $pageData['title']; ?></h1>
        </div>
                                <?php 



                                if ( ! $pageData['content']->isPhp() ){
                                        print $pageData['content']->toHtml();
                                }
                                else{
                                        include $pageData['content']->getFile();
                                }


                                ?>
      </div>

      <div id="push"></div>
    </div>

    <div id="footer">
      <div class="container">
        <p class="muted credit">Template based on <a href="http://getbootstrap.com">Bootstrap</a>. Copyright 2012-2013 Nate Levesque (TheNaterhood). <a href="/sitemaps/page">View sitemap</a>.</p>
      </div>
    </div>



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery.js"></script>

    <script src="/assets/bootstrap/js/bootstrap.js"></script>

  </body>
</html>

