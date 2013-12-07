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
		$session = $this->pageData['session'];

		$this->pageData['displaypost'] = new article( $this->post_directory.'/'.$session->node, $this->settings['id'] );

		$pageData = $this->pageData;

		include $this->template;

	}

	public function home(){

		$this->pageData['content'] = pullContent( BLOG_ROOT.'/pages/page_home' );
		$session = $this->pageData['session'];
		$this->pageData['articles'] = $this->getPostRange( $session->start, $session->end );

		$pageData = $this->pageData;

		include $this->template;

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