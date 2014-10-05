<?php

include_once NWEB_ROOT.'/lib/core_auth.php';
include_once 'models.php';

require_once(NWEB_ROOT.'/Content/Loaders/class_contentFactory.php');

use Naterweb\Content\Loaders\ContentFactory;
use Naterweb\Content\Renderers\PhpRenderer;
use Naterweb\Client\request;
use Naterweb\Client\SessionMgr;
use Naterweb\Routing\Urls\UrlBuilder;

class blog extends ControllerBase{

	private $id;
	private $configFile;
	private $approot;
	private $dal;
	private $usedb;

	public function __construct(){

		$this->usedb = \Naterweb\Engine\Configuration::get_option('use_db');

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

		$this->pageData['id'] = $session->id;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;
		$this->pageData['blogid'] = $this->settings['id'];
		$urlBase = new UrlBuilder(array($this->settings['id']=>''));
		$this->pageData['urlBase'] = $urlBase->build();



	}

	//////////////////////////////////////////////////////
	// Public page views 
	//////////////////////////////////////////////////////

	private function get_post(){

		$session = $this->pageData['session'];

		if ( $this->usedb ){
			return $this->dal->get( 'Blogpost', 'nodeid', $session->node );
		} else {

			$post = 
				ContentFactory::loadContentFile( "{$this->post_directory}/{$session->node}.json" );
			return $post;
		}
	}


	public function read(){

		$session = $this->pageData['session'];
		$this->pageData['nodeid'] = $session->node;
		$post = $this->get_post();
		$this->pageData['displaypost'] = $post;

		$this->pageData['content'] = 
			ContentFactory::loadContentFile( $this->approot.'/pages/page_read.php' );
		$this->pageData['blogid'] = $this->settings['id'];


		$this->pageData['commentCode'] = $this->settings['comment_code'];
		$this->pageData['outdated'] = ( ( strtotime('today') - strtotime($post->datestamp) ) > 31556916 );

		$renderer = new PhpRenderer($this->template);
		$renderer->bulk_set_values($this->pageData);
		$renderer->render();

	}

	public function json(){

		$this->pageData['blogid'] = $this->settings['id'];

		$session = $this->pageData['session'];

		$post = $this->get_post();


		Header('Content-type: application/json');

		$blogpost = array();
		$blogpost['title'] = $post->title;
		$blogpost['content'] = $post->content;
		$blogpost['tags'] = $post->tags;
		$blogpost['author'] = $post->author;
		$blogpost['datestamp'] = $post->datestamp;

		print json_encode($blogpost);


	}

	public function simple(){

		$this->pageData['blogid'] = $this->settings['id'];

		$session = $this->pageData['session'];

		$post = $this->get_post();

		print '<h1>' . $post->title . '</h1>';
		print '<h2>' . $post->datestamp . '</h2>';

		print $post->content;


	}

	public function home(){

		$this->pageData['content'] = 
			ContentFactory::loadContentFile( $this->approot.'/pages/page_home.php' );
		$this->pageData['blogid'] = $this->settings['id'];
		$session = $this->pageData['session'];
		$this->pageData['articles'] = $this->getPostRange( $session->start, $session->end );

		$this->pageData['totalPosts'] = count( $this->getPostList() );

		$renderer = new PhpRenderer($this->template);
		$renderer->bulk_set_values($this->pageData);
		$renderer->render();

	}

	public function tags(){

		$format = request::sanitized_get('as');

		if ( ! is_null( $format ) && $format == 'json' ){

			Header('Content-type: application/json');
			print file_get_contents( \Naterweb\Engine\Configuration::get_option('dynamic_directory' ).'/'.$this->settings['id'].'_tagcache.json' );
		} else {

			$this->pageData['content'] = 
				ContentFactory::loadContentFile( $this->approot.'/pages/page_taghistogram.php' );
			$this->pageData['blogid'] = $this->settings['id'];
			$this->pageData['tags'] = $this->retrieveTagCache();

			$renderer = new PhpRenderer($this->template);
			$renderer->bulk_set_values($this->pageData);
			$renderer->render();
		}


	}

	public function viewtag(){


	}

