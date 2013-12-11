<?php
include_once GNAT_ROOT.'/lib/core_auth.php';

class page extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();

		$approot = PAGE_ROOT;
		$configFile = $approot.'/mainsite.conf.xml';
		$this->readConfig( $configFile );

		$session = request::get_sanitized_as_object( array('name', 'track', 'konami', 'id') );

		$this->settings['approot'] = $approot;
		$this->pageData['session'] = $session;
		$this->pageData['static'] = $this->page_directory;
		$content = pullContent( array( $this->page_directory.'/page_'.$session->id, $this->page_directory.'/hidden_'.$session->id ) );
		$this->pageData['content'] = $content;
		$this->pageData['id'] = $content->title;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;



	}

	public function manage(){

		auth_user( getConfigOption('site_domain').'/'.$this->settings['id'].'/manage' );


		$this->pageData['content'] = pullContent( $this->settings['approot'].'/pages/page_manage' );
		$this->pageData['id'] = $this->settings['id'];
		$this->pageData['pages'] = $this->getPageList();
		$this->pageData['apphome'] = getConfigOption('site_domain').'/'.$this->settings['id'];

		$pageData = $this->pageData;

		include $this->settings['template'];
		
	
	}

	public function getPageList(){

		$pages = array();

		$handler = opendir( $this->page_directory );

		while( $file = readdir( $handler)){
			if ( $file != '.' && $file != '..' && substr($file, 0, 5) == 'page_' ){
				$nodeinfo = pathinfo($file);
				$pages[ $this->settings['page_directory'].'/'.$file ] = 
				     getConfigOption('site_domain').'/?url='.$this->settings['id'].'/'.substr($nodeinfo['filename'], 5);
			}
		}

		return $pages;

	}


}

?>
