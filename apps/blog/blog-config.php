<?php 
$app_config = array();

/**
 * Configure where the engine should look to
 * find entries for this blog/article collection
 */
$app_config['post_directory'] = 'site-data/blog/entries';

$app_config['page_directory'] = 'site-data/blog/static';

/**
 * Configure the id of the blog (this is where the
 * blog is on the web, i.e example.com/yourblog)
 */
$app_config['id'] = 'blog';

/**
 * Configure the title of the blog 
 */
$app_config['title'] = 'The Philosophy of Nate';
$app_config['short_title'] = '';

/**
 * Configure the blog's (brief) description/catchline
 */
$app_config['catchline'] = "It's the cyber age. Stay in the know.";

/**
 * Insert any comment code (such as disqus) or 
 * other code you would like inserted on each 
 * post page here.
 */
$app_config['comment_code'] = '<div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = "nphilosophy"; // required: replace example with your forum shortname

        /* * * DONT EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
            dsq.src = "//" + disqus_shortname + ".disqus.com/embed.js";
            (document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
    ';

/**
 * Location of the page template
 */
$app_config['template'] = NWEB_ROOT.'/config/template.d/generic_template.php';


?>
