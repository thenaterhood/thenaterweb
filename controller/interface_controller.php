<?php 

include_once GNAT_ROOT.'/lib/core_blog.php';

abstract class controllerBase{

	protected $settings;
	protected $configuration;

	public function getConfigurables(){
		return $this->settings;
	}

	public function readConfig( $path ){

		$confFile = $path;
	    $conf = array();
	    $conf['title'] = "Error";
	    $conf['catchline'] = "";
	    $conf['commentCode'] = "";
	    
	    
	    if ( file_exists($confFile) ){
	            $xml = simplexml_load_file( GNAT_ROOT.'/config/section.d/'.$id.'.conf.xml' );
	            $conf = xmltoArray( $xml ); 
	            
	            
	    }

	    $this->settings = (array) arrayToObject($conf);

	    if ( !array_key_exists( 'template', $this->settings ) )
	    	$this->settings['template'] = GNAT_ROOT.'/config/template.d/generic_template.php';

	}


	public function saveConfig( $path ){

		$confFile = fopen( $path, 'w' );

		$xml = new SimpleXMLElement('<xml/>');
		$control = $xml->addChild('control');

		#Header('Content-type: text/xml');

		foreach ($this->settings as $key => $value) {

			$control->addChild($key, $value);


		}

		fwrite( $confFile, $xml->toXml() );
		fclose( $confFile );


	}

	public function setConfig( $map ){
		$this->settings = $map;
	}

	public function __get( $field ){
		return $this->settings[$field];
	}

}

?>