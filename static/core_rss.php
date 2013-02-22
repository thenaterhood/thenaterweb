<?php
	class RSS_channel {
		public $title, $link, $description;
		
		public function __construct($title, $link, $description) {
			$this->title = $title;
			$this->link = $link;
			$this->description = $description;
			$this->items = array();

		}

		public function new_item($title, $link, $description) {
			array_push($this->items, new RSS_item($title, $link, $description));
		}

		public function RSS_header() {
			$r ='<?xml version="1.0"?>';
			$r .= '<rss version = "2.0">';
			return $r;
		}

		public function output() {
			<feed xmlns="http://www.w3.org/2005/Atom"
	  xml:base="http://www.example.org/">
			$r .= "";
			$r .= "<channel>";
			$r .= "<title>" . $this->title . "</title>";
			$r .= "<link>" . $this->link . "</link>";
			$r .= "<description>" . $this->description . "</description>";
			foreach ($this->items as $item) {
				$r .= $item->output();
			}
			$r .= "</channel>";
			$r .= "</rss>";
			return $r;
		}

	}

	class RSS_item {
		public $title, $link, $description;
		
		public function __construct($title, $link, $description) {
			$this->title = $title;
			$this->link = $link;
			$this->description = $description;
		}
		public function output() {
			$r = "<item>";
			$r .= "<title>" . $this->title . "</title>";
			$r .= "<link>" . $this->link . "</link>";
			$r .= "<description>" . $this->description . "</description>";
			$r .= "</item>";
			return $r;
		}

}

?>
