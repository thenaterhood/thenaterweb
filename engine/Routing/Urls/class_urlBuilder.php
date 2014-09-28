<?php

namespace Naterweb\Routing\Urls;

use Naterweb\Engine\Configuration;

class UrlBuilder {

	private $urlParams;

	public function __construct($params)
	{
		$this->urlParams = $params;
	}

	public function build()
	{
		$friendlyUrl = Configuration::get_option('friendly_urls');
		$domain = Configuration::get_option('site_domain');

		$params = array();
		$params[] = $domain;

		$formatString = "%s";
		$formatString .= $friendlyUrl ? '' : '?url=';
		$firstPass = True;
		foreach ($this->urlParams as $key => $value) {
			$formatString .= $firstPass ? '%s/%s' : '/%s/%s';
			$params[] = urlencode($key);
			$params[] = urlencode($value);
			$firstPass = False;
		}

		return vsprintf($formatString, $params);
	}


}
