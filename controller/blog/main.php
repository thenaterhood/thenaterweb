<?php

include_once GNAT_ROOT.'/lib/core_auth.php';

class blog extends controllerBase{

	private $id;
	private $configFile;
	private $approot;

	public function __construct(){

		$this->pageData = array();


		$this->approot = BLOG_ROOT;

		$configFile = $this->approot.'/conf.xml';
		$this->readConfig( $configFile );

		$session = request::get_sanitized_as_object( array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );


		$this->pageData['session'] = $session;
		$this->pageData['static'] = $this->page_directory;
		$content = pullContent( array( $this->page_directory.'/page_'.$session->id, $this->page_directory.'/hidden_'.$session->id, GNAT_ROOT.'/lib/pages/page_'.$session->id ) );
		$this->pageData['content'] = $content;
		$this->pageData['id'] = $session->id;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;



	}

	//////////////////////////////////////////////////////
	// Public page views 
	//////////////////////////////////////////////////////


	public function read(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_read' );
		$this->pageData['blogid'] = $this->settings['id'];

		$session = $this->pageData['session'];

		$this->pageData['displaypost'] = new article( $this->post_directory.'/'.$session->node, $this->settings['id'] );

		$post = $this->pageData['displaypost'];
		$this->pageData['outdated'] = ( ( strtotime('today') - strtotime($post->datestamp) ) > 31556916 ); 

		$pageData = $this->pageData;

		include $this->template;

	}

	public function home(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_home' );
		$this->pageData['blogid'] = $this->settings['id'];
		$session = $this->pageData['session'];
		$this->pageData['articles'] = $this->getPostRange( $session->start, $session->end );
		$this->pageData['totalPosts'] = count( $this->getPostFiles() );

		$pageData = $this->pageData;

		include $this->template;

	}

	public function tags(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_taghistogram' );
		$this->pageData['blogid'] = $this->settings['id'];
		$this->pageData['tags'] = $this->retrieveTagCache();

		$pageData = $this->pageData;

		include $this->template;




	}

	public function viewtag(){


	}

	public function titles(){

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_titles' );
		$this->pageData['blogid'] = $this->settings['id'];
		$this->pageData['titles'] = $this->retrieveTitleCache();

		$pageData = $this->pageData;

		include $this->template;


	}

	/////////////////////////////////////////////////////////////////
	// Managment functions
	/////////////////////////////////////////////////////////////////

	public function manage(){

		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage' );

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_manage');
		$this->pageData['id'] = $this->settings['id'];

		$pageData = $this->pageData;

		include $this->template;

	}

	public function newpost(){
		
		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

		$sessionmgr = SessionMgr::getInstance();


		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_editpost');
		$this->pageData['id'] = $this->settings['id'];
		$this->pageData['csrf_id'] = $sessionmgr->get_csrf_id();
		$this->pageData['csrf_token'] = $sessionmgr->get_csrf_token();
		$this->pageData['newPost'] = True;

		$pageData = $this->pageData;

		include $this->template;


	}

	public function editpost(){
		
		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

		$sessionmgr = SessionMgr::getInstance();

		if ( request::get('node') != '' ){

			$this->pageData['content'] = pullContent( $this->approot.'/pages/page_editpost');
			$this->pageData['id'] = $this->settings['id'];
			$this->pageData['csrf_id'] = $sessionmgr->get_csrf_id();
			$this->pageData['csrf_token'] = $sessionmgr->get_csrf_token();
			$post = new article( $this->post_directory.'/'.$this->pageData['session']->node, $this->settings['id'] );

			$pageData['post'] = $post->dump();

			$pageData = $this->pageData;

			include $this->template;

		} else {

			$this->pageData['posts'] = $this->getPostList();
			$this->pageData['appid'] = $this->settings['id'];
			$this->pageData['content'] = pullContent( $this->approot.'/pages/page_selectpost' );

			$pageData = $this->pageData;

			include $this->template;

		}


	}

	public function savepost(){

		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage/editpost' );

		$sessionmgr = SessionMgr::getInstance();
		$session = $this->pageData['session'];

		$postData = array();
		$postData['content'] = request::post('content');
		$postData['title'] = request::post('title');
		$postData['date'] = request::post('date');
		$postData['tags'] = request::post('tags');
		$postData['datestamp'] = date(DATE_ATOM);
		$postData['updated'] = date(DATE_ATOM);
		$file = request::post('file');

		$saved = $this->save_post_file( $postData, $file );

		$this->pageData['content'] = pullContent( $this->approot.'/pages/page_savedpost' );
		$this->pageData['saved'] = $saved;
		$this->pageData['postData'] = $postData;
		$this->pageData['blogid'] = $this->settings['id'];

		$pageData = $this->pageData;

		include $this->template;

	}

	/////////////////////////////////////////////////////////////////
	// Private functions (internal functionality)
	/////////////////////////////////////////////////////////////////

	private function save_post_file( $postData, $file ){

		$pathinfo = pathinfo($file);
		$postpath = $this->settings['post_directory'];
		$postFname = $file['filename'];


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
			$postFname = $file;
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


		if ( count($itles) != count( $this->getPostFiles() ) ){

			$titles = $this->updateTitleCache();
		}

		return $titles;


	}

	private function buildTitleCache(){

		$titleCacheFile = getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json';


		$titleArray = array();
		$postList = $this->getPostFiles();

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
			$posts = count( $this->getPostFiles() );
		}


		if ( $posts != count( $this->getPostFiles() ) ){

			$tags = $this->updateTagCache();
		}

		return $tags;


	}

	private function buildTagCache(){

		$tagCacheFile = getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_tagcache.json';


		$tagArray = array();
		$postList = $this->getPostFiles();

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

		$posts = array_slice($this->getPostFiles(), $start, $end);
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

	public function getPostList(){

    	$posts = $this->getPostFiles();
    	$articles = array();

    	foreach ($posts as $post) {
    		$articles[] = new article( $this->post_directory.'/'.$post, $this->settings['id'] );
    	}

    	return $articles;


	}


}

?>
