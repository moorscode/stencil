<?php
/**
 * Post Type Archive post type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Post_Type_Archive
 */
class Stencil_Page_Type_Post_Type_Archive extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		$controller->set( 'post_type', get_post_type() );
	}
}
