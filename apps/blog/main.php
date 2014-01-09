<?php

include_once NWEB_ROOT.'/lib/core_auth.php';
include_once 'models.php';

class blog extends controllerBase{

	private $id;
	private $configFile;
	private $approot;
	private $dal;

	public function __construct(){

		$this->pageData = array();


		$this->approot = BLOG_ROOT;

		$this->dal = new DataAccessLayer();

		// Configure the models
		$this->dal->registerModel( 'Blogpost' );

		$configFile = $this->approot.'/conf.xml';
		$this->readConfig( $configFile );

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


	public function read(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_read' );
		$this->pageData['blogid'] = $this->settings['id'];

		$session = $this->pageData['session'];

		$post = $this->dal->get( 'Blogpost', 'nodeid', $session->node );

		$this->pageData['displaypost'] = $post->getArticle();

		$this->pageData['outdated'] = ( ( strtotime('today') - strtotime($post->datestamp) ) > 31556916 ); 

		render_php_template( $this->template, $this->pageData );

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

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_taghistogram' );
		$this->pageData['blogid'] = $this->settings['id'];
		$this->pageData['tags'] = $this->retrieveTagCache();

		render_php_template( $this->template, $this->pageData );


	}

	public function viewtag(){


	}

	public function titles(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_titles' );
		$this->pageData['blogid'] = $this->settings['id'];
		$this->pageData['titles'] = $this->retrieveTitleCache();

		render_php_template( $this->template, $this->pageData );

	}

	/////////////////////////////////////////////////////////////////
	// Managment functions
	/////////////////////////////////////////////////////////////////

	public function manage(){

		#auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage' );

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_manage');
		$this->pageData['id'] = $this->settings['id'];


		render_php_template( $this->template, $this->pageData );

	}

	public function newpost(){
		
		#auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

		$sessionmgr = SessionMgr::getInstance();


		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_editpost');
		$this->pageData['id'] = $this->settings['id'];
		$this->pageData['csrf_id'] = $sessionmgr->get_csrf_id();
		$this->pageData['csrf_token'] = $sessionmgr->get_csrf_token();
		$this->pageData['isNew'] = True;

		render_php_template( $this->template, $this->pageData );


	}

	public function editpost(){
		
		#auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

		$sessionmgr = SessionMgr::getInstance();

		if ( request::get('node') != '' ){

			$this->pageData['content'] = pullContent( $this->approot.'/pages/page_editpost');
			$this->pageData['id'] = $this->settings['id'];

			$post = $this->dal->get( 'Blogpost', 'nodeid', $this->pageData['session']->node );
			$article = $post->getArticle();

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

		$sessionmgr = SessionMgr::getInstance();

		$session = $this->pageData['session'];
		$post = $this->dal->get( 'Blogpost', 'nodeid', $session->node );

		$post->content = request::post('content');
		$post->title = request::post('title');
		$post->tags = request::post('tags');
		$post->updated = date(DATE_ATOM);

		$post->save();


		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_savedpost' );
		$this->pageData['saved'] = $post->nodeid;
		$this->pageData['blogid'] = $this->settings['id'];

		render_php_template( $this->template, $this->pageData );


	}

	public function savepost(){

		#auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

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
		$file = request::post('file');

		if ( $nodeid == '' )
			$nodeDate = date("Y.m.d");

		$post->nodeid = $nodeDate;
		$post->save();

		$post->nodeid = $post->nodeid . '.' . $post->id;
		$post->save();

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_savedpost' );
		$this->pageData['saved'] = $post->nodeid;

		$this->pageData['blogid'] = $this->settings['id'];

		render_php_template( $this->template, $this->pageData );


	}

	/////////////////////////////////////////////////////////////////
	// Private functions (internal functionality)
	/////////////////////////////////////////////////////////////////


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

		$lock = new lock( getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json' );

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

		$lock = new lock( $tagCacheFile );

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

    	$posts = $this->dal->getAll( 'Blogpost' );
    	$articles = array();

    	foreach ($posts as $p) {
    		$articles[] = $p->getArticle();
    	}
    	return array_reverse($articles);


	}


}

?>
