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
		$friendlyUrl = Configuration::get_config_option('friendly_urls');
		$domain = Configuration::get_config_option('site_domain');

		$params = array();
		$params[] = $domain;

		$formatString = "%s/";
		$formatString .= $friendlyUrl ? '' : '?url=';

		foreach ($this->urlParams as $key => $value) {
			$formatString .= '%s/%s/';
			$params[] = urlencode($key);
			$params[] = urlencode($value);
		}

		return vsprintf($formatString, $params);
	}


}
