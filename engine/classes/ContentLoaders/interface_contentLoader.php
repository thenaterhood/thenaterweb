<?php

namespace Content\Loaders;

interface ContentLoader{
	
	public function __construct( $file );

	public function __get( $field );

	public function getType();

	public function setTitle($title);

	public function render_html( $context );

	public function render_atom( $context );

	public function render_rss( $context );

	public function getMetadata();

}