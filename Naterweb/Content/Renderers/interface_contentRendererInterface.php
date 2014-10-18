<?php

namespace Naterweb\Content\Renderers;

interface ContentRendererInterface {
	public function render();
	public function set_value($name, $value);
	public function bulk_set_values($values);
}
