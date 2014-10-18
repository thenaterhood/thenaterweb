<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php print $page->title .' | '. $page->id; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/bootstrap3/css/superhero.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    
    <!-- <script src="index_files/ie-emulation-modes-warning.js"></script> -->

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!-- <script src="index_files/ie10-viewport-bug-workaround.js"></script> -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
    <link href="/assets/bootstrap3/css/carousel.css" rel="stylesheet">
  <style type="text/css" id="holderjs-style"></style></head>
<!-- NAVBAR
================================================== -->
  <body>


        <div class="navbar navbar-default navbar-static-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Nate Levesque</a>
            </div>
            <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li><a href="/">Home</a></li>
                <li><a href="/page/about">About</a></li>
                <li><a href="/page/connect">Connect</a></li>
                <li><a href="/page/projects">Projects</a></li>
                <li><a href="/blog">Blog</a></li>

                <?php 

                   if ( file_exists( $page->static.'/template_dropdown.php' ) ){

                    print '<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">More<b class="caret"></b></a>
                    <ul class="dropdown-menu">';
                    include $page->static.'/template_dropdown.php';
                    print '
                    
                    </ul>
                    </li>
                    </ul>';

                  }
                ?>

              </ul>
            </div>
          </div>
        </div>



    <!-- carousel -->
 


    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->

    <div class="container">

      <h1 style="display:inline;text-transform:capitalize;"><?php print $page->title; ?></h1>
      <span style="margin-left:20px;float:right;"><a href="https://www.linkedin.com/in/thenaterhood" title="LinkedIn"><img style='border:1px solid grey;' src="/assets/simpleicons/linkedin-32-black.png" alt=""/></a>
<a href="https://twitter.com/thenaterhood" title="Twitter"><img style='border:1px solid grey;' src="/assets/simpleicons/twitter-32-black.png" alt=""/></a>
<a href="https://plus.google.com/+NateLevesque/posts" title="Google+"><img style='border:1px solid grey;' src="/assets/simpleicons/googleplus-32-black.png" alt=""/></a>
<a href="https://github.com/thenaterhood" title="Github"><img style='border:1px solid grey;' src="/assets/simpleicons/github-32-black.png" alt=""/></a></span>

      <hr class="clear"/>


      <!-- START THE FEATURETTES -->

    <?php 

    print $page->content->render_html( $page );

    ?>

    <hr class="featurette-divider" />
      <!-- /END THE FEATURETTES -->


      <!-- FOOTER -->
      <footer>
    <p>All content is available under the <a href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons ShareAlike Attribution 4.0 license</a> unless otherwise stated, and in a variety of formats. <a href="/?url=page/license">More Information</a></p>   

    <p><a href="#">Back to top</a></p>

       </footer>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://code.jquery.com/jquery.js"></script>

    <script src="/assets/bootstrap3/js/bootstrap.js"></script>
  

<div data-original-title="Copy to clipboard" title="" style="position: absolute; left: 0px; top: -9999px; width: 15px; height: 15px; z-index: 999999999;" class="global-zeroclipboard-container" id="global-zeroclipboard-html-bridge">      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="global-zeroclipboard-flash-bridge" height="100%" width="100%">         <param name="movie" value="/assets/flash/ZeroClipboard.swf?noCache=1403967230639">         <param name="allowScriptAccess" value="sameDomain">         <param name="scale" value="exactfit">         <param name="loop" value="false">         <param name="menu" value="false">         <param name="quality" value="best">         <param name="bgcolor" value="#ffffff">         <param name="wmode" value="transparent">         <param name="flashvars" value="trustedOrigins=getbootstrap.com%2C%2F%2Fgetbootstrap.com%2Chttp%3A%2F%2Fgetbootstrap.com">         <embed src="index_files/ZeroClipboard.swf" loop="false" menu="false" quality="best" bgcolor="#ffffff" name="global-zeroclipboard-flash-bridge" allowscriptaccess="sameDomain" allowfullscreen="false" type="application/x-shockwave-flash" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="trustedOrigins=getbootstrap.com%2C%2F%2Fgetbootstrap.com%2Chttp%3A%2F%2Fgetbootstrap.com" scale="exactfit" height="100%" width="100%">                </object></div></body></html>