<?php

namespace Naterweb\Content\Generators\Sitemap;

include_once NWEB_ROOT.'/Content/Generators/Sitemap/Urlset.php';

use Naterweb\Content\Generators\Sitemap\Urlset;

class XmlSitemap extends Urlset
{

    public function render()
    {
		$r ='<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$r .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'."\n";
		$r .= "\n";
		foreach ($this->items as $item) {
			$r .= $item->toXml();
		}
		$r .= "</urlset>";
		
		Header('Content-Type: text/xml');
		print $r;
    }
    
}