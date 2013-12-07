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

	public function getPostList(){

    	$posts = array();

    	$handler = opendir($this->post_directory);

    	while ($file = readdir($handler)) {

	      // if file isn't this directory or its parent, add it to the results
	      if ($file != "." && $file != "..") {
	        $posts[] = new article( $this->post_directory.'/'.$file, 'blog' );
	      }

	  }

	  return $posts;


	}


}

?>