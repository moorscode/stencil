<?php
/**
 * Taxonomy page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Taxonomy
 */
class Stencil_Page_Type_Taxonomy extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		$object = get_queried_object();

		if ( class_exists( 'Taxonomy', false ) ) {
			$controller->set( 'taxonomy', Taxonomy::get( $object->taxonomy ) );
		} else {
			$controller->set( 'taxonomy', $object );
		}
	}
}
