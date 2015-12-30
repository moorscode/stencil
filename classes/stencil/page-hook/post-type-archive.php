<?php
/**
 * Post Type Archive page hook
 *
 * @package Stencil\PageHook
 */

/**
 * Class Stencil_Page_Hook_Post_Type_Archive
 */
class Stencil_Page_Hook_Post_Type_Archive extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		Stencil_Environment::trigger( 'archive', $controller );
		Stencil_Environment::trigger( 'archive_' . get_post_type(), $controller );
	}
}
