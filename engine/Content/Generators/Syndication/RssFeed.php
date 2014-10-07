<?php

namespace Naterweb\Content\Generators\Syndication;
include_once NWEB_ROOT.'/Content/Generators/Syndication/Feed.php';

use Naterweb\Content\Generators\Syndication\Feed;

class RssFeed extends Feed
{
	
	/**
	 * Returns a displayable representation of the feed with 
	 * appropriate code added for RSS format.
	 */
	public function render() {
		
		Header('Content-type: application/atom+xml');
		# The code produced is not valid due to the xml tag 
		# which should have a ? before each <>. This breaks the
		# php.
		
		$r ='<xml version="1.0">';
		$r .= '<rss version = "2.0">\n';
		$r .= "<channel>";
		$r .= "<title>" . $this->title . "</title>";
		$r .= "<link>" . $this->feedUrl. "</link>";
		$r .= "<description>" . $this->description . "</description>";
		echo $r;
		foreach ($this->items as $item){
			$item->render_rss();
		}
		
		echo "</channel>";
		echo "</rss>";		
		
	}
}
