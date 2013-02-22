<?php
    class atom_channel {
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

        public function new_item($title, $link, $description, $datestamp) {
            /*
             * Adds an item to the feed as an object in the object's
             * items array
             * 
             * Arguments:
             *  $title (str): the title of the item
             *  $link (str): the web address of the item's source
             *  $description (str): a description of or the content 
             *      of the item
             *  $datestamp (str): the official datestamp of the item
             */
            array_push($this->items, new feed_item($title, $link, $description, $datestamp));
        }
        
        public function output() {
            /*
             * Returns a displayable representation of the feed
             * with appropriate code added.
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
                $r .= $item->output();
            }
            $r .= "</feed>";
            return $r;
        }

    }

    class feed_item {
        /*
         * Creates the data object to contain an item in the feed
         */
        public $title, $link, $description, $datestamp;
        
        public function __construct($title, $link, $description, $datestamp) {
            /*
             * Creates the data object to contain the atom feed item.
             * 
             * Arguments:
             *  $title (str): the title of the item
             *  $link (str): the web address of the item source
             *  $description (str): the content of the item
             *  $datestamp (str): the atom-format datestamp of the item
             */
            $this->title = $title;
            $this->link = $link;
            $this->datestamp = $datestamp;
            $this->description = $description;
        }
        public function output() {
            /*
             * Produces the coded output of the item that can be 
             * returned and displayed or saved
             */
            $r = "<entry>";
            $r .= "<id>" . $this->link . "</id>";
            $r .= '<link href="'.$this->link.'" />';
            $r .= '<updated>'.$this->datestamp.'</updated>';
            $r .= "<title>" . $this->title . "</title>";
            $r .= "<content type='html'>" . $this->description . "</content>";
            $r .= "</entry>";
            return $r;
        }

}

?>
