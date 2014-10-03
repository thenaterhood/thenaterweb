<?php 

include_once NWEB_ROOT.'/lib/core_blog.php';

use Naterweb\Engine\Configuration;
use Naterweb\Routing\Urls\UrlBuilder;

abstract class ControllerBase{

	protected $settings;
	protected $configuration;
	protected $pageData;

	public function __construct(){

		$this->pageData = array();



	}

	/**
	 * Returns a map containing all of the controller 
	 * settings.
	 *
	 * @return a map of the class settings
	 */
	public function getConfigurables(){
		return $this->settings;
	}

	/**
	 * Reads an XML configuration file and sets the 
	 * class settings field
	 * 
	 * @param $path - the path to the config file
	 */
	public function readConfig( $path ){

		$confFile = $path;
	    $conf = array();
	    $conf['title'] = "Error";
	    $conf['catchline'] = "";
	    $conf['commentCode'] = "";
	    
	    
	    if ( file_exists($confFile) ){
	            $xml = simplexml_load_file( $path );
	            $conf = xmltoArray( $xml ); 
	            
	            
	    }

	    $this->settings = (array) RecArrayToObject($conf);
	    $this->settings['configFile'] = $path;

	    if ( !array_key_exists( 'template', $this->settings ) )
	    	$this->settings['template'] = NWEB_ROOT.'/config/template.d/generic_template.php';

	}


	/**
	 * Saves an xml configuration file. This is untested!
	 *
	 * @param $path - the path to the file to save
	 */
	public function saveConfig( $path ){

		$confFile = fopen( $path, 'w' );

		$xml = new SimpleXMLElement('<xml/>');
		$control = $xml->addChild('control');

		foreach ($this->settings as $key => $value) {

			$control->addChild($key, $value);


		}

		fwrite( $confFile, $xml->toXml() );
		fclose( $confFile );


	}

	/**
	 * Allows the class settings to be overwritten en-masse 
	 * externally by providing a new map.
	 *
	 * @param $map - the new settings array to use
	 */
	public function setConfig( $map ){
		$this->settings = $map;
	}

	/**
	 * Returns requested fields from the settings 
	 * array.
	 *
	 * @param $field - the name of the item to return
	 * @return the item
	 */
	public function __get( $field ){
                if ( $field == 'id'){
                    return get_called_class();
                } else {
		return $this->settings[$field];
                }
	}

	public function getPageData(){
		return $this->pageData;
	}

	/**
	 * Returns a list of pages that should be 
	 * available publicly. Used by the sitemap 
	 * generation system.
	 */
	public function getPageList(){
		return array();
	}

	/**
	 * Returns a list of posts (in order) that should be 
	 * available publicly. Used by the feed 
	 * generation system.
	 */
	public function getPostList(){
		return array();
	}
        
        public function unauthorized(){
            echo "403: You are not authorized to access this page.";
            die();
    }

    public function robotstxt(){
    	header("Content-Type: text/plain");
    	echo "User-agent: * \n";
    	echo "Allow: / \n";
    	echo "\n";
	$feedUrl = new UrlBuilder(array(get_called_class()=>'feed'));
	$mapUrl = new UrlBuilder(array(get_called_class()=>'sitemap'));
    	echo "Sitemap: " . $mapUrl->build() . "\n";
    	echo "Sitemap: " . $feedUrl->build();
    }

}


