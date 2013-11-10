<?php
/**
 * Provides a self-sufficient file with the inventory
 * class, which abstracts access to a data file containing
 * an index of posts or articles in a directory.
 */

/**
 * Includes the necessary facilities for managing
 * the inventory
 */
include_once GNAT_ROOT.'/lib/core_web.php';
include_once GNAT_ROOT.'/classes/class_article.php';
include_once GNAT_ROOT.'/classes/class_directoryIndex.php';
include_once GNAT_ROOT.'/classes/class_urlset.php';

/**
 * Provides a database-like means of accessing an inventory
 * of posts or articles in a directory for efficiently searching
 * for things by certain criteria, as well as maintaining
 * and updating the data. Stored in json format currently, but
 * due to being abstracted out, could be modified to store
 * the data in other formats.
 *
 * @since 5/13/2013
 * @author Nate Levesque <public@thenaterhood.com>
 */
class inventory extends directoryIndex{


	public function __construct( $directory, $bloguri=NULL ){

		parent::__construct( $directory, $bloguri, "inventory" );

	}


	public function update(){

		$this->metadata['updated'] = date(DATE_ATOM);

		parent::update( "getMeta" );

	}

	/**
	 * Regenerates the blog inventory file
	 */
	public function regen(){

		$dbCols = array();
		$dbCols['tags'] = 'Text';
		$dbCols['nodeid'] = 'Text';
		$dbCols['title'] = 'Text';
		$dbCols['link'] = 'Text';
		$dbCols['datestamp'] = 'Text';
		$dbCols['author'] = 'Text';
		$dbCols['file'] = 'Text';

		$this->db->dropTable( 'main' );
		$this->db->createTable( 'main', $dbCols );

		$metadata = array();
		$metadata['sitemap'] = getConfigOption('site_domain').'/?url='.$this->bloguri.'/titles';
		$metadata['updated'] = date(DATE_ATOM);
		parent::regen( "getMeta", $metadata );

	}

	/**
	 * Returns all the inventory items that match a
	 * requested value in the requested field
	 *
	 * @param $field - the field to search in
	 * @param $value - the value to search for
	 *
	 * @return $matching - all the matching items in the inventory
	 */
	public function select( $field, $value ){

		return $this->db->selectSome( $field, $value, 'main' );

	}

	/**
	 * Returns a histogram of values mapped to 
	 * their occurances for the selected field
	 *
	 * @param $field - the name of the field to access
	 * @since 7/20/13
	 */
	public function getFieldStatistics( $field ){

		$fieldContents = array();

		foreach ($this->selectField( $field ) as $current ) {

			if ( ! is_array( $current ) ){
				$currentField = explode( ', ', $current );
			}
			else{
				$currentField = $current;
			}

			foreach ($currentField as $item) {
				if ( ! array_key_exists( $item, $fieldContents) )
					$fieldContents[$item] = 1;
				else
					$fieldContents[$item] = $fieldContents[$item] + 1;
			}

		}

		return $fieldContents;

	}

	/**
	 * Returns an array containing the data from a 
	 * particular field, with repeats filtered out
	 *
	 * @param $field - the name of the field to access
	 */
	public function selectField( $field ){

        $fieldContents = array();
        $fieldData = $this->db->selectColumn( $field, 'main' );                         
        foreach( $fieldData as $index => $current) {
                if ( ! is_array( $current ) ){
                                                                                        
                        $currentField = explode( ', ', $current );
                } else {                                                                
                        $currentField = $current;
                }
                foreach ( $currentField as $item ) {                                    
                        $fieldContents[] = $item;
                }       
        }       
        return $fieldContents; 


		#return array_unique( $this->db->query( 'SELECT '. $field . ' FROM main', array() ) );

	}

	/**
	 * Returns all the fields in the inventory
	 */
	public function selectAll(){
		return $this->db->selectTable( 'main' );
	}
	/**
	 * A function to return the inventory file. For supporting
	 * legacy functions
	 */
	public function getFile(){
		return $this->inventoryFile;
	}

}

?>
