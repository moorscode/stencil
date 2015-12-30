<?php
/**
 * Tag page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Tag
 */
class Stencil_Page_Type_Tag extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		$controller->set( 'tag', single_tag_title( '', false ) );
	}
}