	public function titles(){

		$format = request::sanitized_get('as');

		if ( ! is_null($format) && $format == 'json' ){

			Header('Content-type: application/json');
			print file_get_contents( \Naterweb\Engine\Configuration::get_option('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json' );



		} else {
			print $format;

			$this->pageData['content'] = 
				ContentFactory::loadContentFile( $this->approot.'/pages/page_titles.php' );
			$this->pageData['blogid'] = $this->settings['id'];
			$this->pageData['titles'] = $this->retrieveTitleCache();
			$renderer = new PhpRenderer($this->template);
			$renderer->bulk_set_values($this->pageData);
			$renderer->render();
		}

	}

	public function feed(){

		include_once NWEB_ROOT.'/lib/core_feed.php';


		Header('Content-type: application/atom+xml');
		$feed = generateFeed( $this, False );
		print $feed->output( \Naterweb\Engine\Configuration::get_option('feed_type') );

	}

	/////////////////////////////////////////////////////////////////
	// Managment functions
	/////////////////////////////////////////////////////////////////

	public function manage(){

		$url = new UrlBuilder(array($this->settings['id']=>'manage'));
		auth_user( $url->build() );

		$this->pageData['content'] = 
			ContentFactory::loadContentFile( $this->approot.'/pages/page_manage.php');
		$this->pageData['id'] = $this->settings['id'];
		$renderer = new PhpRenderer($this->template);
		$renderer->bulk_set_values($this->pageData);
		$renderer->render();

	}

	public function newpost(){
		$url = new UrlBuilder(array($this->settings['id']=>'manage', 'editpost'=>''));
		auth_user( $url->build() );

		$sessionmgr = SessionMgr::getInstance();

		$this->pageData['content'] = 
			ContentFactory::loadContentFile( $this->approot.'/pages/page_editpost.php');
		$this->pageData['id'] = $this->settings['id'];
		$this->pageData['csrf_id'] = $sessionmgr->get_csrf_id();
		$this->pageData['csrf_token'] = $sessionmgr->get_csrf_token();
		$this->pageData['isNew'] = True;

		$renderer = new PhpRenderer($this->template);
		$renderer->bulk_set_values($this->pageData);
		$renderer->render();


	}

	public function editpost(){
		$url = new UrlBuilder(array($this->settings['id']=>'manage', 'editpost'=>''));
		auth_user( $url->build() );

		$sessionmgr = SessionMgr::getInstance();
		$renderer = new PhpRenderer($this->template);
		if ( request::get('node') != '' ){

			$this->pageData['content'] = 
				ContentFactory::loadContentFile( $this->approot.'/pages/page_editpost.php');
			$this->pageData['id'] = $this->settings['id'];

			$article = $this->get_post();

			$this->pageData['post'] = $article;
			$this->pageData['node'] = request::get('node');
			$renderer->bulk_set_values($this->pageData);



		} else {

			$this->pageData['posts'] = $this->getPostList();
			$this->pageData['appid'] = $this->settings['id'];
			$this->pageData['content'] = ContentFactory::loadContentFile( $this->approot.'/pages/page_selectpost.php' );
			$renderer->bulk_set_values($this->pageData);

		}

		$renderer->render();


	}

	public function updatepost(){
		$url = new UrlBuilder(array($this->settings['id']=>'manage','editpost'=>''));
		auth_user( $url->build() );

		$sessionmgr = SessionMgr::getInstance();

		$session = $this->pageData['session'];


		if ( $this->usedb ){
			$post = $this->dal->get( 'Blogpost', 'nodeid', $session->node );

			$post->content = request::post('content');
			$post->title = request::post('title');
			$post->tags = request::post('tags');
			$post->updated = date(DATE_ATOM);

			$post->save();

			$this->pageData['saved'] = $session->node;

		} else {
			$post = $this->get_post();

			$post->content = request::post('content');
			$post->title = request::post('title');
			$post->tags = request::post('tags');
			$post->updated = date(DATE_ATOM);

			$file = $post->page_filename;

			$nodename = $this->save_post_file( (array)$post, $file );

			$this->pageData['saved'] = $nodename;
		}

		$this->pageData['post'] = $post;
		$this->pageData['content'] = ContentFactory::loadContentFile( $this->approot.'/pages/page_savedpost.php' );
		$this->pageData['blogid'] = $this->settings['id'];
		$renderer = new PhpRenderer($this->template);
		$renderer->bulk_set_values($this->pageData);
		$this->buildTagCache();


		$renderer->render();

	}

	public function savepost(){
		$url = new UrlBuilder(array($this->settings['id']=>'manage', 'editpost'=>''));
		auth_user( $url->build() );

		$sessionmgr = SessionMgr::getInstance();
		$session = $this->pageData['session'];

		if ( $this->usedb ){
			$post = new Blogpost();
		} else {
			$post = new stdClass();
		}

		$post->content = request::post('content');
		$post->title = request::post('title');
		$post->date = 'null';
		if ( request::post('date') != '' )
			$postData['date'] = request::post('date');
		$post->tags = request::post('tags');
		$post->datestamp = date(DATE_ATOM);
		$post->updated = date(DATE_ATOM);

		if ( !isset($nodeid) or $nodeid == '' )
			$nodeDate = date("Y.m.d");

		if ( $this->usedb ){
			$post->nodeid = $nodeDate;
			$post->save();

			$post->nodeid = $post->nodeid . '.' . $post->id;
			$post->save();
			$this->pageData['saved'] = $post->nodeid;

		} else {
			$blogpost = array();
			$blogpost['title'] = $post->title;
			$blogpost['content'] = $post->content;
			$blogpost['tags'] = $post->tags;
			$blogpost['author'] = $post->author;
			$blogpost['datestamp'] = $post->datestamp;
			$nodename = $this->save_post_file( $blogpost );

			$this->pageData['saved'] = $nodename;
		}

		$this->pageData['content'] = ContentFactory::loadContentFile( $this->approot.'/pages/page_savedpost.php' );

		$this->pageData['blogid'] = $this->settings['id'];

		$this->buildTagCache();

		$renderer = new PhpRenderer($this->template);
		$renderer->bulk_set_values($this->pageData);
		$renderer->render();

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
		//$postData['date'] = $_POST['date'];
		$postData['tags'] = $_POST['tags'];
		$postData['datestamp'] = date(DATE_ATOM);
		$postData['updated'] = date(DATE_ATOM);

		$postJsonData = json_encode($postData);
		$postFile = $postpath.'/'.$postFname;


		$lock = new lock( $postFile );

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

		$titleCacheFile = \Naterweb\Engine\Configuration::get_option('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json';

		if ( file_exists( $titleCacheFile ) ){

			$titleData = json_decode( file_get_contents( $titleCacheFile, True ), True);
			$titles = $titleData['titles'];

		} else {

			$titles = $this->buildTitleCache();
		}


		if ( count($titles) != count( $this->getPostList() ) ){

			$titles = $this->updateTitleCache();
		}

		return $titles;


	}

	private function buildTitleCache(){

		$titleCacheFile = \Naterweb\Engine\Configuration::get_option('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json';


		$titleArray = array();

		foreach ( $this->getPostList() as $postid => $post ) {

			$titleArray[$postid] = $post['title'];
		}

		$titleData = array();

		$titleData['titles'] = $titleArray;

		$lock = new Lock( \Naterweb\Engine\Configuration::get_option('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json' );

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

		$tagCacheFile = \Naterweb\Engine\Configuration::get_option('dynamic_directory' ).'/'.$this->settings['id'].'_tagcache.json';
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

		$tagCacheFile = \Naterweb\Engine\Configuration::get_option('dynamic_directory' ).'/'.$this->settings['id'].'_tagcache.json';


		$tagArray = array();
		$postList = $this->getPostList();

		foreach ( $this->getPostList() as $node => $post ) {

			foreach (explode(',', $post['tags']) as $tag ) {
				$tag = trim($tag);

				if ( ! array_key_exists($tag, $tagArray) ){

					$tagArray[ $tag ] = array( "$node"=>$post['title'] );

				} else {
					$tagPosts = $tagArray[ $tag ];
					$tagPosts[ $node ] = $post['title'];
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
	    		$postarray = $p->as_array( strtolower(REQUESTED_NAME) );
			$url = new UrlBuilder(array(REQUESTED_NAME=>'read', $p->nodeid.'.htm'=>''));
	    		$postarray['link'] = $url->build();
	    		$articles[] = $postarray;
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
    		$article = json_decode(file_get_contents("$this->post_directory/$post.json"),true);
		$postName = $post.'.htm';
		$url = new UrlBuilder(array(REQUESTED_NAME=>'read',$postName=>''));
	    	$article['link'] = $url->build();

    		$articles[$post] = $article;
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
