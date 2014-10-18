<?php

namespace Naterweb\Content\Renderers;

include NWEB_ROOT.'/Content/Renderers/interface_contentRendererInterface.php';

use Naterweb\Content\Renderers\ContentRendererInterface;
use Naterweb\Client\SessionMgr;

class PhpRenderer implements ContentRendererInterface {

	private $template;
	private $pageData;
	private $use_csrf;

	public function __construct($template, $use_csrf=True)
	{
		$this->template = $template;
		$this->use_csrf = $use_csrf;
		$this->pageData = array();
	}

	public function render()
	{
		$page = (object)$this->pageData;

    		if ( $this->use_csrf ){
		        $sessionmgr = SessionMgr::getInstance();

		        $page->csrf_token = $sessionmgr->get_csrf_token();
		        $page->csrf_key = $sessionmgr->get_csrf_id();
    		}

		if ( file_exists($this->template) ){
			include $this->template;
		} else {
			throw new Exception('Template could not be loaded.');
		}
	}

	public function set_value($name, $value)
	{
		$this->pageData[$name] = $value;
	}

	public function bulk_set_values($values)
	{
		$this->pageData = array_merge($this->pageData, $values);
	}

}


