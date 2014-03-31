<?php

include_once NWEB_ROOT.'/lib/core_auth.php';
include_once 'models.php';

class blog extends ControllerBase{

	private $id;
	private $configFile;
	private $approot;
	private $dal;
	private $usedb;

	public function __construct(){

		$this->usedb = getConfigOption('use_db');

		$name = REQUESTED_NAME;
		$this->pageData = array();

		$this->approot = constant(strtoupper(get_class($this)).'_ROOT');

		if ( file_exists($this->approot.'/'.strtolower(REQUESTED_NAME).'-config.php') ){

			include_once $this->approot.'/'.strtolower(REQUESTED_NAME).'-config.php';
			$this->settings = $app_config;

			if ( array_key_exists('use_db', $app_config) ){
				print $app_config['use_db'];
				$this->usedb = $app_config['use_db'];
			}

			// Configure the models
			if ( $this->usedb ){
				$this->dal = new DataAccessLayer();
				$this->dal->registerModel( 'Blogpost', True, strtolower(REQUESTED_NAME).'_blogposts' );
			}


		} else {
			throw new Exception("Blog not found.", 404 );
		}

		$session = request::get_sanitized_as_object( array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );


		$this->pageData['session'] = $session;
		$this->pageData['static'] = $this->page_directory;
		$content = pullContent( array( $this->page_directory.'/page_'.$session->id, $this->page_directory.'/hidden_'.$session->id, NWEB_ROOT.'/lib/pages/page_'.$session->id ) );
		$this->pageData['content'] = $content;
		$this->pageData['id'] = $session->id;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;
		$this->pageData['blogid'] = $this->settings['id'];




	}

	//////////////////////////////////////////////////////
	// Public page views 
	//////////////////////////////////////////////////////

	private function get_post(){

		$session = $this->pageData['session'];

		if ( $this->usedb ){
			$post = $this->dal->get( 'Blogpost', 'nodeid', $session->node );
			if ( is_null($post ) ){
				return new article( '', REQUESTED_NAME );
			} else {
				return $post->getArticle();
			}
		} else {
			$post = new article( $this->post_directory.'/'.$session->node, $this->settings['id'] );
			return $post;
		}
	}


	public function read(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_read' );
		$this->pageData['blogid'] = $this->settings['id'];

		$session = $this->pageData['session'];

		$post = $this->get_post();
		$this->pageData['displaypost'] = $post;
		$this->pageData['commentCode'] = $this->settings['comment_code'];
		$this->pageData['outdated'] = ( ( strtotime('today') - strtotime($post->datestamp) ) > 31556916 ); 

		render_php_template( $this->template, $this->pageData );

	}

	public function json(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_read' );
		$this->pageData['blogid'] = $this->settings['id'];

		$session = $this->pageData['session'];

		$post = $this->get_post();


		Header('Content-type: application/json');

		$postdata = $post->dump();

		unset($postdata['file']);

		print json_encode($postdata);


	}

	public function simple(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_read' );
		$this->pageData['blogid'] = $this->settings['id'];

		$session = $this->pageData['session'];

		$post = $this->get_post()->dump();

		print '<h1>' . $post['title'] . '</h1>';
		print '<h2>' . $post['datestamp'] . '</h2>';

		print $post['content'];


	}

	public function home(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_home' );
		$this->pageData['blogid'] = $this->settings['id'];
		$session = $this->pageData['session'];
		$this->pageData['articles'] = $this->getPostRange( $session->start, $session->end );
		$this->pageData['totalPosts'] = count( $this->getPostList() );

		render_php_template( $this->template, $this->pageData );


	}

