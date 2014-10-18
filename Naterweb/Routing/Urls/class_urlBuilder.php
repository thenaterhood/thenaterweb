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

		$formatString = (substr($domain, -1) === '/') ? "%s" : "%s/";
		$formatString .= $friendlyUrl ? '' : '?url=';
		$firstPass = True;
		foreach ($this->urlParams as $key => $value) {
			$formatString .= $firstPass ? '%s/%s' : '/%s/%s';
			$params[] = urlencode($key);
			$params[] = urlencode($value);
			$firstPass = False;
		}

		$url = vsprintf($formatString, $params);

		if (substr($url, -1) === '/') {
			$url = rtrim($url, '/');
		}
		return $url;
	}


}
