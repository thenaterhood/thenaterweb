<?php

namespace Naterweb\Content\Generators\Sitemap;

include_once NWEB_ROOT.'/Content/Generators/Sitemap/Urlset.php';

use Naterweb\Content\Generators\Sitemap\Urlset;

class HtmlSitemap extends Urlset
{

    public function render()
    {
        $r = '<ul>'."\n";

		foreach ($this->items as $item) {
			$r .= '<li>'.$item->toHtml().'</li>'."\n";
		}

		$r .= "</ul>\n";

		print $r;
    }
    
}