	public function tags(){

		$format = request::sanitized_get('as');

		if ( ! is_null( $format ) && $format == 'json' ){

			Header('Content-type: application/json');
			print file_get_contents( getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_tagcache.json' );
		} else {

			$this->pageData['content'] = pullContent( $this->approot.'/pages/page_taghistogram' );
			$this->pageData['blogid'] = $this->settings['id'];
			$this->pageData['tags'] = $this->retrieveTagCache();

			render_php_template( $this->template, $this->pageData );
		}


	}

	public function viewtag(){


	}

	public function titles(){

		$format = request::sanitized_get('as');

		if ( ! is_null($format) && $format == 'json' ){

			Header('Content-type: application/json');
			print file_get_contents( getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json' );



		} else {
			print $format;

			$this->pageData['content'] = pullContent( $this->approot.'/pages/page_titles' );
			$this->pageData['blogid'] = $this->settings['id'];
			$this->pageData['titles'] = $this->retrieveTitleCache();

			render_php_template( $this->template, $this->pageData );

		}

	}

	public function feed(){

		include_once NWEB_ROOT.'/lib/core_feed.php';


		Header('Content-type: application/atom+xml');
		$feed = generateFeed( $this, False );
		print $feed->output( getConfigOption('feed_type') );

	}

	/////////////////////////////////////////////////////////////////
	// Managment functions
	/////////////////////////////////////////////////////////////////

	public function manage(){

		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage' );

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_manage');
		$this->pageData['id'] = $this->settings['id'];


		render_php_template( $this->template, $this->pageData );

	}

	public function newpost(){
		
		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

		$sessionmgr = SessionMgr::getInstance();


		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_editpost');
		$this->pageData['id'] = $this->settings['id'];
		$this->pageData['csrf_id'] = $sessionmgr->get_csrf_id();
		$this->pageData['csrf_token'] = $sessionmgr->get_csrf_token();
		$this->pageData['isNew'] = True;

		render_php_template( $this->template, $this->pageData );


	}

	public function editpost(){
		
		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

		$sessionmgr = SessionMgr::getInstance();

		if ( request::get('node') != '' ){

			$this->pageData['content'] = pullContent( $this->approot.'/pages/page_editpost');
			$this->pageData['id'] = $this->settings['id'];

			$article = $this->get_post();

			$this->pageData['post'] = $article->dump();

			render_php_template( $this->template, $this->pageData );


		} else {

			$this->pageData['posts'] = $this->getPostList();
			$this->pageData['appid'] = $this->settings['id'];
			$this->pageData['content'] = pullContent( $this->approot.'/pages/page_selectpost' );

			render_php_template( $this->template, $this->pageData );


		}


	}

	public function updatepost(){

		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

		$sessionmgr = SessionMgr::getInstance();

		$session = $this->pageData['session'];


		if ( $this->usedb ){
			$post = $this->dal->get( 'Blogpost', 'nodeid', $session->node );

			$post->content = request::post('content');
			$post->title = request::post('title');
			$post->tags = request::post('tags');
			$post->updated = date(DATE_ATOM);

			$post->save();

		} else {
			$post = $this->get_post();

			$post->content = request::post('content');
			$post->title = request::post('title');
			$post->tags = request::post('tags');
			$post->updated = date(DATE_ATOM);

			$file = $post->file;

			$this->save_post_file( $post->dump(), $file );
		}


		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_savedpost' );
		$this->pageData['saved'] = $post->nodeid;
		$this->pageData['blogid'] = $this->settings['id'];

		$this->buildTagCache();


		render_php_template( $this->template, $this->pageData );


	}

	public function savepost(){

		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

		$sessionmgr = SessionMgr::getInstance();
		$session = $this->pageData['session'];

		$post = new Blogpost();

		$post->content = request::post('content');
		$post->title = request::post('title');
		$post->date = 'null';
		if ( request::post('date') != '' )
			$postData['date'] = request::post('date');
		$post->tags = request::post('tags');
		$post->datestamp = date(DATE_ATOM);
		$post->updated = date(DATE_ATOM);

		if ( $nodeid == '' )
			$nodeDate = date("Y.m.d");

		if ( $this->usedb ){
			$post->nodeid = $nodeDate;
			$post->save();

			$post->nodeid = $post->nodeid . '.' . $post->id;
			$post->save();
			$this->pageData['saved'] = $post->nodeid;

		} else {
			$nodename = $this->save_post_file( $post->as_array() );
			$this->pageData['saved'] = $nodename;
		}

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_savedpost' );

		$this->pageData['blogid'] = $this->settings['id'];

		$this->buildTagCache();

		render_php_template( $this->template, $this->pageData );


	}


	/////////////////////////////////////////////////////////////////
	// Private functions (internal functionality)
	/////////////////////////////////////////////////////////////////
	private function save_post_file( $postData, $file='' ){

		$pathinfo = pathinfo($file);
		$postpath = $this->settings['post_directory'];


		if ( $file == '' ){
			$nodeDate = date("Y.m.d");
			$nodename = $nodeDate.'.0';
			$postFname = $nodename.'.json';

			$i = 0;
			while ( file_exists( $postpath.'/'.$postFname ) ){
				$i++;
				$nodename = $nodeDate.'.'.$i;
				$postFname = $nodename.'.json';

			}
		}

		else{
			$postFname = $pathinfo['basename'];
			$nodename = substr($postFname, 0, strpos($postFname, '.json') );
		}

		$postData = array();

		$postData['content'] = $_POST['content'];
		$postData['title'] = $_POST['title'];
		$postData['date'] = $_POST['date'];
		$postData['tags'] = $_POST['tags'];
		$postData['datestamp'] = date(DATE_ATOM);
		$postData['updated'] = date(DATE_ATOM);

		$postJsonData = json_encode($postData);
		$postFile = $postpath.'/'.$postFname;


		$lock = new lock( $postFile );

		$postURL = getConfigOption('site_domain').'/'.$_POST['blog'].'/index.php?id=post&node='.$nodename;
		$writetest = fopen( $postpath.'/writetest.txt', 'w' );
		fclose( $writetest );

		if ( is_writeable( $postpath.'/writetest.txt' ) && !$lock->isLocked() ){

			$lock->lock();

			$jsonFile = fopen($postpath.'/'.$postFname, 'w');
			fwrite($jsonFile, $postJsonData);
			fclose($jsonFile);

			$lock->unlock();

			unlink( $postpath.'/writetest.txt');

			return $nodename;

		} else {
			
			return False;

		}




	}

	private function retrieveTitleCache(){

		$titleCacheFile = getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json';

		if ( file_exists( $titleCacheFile ) ){

			$titleData = json_decode( file_get_contents( $titleCacheFile, True ), True);
			$titles = $titleData['titles'];

		} else {

			$titles = $this->buildTitleCache();
		}


		if ( count($itles) != count( $this->getPostList() ) ){

			$titles = $this->updateTitleCache();
		}

		return $titles;


	}

	private function buildTitleCache(){

		$titleCacheFile = getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json';


		$titleArray = array();
		$postList = $this->getPostList();

		foreach ( $this->getPostList() as $post ) {

			$titleArray[ $post->nodeid ] = $post->title;
		}

		$titleData = array();

		$titleData['titles'] = $titleArray;

		$lock = new Lock( getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json' );

		if ( ! $lock->isLocked() ){

			$lock->lock();
			$jsonData = json_encode( $titleData, True );
			
			$fhandle = fopen( $titleCacheFile, 'w' );
			fwrite($fhandle, $jsonData);

			$lock->unlock();

		}

		return $titleArray;

	}

	private function updateTitleCache(){

		return $this->buildTitleCache();

	}

	private function retrieveTagCache(){

		$tagCacheFile = getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_tagcache.json';
		$posts = 0;

		if ( file_exists( $tagCacheFile ) ){

			$tagData = json_decode( file_get_contents( $tagCacheFile, True ), True);
			$tags = $tagData['tags'];
			$posts = count( $tagData['posts'] );

		} else {

			$tags = $this->buildTagCache();
			$posts = count( $this->getPostList() );
		}


		if ( $posts != count( $this->getPostList() ) ){

			$tags = $this->updateTagCache();
		}

		return $tags;


	}

	private function buildTagCache(){

		$tagCacheFile = getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_tagcache.json';


		$tagArray = array();
		$postList = $this->getPostList();

		foreach ( $this->getPostList() as $post ) {

			foreach (explode(',', $post->tags) as $tag ) {

				$tag = trim($tag);

				if ( ! array_key_exists($tag, $tagArray) ){

					$tagArray[ $tag ] = array( "$post->nodeid"=>"$post->title" );

				} else {
					$tagPosts = $tagArray[ $tag ];
					$tagPosts[ $post->nodeid ] = $post->title;
					$tagArray[ $tag ] = $tagPosts;
				}

			}

		}

		$tagData = array();
		$tagData['tags'] = $tagArray;
		$tagData['posts'] = $postList;

		$lock = new Lock( $tagCacheFile );

		if ( ! $lock->isLocked() ){

			$lock->lock();
			$jsonData = json_encode( $tagData, True );
			
			$fhandle = fopen( $tagCacheFile, 'w' );
			fwrite($fhandle, $jsonData);

			$lock->unlock();

		}

		return $tagArray;


	}

	private function updateTagCache(){

		$this->buildTagCache();


	}



	private function getPostRange( $start, $end ){

		$posts = array_slice($this->getPostList(), $start, $end);
		
		return $posts;

	}


	public function getPostList(){

		if ( $this->usedb ){

	    	$posts = $this->dal->getAll( 'Blogpost' );
	    	$articles = array();

	    	foreach ($posts as $p) {
	    		$articles[] = $p->getArticle( strtolower(REQUESTED_NAME) );
	    	}

	    	$articles = array_reverse($articles);
	    } else {
	    	$articles = $this->getPostList_fromFolder();
	    }

    	return $articles;


	}

	private function getPostList_fromFolder(){

    	$posts = $this->getPostFiles();
    	$articles = array();

    	foreach ($posts as $post) {
    		$articles[] = new article( $this->post_directory.'/'.$post, $this->settings['id'] );
    	}

    	return $articles;


	}

	private function getPostFiles(){

		$posts = array();

		$handler = opendir( $this->post_directory );

		while( $file = readdir( $handler)){
			if ( $file != '.' && $file != '..' ){
				$nodeinfo = pathinfo($file);
				$posts[] = $nodeinfo['filename'];
			}
		}

		rsort( $posts );

		return $posts;

	}


}

?>
