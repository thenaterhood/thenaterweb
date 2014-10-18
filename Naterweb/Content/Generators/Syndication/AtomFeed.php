<?php

namespace Naterweb\Content\Generators\Syndication;

include_once NWEB_ROOT.'/Content/Generators/Syndication/Feed.php';

use Naterweb\Content\Generators\Syndication\Feed;

class AtomFeed extends Feed
{
	/**
	 * Returns a displayable representation of the feed
	 * with appropriate code added.  Relies on the article 
	 * atom_output() function to generate code for individidual
	 * feed items. Returns ATOM format.
	 * 
	 */
	public function render() {

		Header('Content-type: application/atom+xml');

		$r = '<?xml version="1.0" encoding="UTF-8"?>';
		$r .='<feed xmlns="http://www.w3.org/2005/Atom"
xml:lang="en"
xml:base="'.\Naterweb\Engine\Configuration::get_option('site_domain').'/">';
		$r .= "\n";
		$r .= '<subtitle type="html">' . $this->description . "</subtitle>\n";
		$r .= "";
		$r .= "<id>" . $this->feedUrl . "</id>\n";
		$r .= "<title>" . $this->title . "</title>\n";
		$r .= "<updated>". $this->generationTime ."</updated>\n";
		$r .= "<author><name>".$this->author."</name></author>\n";
		$r .= '<atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" type="application/atom+xml" href="'.$this->feedUrl.'" />';
		echo $r;
		foreach ($this->items as $item) {
			$item->render_atom();
		}
		echo "</feed>";
	}
}
