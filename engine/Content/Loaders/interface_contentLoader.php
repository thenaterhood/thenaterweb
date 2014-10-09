<?php

namespace Naterweb\Content\Loaders;

abstract class ContentLoader{

	abstract public function __construct( $file );

	abstract public function __get( $field );

	public function getType()
	{
		return get_called_class();
	}

	abstract public function setTitle($title);

	abstract public function setUri($uri);

	abstract public function render_html( $context );

	abstract public function render_atom( $context );

	abstract public function render_rss( $context );

	abstract public function getMetadata();

}
