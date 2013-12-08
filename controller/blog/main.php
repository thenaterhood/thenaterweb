<?php

class blog extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();


		$configFile = BLOG_ROOT.'/conf.xml';
		$this->readConfig( $configFile );

		$session = new session( array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );


		$this->pageData['session'] = $session;
		$this->pageData['static'] = $this->page_directory;
		$content = pullContent( array( $this->page_directory.'/page_'.$session->id, $this->page_directory.'/hidden_'.$session->id, GNAT_ROOT.'/lib/pages/page_'.$session->id ) );
		$this->pageData['content'] = $content;
		$this->pageData['id'] = $content->title;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;



	}

	public function read(){

		$this->pageData['content'] = pullContent( BLOG_ROOT.'/pages/page_read' );
		$this->pageData['blogid'] = $this->settings['id'];

		$session = $this->pageData['session'];

		$this->pageData['displaypost'] = new article( $this->post_directory.'/'.$session->node, $this->settings['id'] );

		$pageData = $this->pageData;

		include $this->template;

	}

	public function home(){

		$this->pageData['content'] = pullContent( BLOG_ROOT.'/pages/page_home' );
		$this->pageData['blogid'] = $this->settings['id'];
		$session = $this->pageData['session'];
		$this->pageData['articles'] = $this->getPostRange( $session->start, $session->end );
		$this->pageData['totalPosts'] = count( $this->getPostFiles() );

		$pageData = $this->pageData;

		include $this->template;

	}

	public function tags(){




	}

	public function viewtag(){


	}

	public function titles(){

		$this->pageData['content'] = pullContent( BLOG_ROOT.'/pages/page_titles.php' );
		$this->pageData['titles'] = $this->retrieveTitleCache();

		$pageData = $this->pageData;

		include $this->template;


	}

	private function retrieveTitleCache(){

		$titleCacheFile = getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json';

		if ( file_exists( $titleCacheFile ) ){

			$titleData = json_decode( file_get_contents( $titleCacheFile, True ), True);
			$titles = $titleData['titles'];

		} else {

			$titles = $this->buildTitleCache();
		}


		if ( count($titles) != count( $this->getPostFiles() ) ){

			$titles = $this->updateTitleCache();
		}

		return $titles;


	}

	private function buildTitleCache(){

		$titleCacheFile = getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json';


		$titleArray = array();
		$postList = $this->getPostFiles();

		foreach ( $this->getPostList() as $post ) {

			$titleArray[ $post->title ] = $post->link;
		}

		$titleData = array();

		$titleData['titles'] = $titleArray;
		$titleData['posts'] = $postList;

		$lock = new lock($titleCacheFile = getConfigOption('dynamic_directory' ).'/'.$this->settings['id'].'_titlecache.json' );

		if ( ! $lock->isLocked() {

			$lock->lock();
			$jsonData = json_encode( $titleData, True );
			
			$fhandle = fopen( $titleCacheFile, 'w' );
			fwrite($fhandle, $jsonData);

			$lock->unlock();

		}

		return $titleArray;

	}

	private function updateTitleCache(){



	}

	private function retrieveTagCache(){


	}

	private function buildTagCache(){


	}

	private function updateTagCache(){


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
    		$articles[] = new article( $this->post_directory.'/'.$post, $this->id );
    	}

    	return $articles;


	}


}

?>