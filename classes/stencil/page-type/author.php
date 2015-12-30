<?php
/**
 * Author page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Author
 */
class Stencil_Page_Type_Author extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		$controller->set( 'author_display_name', get_the_author() );

		$object = get_queried_object();

		if ( class_exists( 'User', false ) ) {
			$controller->set( 'author', User::get( $object->ID ) );
		} else {
			$controller->set( 'author', $object );
		}
	}
}
