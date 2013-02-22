<?php
	class atom_feed {
		/*
		* Defines a data object to contain an atom feed as items
		* are added and the feed is updated then returned
		*/
		public $title, $link, $description, $feedstamp;
		
		public function __construct($title, $link, $description, $feedstamp) {
			/*
			* Creates an empty atom feed object with metadata
			* 
			* Arguments:
			*  $title (str): a title for the atom feed
			*  $link (str): the base url for the feed
			*  $description (str): a description or summary of the feed
			*  $feedstamp (str): a datestamp for the feed, in standard atom format
			*/
			$this->title = $title;
			$this->link = $link;
			$this->description = $description;
			$this->feedstamp = $feedstamp;
			$this->items = array();

		}

		public function new_item($postObject) {
			/*
			* Adds an item to the feed as an object in the object's
			* items array
			* 
			* Arguments:
			*  $postObject: a fully initialized instance of the postObj
			*	class.
			* 
			* TODO: use this directly rather than copying fields
			* into another object
			*/
			array_push($this->items, $postObject);
		}
		
		public function output() {
			/*
			* Returns a displayable representation of the feed
			* with appropriate code added.  Relies on the postObj 
			* atom_output() function to generate code for individidual
			* feed items.
			*/
			$r ='<feed xmlns="http://www.w3.org/2005/Atom"
	xml:lang="en"
	xml:base="http://www.thenaterhood.com/">';
			$r .= "\n";
			$r .= '<subtitle type="html">' . $this->description . "</subtitle>\n";
			$r .= "";
			$r .= "<id>" . $this->link . "</id>\n";
			$r .= "<title>" . $this->title . "</title>\n";
			$r .= "<updated>". $this->feedstamp ."</updated>\n";
			$r .= "<author>"."<name>Nate Levesque</name>"."</author>\n";
			foreach ($this->items as $item) {
				$r .= $item->atom_output();
			}
			$r .= "</feed>";
			return $r;
		}

	}

?>
