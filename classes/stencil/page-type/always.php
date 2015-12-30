<?php
/**
 * Always page type
 *
 * @package Stencil\PageType
 */

/**
 * Class Stencil_Page_Type_Always
 */
class Stencil_Page_Type_Always extends Stencil_Abstract_Page_Type {

	/**
	 * Exection hook
	 *
	 * @param Stencil_Interface $controller Controller that initiated this class.
	 */
	public function execute( Stencil_Interface $controller ) {
		$url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$controller->set( 'self', $url );

		// Loading post(s) as object or array.
		$post_as_object = Stencil_Environment::filter( 'post_as_object', true );

		$object = get_queried_object();

		if ( $object && is_a( $object, 'WP_Post' ) ) {
			/**
			 * Filter to chose whether the 'post' variable is set as array or object
			 */

			if ( $post_as_object ) {
				if ( class_exists( 'Post', false ) ) {
					$controller->set( 'post', Post::get( $object->ID ) );
				} else {
					$controller->set( 'post', $object );
				}
			} else {
				$controller->set( 'post', (array) $object );
			}

			// Additional handy variables.
			$controller->set( 'id', $object->ID );
			$controller->set( 'post_type', get_post_type( $object ) );
		}
	}
}
